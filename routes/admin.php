<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
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

                Route::get('/users/{user}/edit', 'edit')->name('users.edit');
                Route::put('/users/{user}', 'update')->name('users.update');
                Route::put('/users/{user}/status', 'status')->name('users.status');

                Route::put('/{user}/password', 'updatePassword')->name('users.update.password');
            });

            // Category Controller
            Route::resource('categories', CategoryController::class)->except(['show'])->missing(function () {
                return redirect()->route('admin.categories.index');
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
