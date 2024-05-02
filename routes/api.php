<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RequestChangeController;
use App\Http\Controllers\Payment\PaymentGatewayController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);

    Route::put('/change-password', [ProfileController::class, 'changePassword']);
    Route::put('/update-skills', [ProfileController::class, 'updateSkills']);
    Route::put('/update-languages', [ProfileController::class, 'updateLanguages']);
    Route::put('/update-educations', [ProfileController::class, 'updateEducations']);
    Route::put('/update-employments', [ProfileController::class, 'updateEmployments']);
    Route::put('/update-projects', [ProfileController::class, 'updateProjects']);
    Route::put('/update-certifications', [ProfileController::class, 'updateCertifications']);
});


Route::get('/freelancers/{specialization_id?}', [ProfileController::class, 'getFreelancers']);
Route::get('/specializations', [ProfileController::class, 'getSpecializations']);
Route::get('/jobs/specialization/{specialization_id?}', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);


Route::middleware('jwt.verify')->group(function () {

    // jobs
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{job}', [JobController::class, 'update']);

    // invitations
    Route::post('/invitations', [InvitationController::class, 'store']);

    // Applications
    Route::post('/applications', [ApplicationController::class, 'store']);

    // bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);

    // Hire
    Route::put('/hire/{job:slug}/{application:slug}', [ApplicationController::class, 'hire']);

    // request change
    Route::post('/request-change/{job:slug}/{application:slug}/', [RequestChangeController::class, 'requestChange']);
    Route::put('/request-submit/{job:slug}/{application:slug}/', [RequestChangeController::class, 'requestSubmit']);
    Route::put('/request-cancel/{job:slug}/{application:slug}/', [RequestChangeController::class, 'requestCancel']);

    // response change
    Route::put('/response-accept/{request_change}/', [RequestChangeController::class, 'responseAccept']);
    Route::put('/response-decline/{request_change}/', [RequestChangeController::class, 'responseDecline']);

    // Message
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead']);
});


Route::prefix('payment')->middleware(['auth', 'jwt.verify', 'throttle:60,1'])->group(function () {
    Route::post('initiate', [PaymentGatewayController::class, 'initiate'])->name('payment.initiate');
    Route::post('complete', [PaymentGatewayController::class, 'complete'])->name('payment.complete');
    Route::get('status/{transaction}', [PaymentGatewayController::class, 'status'])->name('payment.status');
});
