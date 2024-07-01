<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class ApplicationService
{
    // get all applications
    public function getAllApplications()
    {
        // clear cache
        Cache::forget('applications');
        $applications = Cache::remember('applications_page_' . request('page', 1), 60, function () {
            return Application::select('id', 'job_id', 'freelancer_id', 'cover_letter', 'bid', 'duration', 'status')
                ->with(['job', 'freelancer'])->paginate(1000);
        });
        return $applications;
    }

    // get application by id
    public function getApplicationById($id)
    {
        $application = Application::where('id', $id)->with(['job', 'freelancer'])->first();
        return $application;
    }
}
