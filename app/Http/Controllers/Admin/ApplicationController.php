<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    private $applicationService;
    // constructor for applicationService
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    // index
    public function index()
    {
        $applications = $this->applicationService->getAllApplications();
        $title = __('attributes.applications');
        return view('admin.application.index', compact('title', 'applications'));
    }

    // show
    public function show($id)
    {
        $application = $this->applicationService->getApplicationById($id);
        $title = __('attributes.application');
        return view('admin.application.show', compact('title', 'application'));
    }
}
