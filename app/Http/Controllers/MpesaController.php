<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MpesaService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MpesaController extends Controller
{
    protected $mpesaService;

    /**
     * MpesaController constructor.
     *
     * @param MpesaService $mpesaService
     */
    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Register URLs for C2B transactions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUrl()
    {
        try {
            $response = $this->mpesaService->register();

            $response = json_decode($response, true);

            if (isset($response['ResponseCode'])) {
                return response()->json(['success' => 'URLs registered successfully'], 200);
            } else {
                return response()->json(['error' => $response['errorMessage']], 500);
            }
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Initiate an STK Push.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stkPush(Request $request)
    {
        try {
            $request->validate([
                'reference' => 'required|string',
                'phoneNumber' => 'required|string',
                'amount' => 'required|numeric|min:1',
            ]);

            $response = $this->mpesaService->stkPush(
                $request->input('phoneNumber'),
                $request->input('amount'),
                $request->input('reference'),
                'Payment initiated successfully'
            );

            $response = json_decode($response, true);

            if ($response['ResponseCode'] === '0') {
                return response()->json(['success' => 'Payment initiated successfully, kindly check your phone and enter MPESA pin to complete the transaction']);
            }
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Handle validation request from M-Pesa.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validation(Request $request)
    {
        try {
            // Retrieve the raw JSON input
            $rawInput = file_get_contents('php://input');
            // Decode the JSON response
            $decodedResponse = json_decode($rawInput, true);

            // Store raw input data in public/mpesa_logs
            storeLog('mpesa_logs/validation', $decodedResponse);

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error'], 500);
        }
    }

    /**
     * Handle callback request from M-Pesa.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        try {
            // Retrieve the raw JSON input
            $rawInput = file_get_contents('php://input');

            // Decode the JSON response
            $decodedResponse = json_decode($rawInput, true);

            // Store raw input data in public/mpesa_logs for debugging
            storeLog('mpesa_logs/callback', $decodedResponse);
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Handle confirmation request from M-Pesa.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmation(Request $request)
    {
        try {
            // Retrieve the raw JSON input
            $rawInput = file_get_contents('php://input');
            // Decode the JSON response
            $decodedResponse = json_decode($rawInput, true);

            // Store raw input data in public/mpesa_logs
            storeLog('mpesa_logs/confirmation', $decodedResponse);

            // Check if the response is valid
            if (!isset($decodedResponse['TransID']) || !isset($decodedResponse['TransAmount']) || !isset($decodedResponse['MSISDN']) || !isset($decodedResponse['FirstName'])) {
                Log::error('Invalid confirmation data.', ['raw_input' => $rawInput]);
                return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
            }

            // Extract necessary fields
            $transactionId = $decodedResponse['TransID'];
            $amount = $decodedResponse['TransAmount'];
            $phoneNumber = $decodedResponse['MSISDN'];
            $firstName = $decodedResponse['FirstName'];
            $reference = $decodedResponse['BillRefNumber'] ?? ''; // Use BillRefNumber as reference if available

            // Store The Response to Database
            $this->storeTransaction([
                'transaction_id' => $transactionId,
                'name' => $firstName,
                'amount' => $amount,
                'phone_number' => $phoneNumber,
                'status' => 'success',
                'reference' => $reference,
                'transaction_type' => 'deposit'
            ]);

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error'], 500);
        }
    }

    /**
     * Store transaction details in the database.
     *
     * @param array $data
     * @return void
     */
    protected function storeTransaction(array $data)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            $referencePhoneNumber = formatPhoneNumber($data['reference']);

            // Get user
            $userInfo = User::withCount('mpesaTransactions')
                ->where('phone', $referencePhoneNumber)
                ->first();

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            Log::error('Error storing transaction: ' . $e->getMessage());
        }
    }
}
