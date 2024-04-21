<?php

namespace App\Services\Api;

use App\Models\Job;
use Illuminate\Validation\ValidationException;

class JobService
{

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
