<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    protected $mpesaService;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/portal/dashboard';

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
        $this->middleware('guest');
    }

    /**
     * Show the registration form with subscription plans.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('auth.register', ['subscription_plans' => $plans]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Begin database transaction
            DB::beginTransaction();

            // Create the user
            $user = $this->create($request->all());

            $subscriptionData = null;
            // Process subscription if plan was selected
            if ($request->has('planId') && $request->planId) {
                $subscriptionData = $this->processSubscription($user, $request);
            }

            // Send verification email
            $user->sendEmailVerificationNotification();

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Please check your email for verification.',
                'data' => [
                    'user' => $user,
                    'subscription' => $subscriptionData,
                ],
                'redirect' => $this->redirectTo
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process the user's subscription
     *
     * @param User $user
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    protected function processSubscription(User $user, Request $request)
    {
        $plan = SubscriptionPlan::findOrFail($request->planId);
        $paymentResponse = null;

        // Process payment based on method
        switch ($request->paymentMethod) {
            case 'mpesa':
                $paymentResponse = $this->processMpesaPayment($user, $plan, $request->phoneNumber);
                break;
            case 'card':
                // Future implementation for card payments
                break;
            default:
                throw new \Exception('Invalid payment method');
        }

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_method' => $request->paymentMethod,
            'end_date' => now()->addDays($plan->duration_days)
        ]);

        return [
            'subscription' => $subscription,
            'payment_response' => $paymentResponse
        ];
    }

    /**
     * Process M-Pesa payment
     *
     * @param User $user
     * @param SubscriptionPlan $plan
     * @param string $phoneNumber
     * @return array
     * @throws \Exception
     */
    protected function processMpesaPayment(User $user, SubscriptionPlan $plan, $phoneNumber)
    {
        try {
            $phoneNumber = formatPhoneNumber($phoneNumber);

            if (!preg_match('/^254[17]\d{8}$/', $phoneNumber)) {
                throw new \Exception('Invalid M-Pesa phone number. Format: 2547XXXXXXXX or 2541XXXXXXXX');
            }

            if ($plan->price <= 0) {
                throw new \Exception('Invalid payment amount');
            }

            $response = $this->mpesaService->stkPush(
                $phoneNumber,
                number_format($plan->price, 0),
                $user->id,
                'Subscription payment for ' . $plan->name
            );

            Log::debug('M-Pesa STK Push Response:', ['response' => $response]);

            $responseData = json_decode($response, true);

            if (!isset($responseData['ResponseCode']) || $responseData['ResponseCode'] !== '0') {
                $error = $responseData['errorMessage'] ?? 'M-Pesa payment request failed';
                throw new \Exception($error);
            }

            Log::info("M-Pesa payment initiated", [
                'user_id' => $user->id,
                'phone' => $phoneNumber,
                'amount' => $plan->price,
                'plan' => $plan->name,
                'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null
            ]);

            return [
                'merchant_request_id' => $responseData['MerchantRequestID'],
                'checkout_request_id' => $responseData['CheckoutRequestID'],
                'response_code' => $responseData['ResponseCode'],
                'response_description' => $responseData['ResponseDescription']
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa payment failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (isset($data['planId']) && $data['planId']) {
            $rules['planId'] = ['required', 'exists:subscription_plans,id'];
            $rules['paymentMethod'] = ['required', 'in:mpesa,card'];

            if ($data['paymentMethod'] === 'mpesa') {
                $rules['phoneNumber'] = ['required', 'string', 'regex:/^(\+254|0)[17]\d{8}$/'];
            }
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phoneNumber'] ?? null,
            'password' => Hash::make($data['password']),
            'email_verification_token' => Str::random(60),
        ]);
    }
}
