<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\MpesaPayment;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    protected $mpesaService;

    /**
     * Constructor to inject MpesaService.
     *
     * @param MpesaService $mpesaService
     */
    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Register confirmation and validation URLs with M-Pesa API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUrl()
    {
        try {
            $response = json_decode($this->mpesaService->register(), true);

            if (isset($response['ResponseCode'])) {
                return response()->json(['success' => 'URLs registered successfully'], 200);
            }

            return response()->json(['error' => $response['errorMessage'] ?? 'Unknown error'], 500);
        } catch (\Exception $exception) {
            Log::error('Register URL Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['error' => 'Failed to register URLs'], 500);
        }
    }

    /**
     * Initiate STK Push to customer's phone.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stkPush(Request $request)
    {
        $request->validate([
            'reference'    => 'required|string',
            'phoneNumber'  => 'required|string',
            'amount'       => 'required|numeric|min:1',
        ]);

        try {
            $response = json_decode($this->mpesaService->stkPush(
                $request->input('phoneNumber'),
                number_format($request->input('amount'), 0),
                $request->input('reference'),
                'Payment initiated successfully'
            ), true);

            if (isset($response['ResponseCode']) && $response['ResponseCode'] === '0') {
                return response()->json([
                    'success' => 'Payment initiated successfully, kindly check your phone and complete the transaction.',
                ]);
            }

            return response()->json(['error' => $response['errorMessage'] ?? 'Failed to initiate payment.'], 500);
        } catch (\Exception $exception) {
            Log::error('STK Push Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['error' => 'Failed to initiate payment.'], 500);
        }
    }

    /**
     * Handle validation URL requests from M-Pesa API.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validation(Request $request)
    {
        try {
            $decodedResponse = json_decode(file_get_contents('php://input'), true);

            storeLog('mpesa_logs/validation', $decodedResponse);

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Validation received successfully']);
        } catch (\Exception $exception) {
            Log::error('Validation Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Validation failed'], 500);
        }
    }

    /**
     * Handle callback notifications from M-Pesa API after C2B.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        try {
            $decodedResponse = json_decode(file_get_contents('php://input'), true);

            storeLog('mpesa_logs/callback', $decodedResponse);

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Callback received successfully']);
        } catch (\Exception $exception) {
            Log::error('Callback Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['error' => 'Callback failed.'], 500);
        }
    }

    /**
     * Handle confirmation request from M-Pesa API after customer payment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmation(Request $request)
    {
        try {
            $decodedResponse = json_decode(file_get_contents('php://input'), true);

            storeLog('mpesa_logs/confirmation', $decodedResponse);

            // Validate required fields
            if (!isset($decodedResponse['TransID'], $decodedResponse['TransAmount'], $decodedResponse['MSISDN'], $decodedResponse['FirstName'])) {
                Log::error('Invalid confirmation data.', ['data' => $decodedResponse]);
                return response()->json(['status' => 'error', 'message' => 'Invalid data received'], 400);
            }

            // Store transaction
            $this->storeTransaction([
                'transaction_id'   => $decodedResponse['TransID'],
                'name'             => $decodedResponse['FirstName'],
                'amount'           => $decodedResponse['TransAmount'],
                'phone_number'     => $decodedResponse['MSISDN'],
                'status'           => 'success',
                'reference'        => $decodedResponse['BillRefNumber'] ?? '',
                'transaction_type' => 'deposit',
            ]);

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Confirmation processed successfully']);
        } catch (\Exception $exception) {
            Log::error('Confirmation Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Confirmation processing failed'], 500);
        }
    }

    /**
     * Save transaction record to Subscription and MpesaPayment models.
     *
     * @param array $data
     * @return void
     */
    protected function storeTransaction(array $data)
    {
        DB::beginTransaction();

        try {
            $subscription = Subscription::with(['user', 'plan'])
                ->where('id', $data['reference'])
                ->firstOrFail();

            $transactionId = $data['transaction_id'];
            $amount = (float) $data['amount'];

            if (empty($transactionId)) {
                throw new \Exception('Transaction ID is required');
            }

            // Update Subscription payments
            $currentReferences = $subscription->payment_references ?? [];
            $updatedReferences = array_merge($currentReferences, [$transactionId]);
            $subscription->payment_references = array_unique($updatedReferences);
            $subscription->amount_paid += $amount;

            // Update payment status
            if ($subscription->amount_paid >= $subscription->plan->price) {
                $subscription->payment_status = 'paid';
                $subscription->end_date = now()->addMonths($subscription->plan->duration_months);

                if ($subscription->is_auto_renew) {
                    $subscription->start_date = $subscription->end_date;
                    $subscription->end_date = $subscription->start_date->copy()->addMonths($subscription->plan->duration_months);
                }
            } elseif ($subscription->amount_paid > 0) {
                $subscription->payment_status = 'partial';
            }

            $subscription->save();

            // Save transaction in MpesaPayment table
            MpesaPayment::create([
                'user_id'         => $subscription->user->id,
                'subscription_id' => $subscription->id,
                'transaction_id'  => $transactionId,
                'reference'       => $data['reference'],
                'phone_number'    => $data['phone_number'],
                'name'            => $data['name'],
                'amount'          => $amount,
                'status'          => $data['status'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error('Transaction Store Error: ' . $exception->getMessage(), [
                'subscription_id' => $data['reference'] ?? null,
                'transaction_id'  => $transactionId ?? null,
                'amount'          => $amount ?? null,
            ]);

            throw $exception;
        }
    }
}
