<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MpesaController;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\MpesaPayment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     */
    protected string $redirectTo = '/portal/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form with subscription plans.
     */
    public function showRegistrationForm()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('auth.register', ['subscription_plans' => $plans]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        try {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            DB::beginTransaction();

            // Create user
            $user = $this->create($request->all());

            // Create subscription if plan is selected
            $subscriptionData = null;
            if ($request->filled('planId')) {
                $subscriptionData = $this->processSubscription($user, $request);
            }

            $user->sendEmailVerificationNotification();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Please verify your email.',
                'data' => [
                    'user' => $user,
                    'subscription' => $subscriptionData,
                ],
                'redirect' => $this->redirectTo
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
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
     * Process the user's subscription and payment.
     */
    protected function processSubscription(User $user, Request $request): array
    {
        $plan = SubscriptionPlan::findOrFail($request->planId);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_method' => $request->paymentMethod,
            'amount_billed' => $plan->price,
            'end_date' => now()->addDays($plan->duration_days),
        ]);

        if (!$subscription) {
            throw new \Exception('Failed to create subscription.');
        }

        switch ($request->paymentMethod) {
            case 'mpesa':
                // Perform STK Push
                $this->mpesaController()->stkPush(new Request([
                    'reference' => (string) $subscription->id,
                    'phoneNumber' => $user->phone,
                    'amount' => $plan->price,
                ]));
                break;

            case 'card':
                // Future: Implement card payment logic
                break;

            default:
                throw new \Exception('Invalid payment method selected.');
        }

        return ['subscription' => $subscription];
    }

    /**
     * Validate incoming registration request.
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (!empty($data['planId'])) {
            $rules['planId'] = ['required', 'exists:subscription_plans,id'];
            $rules['paymentMethod'] = ['required', 'in:mpesa,card'];

            if (($data['paymentMethod'] ?? null) === 'mpesa') {
                $rules['phoneNumber'] = ['required', 'regex:/^(\+254|0)[17]\d{8}$/'];
            }
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance.
     */
    protected function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phoneNumber'] ?? null,
            'password' => Hash::make($data['password']),
            'email_verification_token' => Str::random(60),
        ]);
    }

    /**
     * Resolve MpesaController from container.
     */
    private function mpesaController(): MpesaController
    {
        return app(MpesaController::class);
    }
}
