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

    public function store(ApplicationRequest $request)
    {
        $data = $request->validated();
        $application = $this->applicationService->save($data);
        return $this->apiResponse(new ApplicationResource($application), 'Application sent successfully', 201);
    }

    // Hire
    public function hire(Job $job, Application $application)
    {
        return [$job, $application];
        // $application = $this->applicationService->hire($job, $application);
        // return $this->apiResponse(new ApplicationResource($application), 'Freelancer hired successfully');
    }
}
