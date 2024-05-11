<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Escrow;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    private $client;
    private $apiKey;
    private $integrationId;
    private $iFrameNumber;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]); // Change to true in production
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
        $this->iFrameNumber = env('PAYMOB_IFRAME_NUMBER');
    }

    public function initiate(Request $request)
    {
        $jobId = $request->job_id;
        $userId = $request->user()->id;
        $amount = $request->amount;

        try {
            $token = $this->getAuthToken();
            $order = $this->createOrder($token, $amount, $jobId);
            $paymentKey = $this->getPaymentKey($token, $order->id, $amount, $userId);

            $transaction = Transaction::create([
                'user_id' => $userId,
                'amount' => $amount,
                'status' => 'pending',
                'paymob_order_id' => $order->id,
                'type' => 'deposit'
            ]);

            Escrow::create([
                'job_id' => $jobId,
                'amount' => $amount,
                'status' => 'pending',
                'transaction_id' => $transaction->id
            ]);

            return response()->json([
                'payment_url' => "https://accept.paymob.com/api/acceptance/iframes/{$this->iFrameNumber}?payment_token={$paymentKey}"
            ]);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    private function getAuthToken()
    {
        $response = $this->client->post('https://accept.paymob.com/api/auth/tokens', [
            'json' => ['api_key' => $this->apiKey],
        ]);
        $body = json_decode($response->getBody()->getContents());

        if (isset($body->token)) {
            return $body->token;
        } else {
            throw new \Exception('Failed to retrieve authentication token from Paymob');
        }
    }
    
    private function createOrder($token, $amount, $jobId)
    {
        $response = $this->client->post('https://accept.paymob.com/api/ecommerce/orders', [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'amount_cents' => $amount * 100,  // Convert to cents
                'currency' => 'EGP',
                'items' => [],
                'merchant_order_id' => (string) $jobId  // Linking job ID to the order for tracking
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    private function getPaymentKey($token, $orderId, $amount, $userId)
    {
        // Retrieve user data from the database
        $user = User::findOrFail($userId);
        
        // Split the name on whitespace
        $names = explode(' ', $user->name);
        $firstName = $names[0];
        $lastName = isset($names[1]) ? $names[1] : '';  // Default to empty if no last name
    
        $response = $this->client->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'amount_cents' => $amount * 100,  // Convert to cents
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $user->email,  // Use user's email
                    'floor' => 'NA',
                    'first_name' => $firstName,  // Extracted first name
                    'street' => 'NA',
                    'building' => 'NA',
                    'phone_number' => '+201111111111',  // Use user's phone number, or default
                    'shipping_method' => 'PKG',
                    'postal_code' => 'NA',
                    'city' => 'NA',
                    'country' => 'EG',
                    'last_name' => $lastName,  // Extracted last name
                    'state' => 'NA'
                ],
                'currency' => 'EGP',
                'integration_id' => $this->integrationId,
            ]
        ]);
    
        return json_decode($response->getBody()->getContents())->token;
    }
    
}