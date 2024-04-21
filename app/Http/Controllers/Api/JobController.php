<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Services\Api\JobService;
use Illuminate\Http\Request;


class JobController extends Controller
{
    use ApiResponseTrait;

    private $jobService;
    //constructor
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    // store
    public function store(JobRequest $request)
    {
        try {
            $data = $request->validated();

            $job = $this->jobService->save($data);

            return $this->apiResponse(new JobResource($job), 'Job created successfully', 200);
        } catch (\Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 400);
        }
    }


    // show
    public function show(Job $job)
    {

        if ($job) {
            return $this->apiResponse(new JobResource($job), 'Job found', 200);
        } else {
            return $this->apiResponse(null, 'Job not found', 404);
        }
    }

    // update
    public function update(JobRequest $request, Job $job)
    {
        if ($job) {
            $data = $request->validated();

            $job = $this->jobService->update($data, $job);

            return $this->apiResponse(new JobResource($job), 'Job updated successfully', 200);
        } else {
            return $this->apiResponse(null, 'Job not found', 404);
        }
    }
}
