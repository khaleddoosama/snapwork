<?php

namespace App\Http\Requests\Api;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class InvitationRequest extends FormRequest
{

    public function authorize(): bool
    {
        // Retrieve the Job from the database using the job_id provided in the request
        $job = Job::find($this->job_id);

        // Check if the job exists and if the user is the client associated with this job
        return $job && $this->user()->id == $job->client_id;
    }

    public function rules(): array
    {
        return [
            // job_id, freelancer_id must be unique
            'job_id' => 'required|exists:jobs,id|unique:invitations,job_id,NULL,id,freelancer_id,' . request()->freelancer_id,
            'freelancer_id' => 'required|exists:users,id,role,freelancer',
        ];
    }
}
