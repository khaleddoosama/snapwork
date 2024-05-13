<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Escrow;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
    
        try {
            // Retrieve the job along with the hired application
            $job = Job::with('hiredApplication')->findOrFail($jobId);
    
            // Check if the current user is the client of the job
            if ($job->client_id != $userId) {
                return response()->json(['error' => 'You are not authorized to initiate payment for this job'], 403);
            }
    
            // Ensure there is a hired application and get the bid amount
            if (!$job->hiredApplication) {
                return response()->json(['error' => 'No hired freelancer for this job'], 404);
            }
            $amount = $job->hiredApplication->bid; // Assuming the 'bid' attribute holds the agreed payment amount
    
            // Generate payment token and process the payment
            $token = $this->getAuthToken();
            $order = $this->createOrder($token, $amount, $jobId);
            $paymentKey = $this->getPaymentKey($token, $order->id, $amount, $userId);
    
            // Create escrow record
            $escrow = Escrow::create([
                'job_id' => $jobId,
                'amount' => $amount,
                'status' => 'held',
            ]);
            
            // Record the transaction
            Transaction::create([
                'user_id' => $userId,
                'escrow_id' => $escrow->id,
                'amount' => $amount,
                'status' => 'completed',
                'paymob_order_id' => $order->id,
                'type' => 'deposit'
            ]);
    
            return response()->json([
                'payment_url' => "https://accept.paymob.com/api/acceptance/iframes/{$this->iFrameNumber}?payment_token={$paymentKey}"
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Job not found'], 404);
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
                    'phone_number' => $user->phone ?? 'NA',  // Use user's phone number, or default
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

    public function withdraw(Request $request)
    {
        $userId = $request->user()->id; // Assuming the user is authenticated and is a freelancer
        $jobId = $request->job_id;
    
        // Check if the job exists and is completed
        $job = Job::with('hiredApplication')->where('id', $jobId)->where('status', 'completed')->first();
        if (!$job) {
            return response()->json(['error' => 'Job not found or not completed'], 404);
        }
    
        // Check if the authenticated user is the hired freelancer for this job
        $hiredApplication = $job->hiredApplication;
        if (!$hiredApplication || $hiredApplication->freelancer_id != $userId) {
            return response()->json(['error' => 'Not authorized to withdraw for this job'], 403);
        }
    
        // Check if there's an escrow for this job
        $escrow = Escrow::where('job_id', $jobId)->where('status', 'held')->first();
        if (!$escrow) {
            return response()->json(['error' => 'No funds to withdraw or already withdrawn'], 404);
        }
    
        // Process the withdrawal
        try {
            // Begin transaction
            DB::beginTransaction();
    
            // Update escrow status to released
            $escrow->update(['status' => 'released']);
    
            // Record the transaction
            $transaction = Transaction::create([
                'user_id' => $userId,
                'escrow_id' => $escrow->id,
                'amount' => $escrow->amount,
                'status' => 'completed',
                'paymob_order_id' => 'NA',
                'type' => 'withdrawal'
            ]);
    
            // Update user balance
            $user = User::findOrFail($userId);
            $user->increment('balance', $escrow->amount);
    
            // Commit transaction
            DB::commit();
    
            return response()->json(['message' => 'Withdrawal successful', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            // Rollback transaction in case of errors
            DB::rollback();
            Log::error('Withdrawal failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    
    
}