<?php

namespace App\Services\Api;

use App\Models\Application;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    // store application
    public function save(array $data)
    {
        $job = auth()->user()->applications()->create($data);
        return $job;
    }
}
