<?php

namespace App\Services\Api;

use App\Models\Application;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    // get applications for freelancer
    public function getForFreelancer()
    {
        $applications = auth()->user()->applications;
        return $applications;
    }

    // store application
    public function save(array $data)
    {
        $job = auth()->user()->applications()->create($data);
        return $job;
    }

    // hire
    public function hire(Application $application)
    {
        $application->update(['status' => 'hired']);
        $application->job->update(['status' => 'hired']);
        return $application;
    }

    public function unhire(Application $application)
    {
        $application->update(['status' => null]);
        $application->job->update(['status' => null]);
        return $application;
    }
}
