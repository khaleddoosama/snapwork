<?php

namespace App\Services\Api;

use App\Models\RequestChange;
use App\Models\Job;
use Illuminate\Validation\ValidationException;

class RequestChangeService
{
    // requestChange
    public function requestChange(array $data)
    {
        $requestChange = RequestChange::create($data);
        return $requestChange;
    }
}
