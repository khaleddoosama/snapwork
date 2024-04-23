<?php

namespace App\Services\Api;

use App\Models\Job;
use Illuminate\Validation\ValidationException;

class JobService
{
    // get all jobs
    public function getAll($specialization_id)
    {
        if ($specialization_id) {
            $jobs = Job::with('client', 'specialization')->where('specialization_id', $specialization_id)->paginate(10);
        } else {
            $jobs = Job::with('client', 'specialization')->paginate(10);
        }
        return $jobs;
    }

    // store job
    public function save(array $data)
    {
        $job = auth()->user()->jobs()->create($data);
        return $job;
    }

    // update job
    public function update(array $data, Job $job)
    {
        $job->update($data);
        return $job;
    }
}
