<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RequestChangeRequest;
use App\Models\Job;
use App\Services\Api\RequestChangeService;
use Illuminate\Http\Request;
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

    public function requestChange(RequestChangeRequest $request, Job $job)
    {
        $data = $request->validated();
        // check if user created request change before and their status is pending
        $user_request_change = $job->requestChanges()->where('freelancer_id', auth()->user()->id)->where('status', 'pending')->where('type', 'change')->first();
        if ($user_request_change) {
            throw ValidationException::withMessages(['request_change' => 'You have already created a request change.']);
        }
        $data['job_id'] = $job->id;
        $data['freelancer_id'] = auth()->user()->id;

        $request_change = $this->RequestChangeService->requestChange($data);

        return $this->apiResponse($request_change, 'Request change created successfully', 201);
    }


    public function requestSubmit(RequestChangeRequest $request, Job $job)
    {
        $request->validated();
        // check if user created request change before and their status is pending
        $user_request_change = $job->requestChanges()->where('freelancer_id', auth()->user()->id)->whereIn('status', ['pending', 'submit'])->where('type', 'submit')->first();
        if ($user_request_change) {
            throw ValidationException::withMessages(['request_submit' => 'You have already created a request submit.']);
        }

        $data['freelancer_id'] = auth()->user()->id;
        $data['job_id'] = $job->id;
        $data['type'] = 'submit';

        $request_change = $this->RequestChangeService->requestChange($data);
        return $this->apiResponse($request_change, 'Request change created successfully', 201);
    }

    public function requestCancel(RequestChangeRequest $request, Job $job)
    {
        $request->validated();
        // check if user created request change before and their status is pending
        $user_request_change = $job->requestChanges()->where('freelancer_id', auth()->user()->id)->whereIn('status', ['pending', 'submit'])->where('type', 'cancel')->first();
        if ($user_request_change) {
            throw ValidationException::withMessages(['request_cancel' => 'You have already created a request cancel.']);
        }
        $data['freelancer_id'] = auth()->user()->id;
        $data['job_id'] = $job->id;
        $data['type'] = 'cancel';

        $request_change = $this->RequestChangeService->requestChange($data);
        return $this->apiResponse($request_change, 'Request change created successfully', 201);
    }
}
