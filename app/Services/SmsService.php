<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $apiUrl;
    protected $senderId;
    protected $client;

    public function __construct()
    {
        $this->apiKey = config('sms.api_key');
        $this->apiUrl = rtrim(config('sms.api_url'), '/'); // Ensure no trailing slash
        $this->senderId = config('sms.sender_id');
        $this->client = new Client(['timeout' => 15]); // Initialize Guzzle client
    }

    /**
     * Send a single SMS message
     */
    public function sendSms($recipient, $message)
    {
        try {
            $response = $this->client->post("{$this->apiUrl}/sendsms", [
                'headers' => [
                    'h_api_key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'mobile' => $recipient,
                    'response_type' => 'json',
                    'sender_name' => $this->senderId,
                    'service_id' => 0,
                    'message' => $message,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('SMS Sending Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send multiple SMS messages
     */
    public function sendBulkSms(array $messages)
    {
        try {
            $response = $this->client->post("{$this->apiUrl}/sendmultiple", [
                'headers' => [
                    'h_api_key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'serviceId' => "0",
                    'shortcode' => $this->senderId,
                    'messages' => $messages,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('Bulk SMS Sending Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check SMS delivery status
     */
    public function checkSmsStatus($mobileNumber)
    {
        try {
            $response = $this->client->get("{$this->apiUrl}/mobile", [
                'headers' => ['h_api_key' => $this->apiKey],
                'query' => ['mobile' => $mobileNumber, 'return' => 'json'],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('SMS Status Check Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check SMS balance
     */
    public function getBalance()
    {
        try {
            $response = $this->client->get("{$this->apiUrl}/getbalance", [
                'headers' => [
                    'h_api_key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => ['response_type' => 'json'],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('Balance Check Error: ' . $e->getMessage());
            return false;
        }
    }
}
