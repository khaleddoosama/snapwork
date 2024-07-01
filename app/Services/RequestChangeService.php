<?php

namespace App\Services;

use App\Models\RequestChange;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class RequestChangeService
{
    // get all request_changes
    public function getAllRequestChanges()
    {
        // clear cache
        Cache::forget('request_changes');

        $request_changes = Cache::remember('request_changes_page_' . request('page', 1), 60, function () {
            return RequestChange::select('id', 'application_id', 'job_id', 'type', 'new_bid', 'new_duration', 'status')
                ->with(['application', 'job'])->paginate(1000);
        });
        return $request_changes;
    }

    // get request_change by id
    public function getRequestChangeById($id)
    {
        $request_change = RequestChange::where('id', $id)->with(['application', 'job'])->first();
        return $request_change;
    }
}
