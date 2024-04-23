<?php

namespace App\Services\Api;

use App\Models\Application;
use Illuminate\Validation\ValidationException;

class ApplicationService
{


    // store job
    public function save(array $data)
    {
        $job = auth()->user()->applications()->create($data);
        return $job;
    }

    // update job
    public function update(array $data, Application $job)
    {
        $job->update($data);
        return $job;
    }
}
