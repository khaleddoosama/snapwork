<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RequestChangeRequest;
use App\Models\Application;
use App\Models\Job;
use App\Models\RequestChange;
use App\Services\Api\RequestChangeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        return $this->apiResponse($request_change, 'Request change created successfully', 201);
    }


    public function requestSubmit(RequestChangeRequest $request, Job $job, Application $application)
    {
        $this->validateRequestChange($request, $job, $application, 'submit');

        $data = $this->prepareRequestChangeData($application, $job, 'submit');
        $request_change = $this->RequestChangeService->requestChange($data);

        return $this->apiResponse($request_change, 'Request submit created successfully', 201);
    }

    public function requestCancel(RequestChangeRequest $request, Job $job, Application $application)
    {
        $this->validateRequestChange($request, $job, $application, 'cancel');

        $data = $this->prepareRequestChangeData($application, $job, 'cancel');
        $request_change = $this->RequestChangeService->requestChange($data);

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
        if ($job && $job->status === 'hired') {
            $job->status = 'Done';
            Application::where('job_id', $request_change->job_id)->update(['status' => 'Done']);
            $job->save();
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
