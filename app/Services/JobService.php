<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class JobService
{
    // get all jobs
    public function getAllJobs()
    {
        // clear cache
        Cache::forget('jobs');
        $jobs = Cache::remember('jobs_page_' . request('page', 1), 60, function () {
            return Job::select('id', 'title', 'client_id', 'specialization_id', 'type', 'location_type', 'status')
                ->with(['client', 'specialization'])->paginate(1000);
        });
        return $jobs;
    }

    // get job by id
    public function getJobById($id)
    {
        $job = Job::where('id', $id)->with(['client', 'specialization', 'applications', 'requestChanges', 'hiredApplication', 'rates'])->first();
        return $job;
    }
}
