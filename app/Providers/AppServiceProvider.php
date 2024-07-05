<?php

namespace App\Providers;

use App\Services\AdminNotificationService;
use App\Services\Api\ApplicationService;
use App\Services\Api\BookmarkService;
use App\Services\Api\InvitationService;
use App\Services\Api\JobService;
use App\Services\Api\MessageService;
use App\Services\Api\RequestChangeService;
use App\Services\ApplicationService as ServicesApplicationService;
use App\Services\InvitationService as ServicesInvitationService;
use App\Services\JobService as ServicesJobService;
use App\Services\MessageService as ServicesMessageService;
use App\Services\RateService;
use App\Services\RequestChangeService as ServicesRequestChangeService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // api services
        $this->app->singleton(ApplicationService::class, function ($app) {
            return new ApplicationService();
        });
        $this->app->singleton(BookmarkService::class, function ($app) {
            return new BookmarkService();
        });
        $this->app->singleton(InvitationService::class, function ($app) {
            return new InvitationService();
        });
        $this->app->singleton(JobService::class, function ($app) {
            return new JobService();
        });
        $this->app->singleton(MessageService::class, function ($app) {
            return new MessageService();
        });
        $this->app->singleton(RequestChangeService::class, function ($app) {
            return new RequestChangeService();
        });

        // other services
        $this->app->singleton(AdminNotificationService::class, function ($app) {
            return new AdminNotificationService();
        });
        $this->app->singleton(ServicesApplicationService::class, function ($app) {
            return new ServicesApplicationService();
        });
        $this->app->singleton(ServicesInvitationService::class, function ($app) {
            return new ServicesInvitationService();
        });
        $this->app->singleton(ServicesJobService::class, function ($app) {
            return new ServicesJobService();
        });
        $this->app->singleton(ServicesMessageService::class, function ($app) {
            return new ServicesMessageService();
        });
        $this->app->singleton(RateService::class, function ($app) {
            return new RateService();
        });
        $this->app->singleton(ServicesRequestChangeService::class, function ($app) {
            return new RequestChangeService();
        });
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
