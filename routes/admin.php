<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\RequestChangeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



//----------------------------- Admin Routes -----------------------------//



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::prefix('admin')->middleware(['auth', 'role:admin', 'web'])->as('admin.')->group(function () {
            // admin controller (resource)
            Route::resource('all_admin', AdminController::class)->except(['show']);
            // Admin Controller
            Route::controller(AdminController::class)->group(function () {
                Route::get('/', 'home')->name('home');
                Route::get('/profile', 'profile')->name('profile');
                Route::put('/profile', 'updateProfile')->name('profile.update');
                Route::put('/change-password', 'changePassword')->name('change.password');
            });

            // User Controller
            Route::controller(UserController::class)->group(function () {
                Route::get('/clients', 'clients')->name('user.clients');
                Route::get('/freelancers', 'freelancers')->name('user.freelancers');

                Route::get('/users/{user}', 'show')->name('users.show');
                Route::get('/users/{user}/edit', 'edit')->name('users.edit');
                Route::put('/users/{user}', 'update')->name('users.update');
                Route::put('/users/{user}/status', 'status')->name('users.status');

                Route::put('/{user}/password', 'updatePassword')->name('users.update.password');
            });

            // Category Controller
            Route::resource('specialization', SpecializationController::class)->except(['show'])->missing(function () {
                return redirect()->route('admin.specialization.index');
            });

            // Job Controller
            Route::controller(JobController::class)->group(function () {
                Route::get('/jobs', 'index')->name('jobs.index');
                Route::get('/jobs/{job}', 'show')->name('jobs.show');
                Route::put('/jobs/{job}/status', 'status')->name('jobs.status');
            });

            // Application Controller
            Route::controller(ApplicationController::class)->group(function () {
                Route::get('/applications', 'index')->name('applications.index');
                Route::get('/applications/{application}', 'show')->name('applications.show');
                // Route::put('/applications/{application}/status', 'status')->name('applications.status');
            });

            // Request Change Controller
            Route::controller(RequestChangeController::class)->group(function () {
                Route::get('/request-change', 'index')->name('request_changes.index');
                Route::get('/request-change/{requestChange}', 'show')->name('request_changes.show');
                // Route::put('/request-change/{requestChange}/status', 'status')->name('request_changes.status');
            });

            // Rate Controller
            Route::controller(RateController::class)->group(function () {
                Route::get('/rates', 'index')->name('rates.index');
                Route::get('/rates/{rate}', 'show')->name('rates.show');
            });

            // Invitation Controller
            Route::controller(InvitationController::class)->group(function () {
                Route::get('/invitations', 'index')->name('invitations.index');
            });

            // Message Controller
            Route::controller(MessageController::class)->group(function () {
                Route::get('/messages', 'index')->name('messages.index');
                Route::get('/messages/{message}', 'show')->name('messages.show');
            });

            // Permission controller (resource)
            // Route::resource('permission', RolePermissionController::class)->except(['show']);

            // Role controller (resource)
            Route::resource('role', RoleController::class)->except(['show']);

            // role permission controller (resource) with prefix role-permission and as role_permission.
            // Route::resource('role_permission', RolePermissionController::class)->except(['show', 'destroy']);

            // support
            Route::get('/support', [SupportController::class, 'index'])->name('support.index');
        });
    }
);
