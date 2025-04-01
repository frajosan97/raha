<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use MpesaSdk\AccountBalance;
use MpesaSdk\StkPush;
use MpesaSdk\B2c;
use MpesaSdk\C2b;
use MpesaSdk\B2BExpressCheckOut;
use MpesaSdk\Reversals;
use MpesaSdk\TransactionStatus;

class MpesaService
{
    protected $c2b;
    protected $b2c;
    protected $b2b;
    protected $stkPush;
    protected $reversal;
    protected $accBalance;
    protected $transactionStatus;

    /*
     * MpesaService constructor.
     * 
     * Initializes the service with necessary SDK instances.
     * 
     * @param C2b $c2b Instance of the C2b SDK class.
     * @param B2c $b2c Instance of the B2c SDK class.
     * @param B2BExpressCheckOut $b2b Instance of the B2BExpressCheckOut SDK class.
     * @param StkPush $stkPush Instance of the StkPush SDK class.
     * @param Reversals $reversal Instance of the Reversals SDK class.
     * @param AccountBalance $accBalance Instance of the AccountBalance SDK class.
     * @param TransactionStatus $transactionStatus Instance of the TransactionStatus SDK class.
     */
    public function __construct(
        C2b $c2b,
        B2c $b2c,
        B2BExpressCheckOut $b2b,
        StkPush $stkPush,
        Reversals $reversal,
        AccountBalance $accBalance,
        TransactionStatus $transactionStatus
    ) {
        $this->c2b = $c2b;
        $this->b2c = $b2c;
        $this->b2b = $b2b;
        $this->stkPush = $stkPush;
        $this->reversal = $reversal;
        $this->accBalance = $accBalance;
        $this->transactionStatus = $transactionStatus;
    }

    /*
     * Register URLs for C2B transactions.
     * 
     * This method registers the confirmation and validation URLs for the C2B transactions.
     * 
     * @param string $shortcode The shortcode for the business.
     * @param string $confirmationUrl The URL for confirmation.
     * @param string $validationUrl The URL for validation.
     * @return mixed The response from the C2B registration API.
     */
    public function register()
    {
        $response = $this->c2b->register();
        Log::info('URLs registered.', ['response' => $response]);
        return $response;
    }

    /*
     * Simulate a C2B transaction.
     * 
     * This method simulates a Customer to Business (C2B) transaction for testing purposes.
     * 
     * @param string $shortcode The shortcode for the business.
     * @param float $amount The amount to be transacted.
     * @param string $msisdn The mobile number of the customer.
     * @param string $billRefNumber The bill reference number.
     * @return mixed The response from the C2B simulation API.
     */
    public function simulateC2B($msisdn, $amount, $billRefNumber)
    {
        $response = $this->c2b->simulate($msisdn, $amount, $billRefNumber);
        Log::info('C2B simulation done.', ['amount' => $amount, 'msisdn' => $msisdn, 'billRefNumber' => $billRefNumber, 'response' => $response]);
        return $response;
    }

    /*
     * Initiate an STK Push transaction.
     * 
     * This method initiates a Safaricom STK Push to the customer's phone.
     * 
     * @param string $phoneNumber The customer's phone number.
     * @param float $amount The amount to be transacted.
     * @param string $reference A reference for the transaction.
     * @param string $description A description for the transaction.
     * @return mixed The response from the STK Push API.
     */
    public function stkPush($phoneNumber, $amount, $reference, $description)
    {
        $phoneNumber = formatPhoneNumber($phoneNumber);
        $response = $this->stkPush->initiate($phoneNumber, $amount, $reference, $description);
        Log::info('STK Push initiated.', ['request' => compact('phoneNumber', 'amount', 'reference', 'description'), 'response' => $response]);
        return $response;
    }

    /*
     * Query the status of an STK Push transaction.
     * 
     * This method queries the status of a previously initiated STK Push transaction.
     * 
     * @param string $checkoutRequestId The checkout request ID for the STK Push transaction.
     * @return mixed The response from the STK Push status API.
     */
    public function stkPushStatus($checkoutRequestId)
    {
        $response = $this->stkPush->query($checkoutRequestId);
        Log::info('STK Push status queried.', ['checkout_request_id' => $checkoutRequestId, 'response' => $response]);
        return $response;
    }

