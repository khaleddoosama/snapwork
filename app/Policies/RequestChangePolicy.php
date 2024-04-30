<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Job;
use App\Models\RequestChange;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequestChangePolicy
{

    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RequestChange $requestChange)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Job $job)
    {
        // check if user is the same as the application
        $user_condition = $user->id === $job->hiredApplication->freelancer_id;
        // check if user created request change before and their status is pending
        // $user_request_change = $job->requestChanges()->where('status', 'pending')->where('freelancer_id', $user->id)->first();
        return $user->id === $job->hiredApplication->freelancer_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RequestChange $requestChange)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RequestChange $requestChange)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RequestChange $requestChange)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RequestChange $requestChange)
    {
        //
    }
}
