<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Services\Api\ApplicationService;

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
}
