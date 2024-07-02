<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

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
    public function show(Request $request, $id)
    {
        $job = $this->jobService->getJobById($id);
        $title = __('attributes.job');
        return view('admin.job.show', compact('title', 'job'));
    }

    // status
    public function status(Request $request, $id)
    {
        $this->jobService->updateJobStatus($request, $id);
        Toastr::success(__('messages.job_status_updated'), __('status.success'));
        return redirect()->back();
    }
}
