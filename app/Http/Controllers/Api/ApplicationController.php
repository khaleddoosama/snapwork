<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Job;
use App\Services\Api\ApplicationService;
use Illuminate\Support\Facades\App;

class ApplicationController extends Controller
{
    use ApiResponseTrait;
    //construct
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    // Get all applications for freelancer

    public function getForFreelancer()
    {
        $applications = $this->applicationService->getForFreelancer();
        return $this->apiResponse(ApplicationResource::collection($applications), 'Applications retrieved successfully', 200);
    }

    public function store(ApplicationRequest $request)
    {
        $data = $request->validated();
        $application = $this->applicationService->save($data);
        return $this->apiResponse(new ApplicationResource($application), 'Application sent successfully', 201);
    }

    // Hire
    public function hire(Job $job, Application $application)
    {
        // check if the authenticated user is the owner of the job
        // if (auth()->user()->id != $job->client_id) {
        //     return $this->apiResponse(null, 'Unauthorized', 401);
        // }

        $this->authorize('hire', $job);

        // check if the application belongs to the job
        if ($application->job_id != $job->id) {
            return $this->apiResponse(null, 'Not Responsible', 401);
        }

        // check if the job is not already hired
        if ($job->status === 'hired') {
            return $this->apiResponse(null, 'error: Job is hired before', 401);
        }

        // check if the application is not already hired
        if ($application->status === 'hired') {
            return $this->apiResponse(null, 'Application is hired', 401);
        }

        $application = $this->applicationService->hire($application);
        return $this->apiResponse(new ApplicationResource($application), 'Hired', 200);
    }
}
