<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class JobPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function before(User $user)
    // {
    //     return $user->isAdmin();
    // }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Job $job)
    {
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Job $job)
    {
        return $user->id === $job->client_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Job $job)
    {
        return $user->id === $job->client_id;
    }

    public function hire(User $user, Job $job)
    {
        return $user->id === $job->client_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Job $job)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Job $job)
    {
    }
}
