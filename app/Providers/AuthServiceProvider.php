<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Job;
use App\Models\Rate;
use App\Models\RequestChange;
use App\Policies\JobPolicy;
use App\Policies\RatePolicy;
use App\Policies\RequestChangePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Job::class => JobPolicy::class,
        RequestChange::class => RequestChangePolicy::class,
        Rate::class => RatePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

    }
}
