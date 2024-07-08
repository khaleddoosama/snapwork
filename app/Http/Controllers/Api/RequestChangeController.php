<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RequestChangeRequest;
use App\Models\Application;
use App\Models\Job;
use App\Models\Escrow;
use App\Models\User;
use App\Models\Transaction;
use App\Models\RequestChange;
use App\Notifications\RequestChangeNotification;
use App\Services\Api\RequestChangeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class RequestChangeController extends Controller
{
    use ApiResponseTrait;
    //construct
    protected $RequestChangeService;

    public function __construct(RequestChangeService $RequestChangeService)
    {
        $this->RequestChangeService = $RequestChangeService;
    }

    // request change

    public function requestChange(RequestChangeRequest $request, Job $job, Application $application)
    {
        $data = $request->validated() + [
            'job_id' => $job->id,
            'application_id' => $application->id
        ];
        // check if user created request change before and their status is pending
        $user_request_change = $job->requestChanges()->where('application_id', $application->id)->where('status', 'pending')->where('type', 'change')->first();
        if ($user_request_change) {
            throw ValidationException::withMessages(['request_change' => 'You have already created a request change.']);
        }
        $data['job_id'] = $job->id;
        $data['application_id'] = $application->id;

        $request_change = $this->RequestChangeService->requestChange($data);

        // Notification
        $job->client->notify(new RequestChangeNotification(auth()->user(), $request_change, 'change'));

        return $this->apiResponse($request_change, 'Request change created successfully', 201);
    }


    public function requestSubmit(RequestChangeRequest $request, Job $job, Application $application)
    {
        $this->validateRequestChange($request, $job, $application, 'submit');

        $data = $this->prepareRequestChangeData($application, $job, 'submit');
        $request_change = $this->RequestChangeService->requestChange($data);

        // Notification
        $job->client->notify(new RequestChangeNotification(auth()->user(), $request_change, 'submit'));

        return $this->apiResponse($request_change, 'Request submit created successfully', 201);
    }

    public function requestCancel(RequestChangeRequest $request, Job $job, Application $application)
    {
        $this->validateRequestChange($request, $job, $application, 'cancel');

        $data = $this->prepareRequestChangeData($application, $job, 'cancel');
        $request_change = $this->RequestChangeService->requestChange($data);

        // Notification
        $job->client->notify(new RequestChangeNotification(auth()->user(), $request_change, 'cancel'));

        return $this->apiResponse($request_change, 'Request cancel created successfully', 201);
    }

    private function validateRequestChange($request, $job, $application, $type)
    {
        $request->validated();
        $existingRequest = $job->requestChanges()
            ->where('application_id', $application->id)
            ->whereIn('status', ['pending', $type])
            ->where('type', $type)
            ->first();

        if ($existingRequest) {
            throw ValidationException::withMessages(["request_$type" => "A request to $type has already been created and is pending."]);
        }
    }

    private function prepareRequestChangeData(Application $application, Job $job, string $type): array
    {
        return [
            'application_id' => $application->id,
            'job_id' => $job->id,
            'type' => $type
        ];
    }



    // response accepted

    public function responseAccept(RequestChange $request_change)
    {
        DB::beginTransaction();
        try {
            if ($request_change->status == 'accept') {
                throw ValidationException::withMessages(['request_change' => 'Request change already accepted.']);
            }
            $request_change->status = 'accept';

            $this->handleAcceptance($request_change);

            DB::commit();
            return $this->apiResponse($request_change, 'Request change accepted successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    private function handleAcceptance($request_change)
    {
        switch ($request_change->type) {
            case 'change':
                $this->handleChange($request_change);
                break;
            case 'submit':
                $this->handleSubmit($request_change);
                break;
            case 'cancel':
                $this->handleCancel($request_change);
                break;
            default:
                throw new \InvalidArgumentException("Invalid request type: {$request_change->type}");
        }
        $request_change->save();
    }

    private function handleChange($request_change)
    {
        $application = Application::find($request_change->application_id);
        if (!$application) {
            throw new \RuntimeException('Application not found.');
        }

        $application->bid = $request_change->new_bid;
        $application->duration = $request_change->new_duration;
        $application->save();
    }

    private function handleSubmit($request_change)
{
    $job = Job::find($request_change->job_id);
    $clientId = auth()->user()->id; // Fetching the authenticated client's ID

    if ($job && $job->status === 'hired') {
        // Check if the authenticated user is the client for this job
        if ($job->client_id != $clientId) {
            throw ValidationException::withMessages(['request_change' => 'Not authorized to update this job.']);
        }

        // Check if the application is the hired application for this job
        $hiredApplication = Application::where('job_id', $request_change->job_id)->where('status', 'hired')->first();
        if (!$hiredApplication) {
            throw ValidationException::withMessages(['request_change' => 'No hired application found for this job.']);
        }

        // Check if there's an escrow for this job
        $escrow = Escrow::where('job_id', $request_change->job_id)->where('status', 'held')->first();
        if (!$escrow) {
            throw ValidationException::withMessages(['request_change' => 'No funds to withdraw or already withdrawn.']);
        }

        try {
            // Begin transaction
            DB::beginTransaction();

            // Update job and application statuses
            $job->status = 'done';
            Application::where('job_id', $request_change->job_id)->update(['status' => 'done']);
            $job->save();

            // Update escrow status to released
            $escrow->update(['status' => 'released']);

            // Record the transaction
            $transaction = Transaction::create([
                'user_id' => $hiredApplication->freelancer_id, // Record transaction for the freelancer
                'escrow_id' => $escrow->id,
                'amount' => $escrow->amount,
                'status' => 'completed',
                'paymob_order_id' => 'NA',
                'type' => 'withdrawal'
            ]);

            // Update freelancer balance
            $freelancer = User::findOrFail($hiredApplication->freelancer_id);
            $freelancer->increment('balance', $escrow->amount);

            // Commit transaction
            DB::commit();

            return response()->json(['message' => 'Request change and withdrawal successful', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            // Rollback transaction in case of errors
            DB::rollback();
            Log::error('Request change and withdrawal failed: ' . $e->getMessage());
            throw ValidationException::withMessages(['request_change' => 'Request change and withdrawal failed: ' . $e->getMessage()]);
        }
    } else {
        throw ValidationException::withMessages(['request_change' => 'Request change cannot be submitted under current job status.']);
    }
}

    private function handleCancel($request_change)
    {
        $job = Job::find($request_change->job_id);
        if ($job && $job->status === 'hired') {
            $job->status = 'open';
            Application::where('job_id', $request_change->job_id)->update(['status' => 'cancelled']);
            $job->save();
        } else {
            throw ValidationException::withMessages(['request_change' => 'Request change cannot be cancelled under current job status.']);
        }
    }


    public function responseDecline(RequestChange $request_change)
    {
        if ($request_change->status !== null) {
            throw ValidationException::withMessages(['request_change' => 'Request change already responded.']);
        }
        $request_change->status = 'decline';
        $request_change->save();

        return $this->apiResponse($request_change, 'Request change declined successfully', 200);
    }
}
