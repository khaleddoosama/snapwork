<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private $jobService;
    // constructor for jobService
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    // index
    public function index()
    {
        $jobs = $this->jobService->getAllJobs();
        $title = __('attributes.jobs');
        return view('admin.job.index', compact('title', 'jobs'));
    }

    // show
    public function show($id)
    {
        $job = $this->jobService->getJobById($id);
        $title = __('attributes.job');
        return view('admin.job.show', compact('title', 'job'));
    }
}