    /*
     * Initiate a B2C transaction.
     * 
     * This method initiates a Business to Customer (B2C) transaction.
     * 
     * @param string $phoneNumber The customer's phone number.
     * @param float $amount The amount to be transacted.
     * @param string $remarks Remarks for the transaction.
     * @param string $occasion The occasion for the transaction.
     * @return mixed The response from the B2C API.
     */
    public function b2c($Amount, $PhoneNumber, $CommandID, $Occasion, $Remarks, $OriginatorConversationID)
    {
        $PhoneNumber = formatPhoneNumber($PhoneNumber);
        $response = $this->b2c->b2c($Amount, $PhoneNumber, $CommandID, $Occasion, $Remarks, $OriginatorConversationID);
        Log::info('B2C transaction initiated.', ['request' => compact('Amount', 'PhoneNumber', 'Occasion', 'OriginatorConversationID'), 'response' => $response]);
        return $response;
    }

    /*
     * Register a C2B transaction.
     * 
     * This method registers a Customer to Business (C2B) transaction.
     * 
     * @param string $shortcode The shortcode for the business.
     * @param float $amount The amount to be transacted.
     * @param string $phoneNumber The customer's phone number.
     * @param string $billRefNumber The bill reference number.
     * @return mixed The response from the C2B registration API.
     */
    public function c2b($shortcode, $amount, $phoneNumber, $billRefNumber)
    {
        $phoneNumber = formatPhoneNumber($phoneNumber);
        $response = $this->c2b->register();
        Log::info('C2B transaction registered.', ['request' => compact('shortcode', 'amount', 'phoneNumber', 'billRefNumber'), 'response' => $response]);
        return $response;
    }

    /*
     * Initiate a B2B transaction.
     * 
     * This method initiates a Business to Business (B2B) transaction.
     * 
     * @param float $amount The amount to be transacted.
     * @param string $primaryShortCode The sender's party.
     * @param string $receiverShortCode The receiver's party.
     * @param string $remarks Remarks for the transaction.
     * @param string $accountReference The account reference for the transaction.
     * @return mixed The response from the B2B API.
     */
    public function b2b($amount, $primaryShortCode, $receiverShortCode)
    {
        $response = $this->b2b->b2bexpresscheckout($amount, $primaryShortCode, $receiverShortCode);
        Log::info('B2B transaction initiated.', ['request' => compact('amount', 'primaryShortCode', 'receiverShortCode'), 'response' => $response]);
        return $response;
    }

    /*
     * Reverse a transaction.
     * 
     * This method reverses a previously completed transaction.
     * 
     * @param string $transactionId The ID of the transaction to be reversed.
     * @param float $amount The amount to be reversed.
     * @return mixed The response from the reversal API.
     */
    public function reverse($transactionId, $amount)
    {
        $response = $this->reversal->reversals($transactionId, $amount);
        Log::info('Transaction reversed.', ['request' => compact('transactionId', 'amount'), 'response' => $response]);
        return $response;
    }

    /*
     * Query the account balance.
     * 
     * This method queries the balance of a specific shortcode account.
     * 
     * @param string $shortcode The shortcode for the business.
     * @return mixed The response from the AccountBalance API.
     */
    public function accountBalance()
    {
        $response = $this->accBalance->AccountBalance();
        Log::info('Account balance queried.', ['response' => $response]);
        return $response;
    }

    /*
     * Get the status of a transaction.
     * 
     * This method retrieves the status of a specific transaction.
     * 
     * @param string $transactionId The ID of the transaction.
     * @param string $OriginatorConversationID The originator conversation ID.
     * @return mixed The response from the TransactionStatus API.
     */
    public function getTransactionStatus($transactionId, $OriginatorConversationID)
    {
        $response = $this->transactionStatus->transactionStatus($transactionId, $OriginatorConversationID);
        Log::info('Transaction status queried.', ['request' => compact('transactionId', 'OriginatorConversationID'), 'response' => $response]);
        return $response;
    } 
}
