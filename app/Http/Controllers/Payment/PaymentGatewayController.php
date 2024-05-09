<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentGatewayController extends Controller
{
    private $client;
    private $apiKey;
    private $integrationId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
    }

    public function initiate(Request $request)
    {
        $amount = $request->amount;
        $token = $this->getAuthToken();
        $order = $this->createOrder($token, $amount);
        $paymentKey = $this->getPaymentKey($token, $order->id, $amount);

        return response()->json([
            'payment_url' => "https://accept.paymob.com/api/acceptance/iframes/$this->integrationId?payment_token=$paymentKey"
        ]);
    }

    private function getAuthToken()
    {
        $response = $this->client->post('https://accept.paymob.com/api/auth/tokens', [
            'json' => [
                'api_key' => $this->apiKey,
            ]
        ]);

        return json_decode($response->getBody()->getContents())->token;
    }

    private function createOrder($token, $amount)
    {
        $response = $this->client->post('https://accept.paymob.com/api/ecommerce/orders', [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'amount_cents' => $amount * 100, // Convert to cents
                'currency' => 'EGP',
                'items' => [],
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    private function getPaymentKey($token, $orderId, $amount)
    {
        $response = $this->client->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'amount_cents' => $amount * 100, // Convert to cents
                'expiration' => 3600, // 1 hour
                'order_id' => $orderId,
                'billing_data' => [
                    'apartment' => 'NA', 
                    'email' => 'customer-email@example.com',
                    'floor' => 'NA',
                    'first_name' => 'John',
                    'street' => 'NA',
                    'building' => 'NA',
                    'phone_number' => '+201111111111',
                    'shipping_method' => 'PKG',
                    'postal_code' => 'NA',
                    'city' => 'NA',
                    'country' => 'EG',
                    'last_name' => 'Doe',
                    'state' => 'NA'
                ],
                'currency' => 'EGP',
                'integration_id' => $this->integrationId,
            ]
        ]);

        return json_decode($response->getBody()->getContents())->token;
    }
}
