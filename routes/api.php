<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\EmploymentController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RateController;
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
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);

    Route::put('/change-password', [ProfileController::class, 'changePassword']);
    Route::put('/update-skills', [ProfileController::class, 'updateSkills']);
    Route::put('/update-picture', [ProfileController::class, 'updatePicture']);

    // Language routes
    Route::post('languages', [LanguageController::class, 'addLanguage']);
    Route::put('languages/{language}', [LanguageController::class, 'updateLanguage']);
    Route::delete('languages/{language}', [LanguageController::class, 'deleteLanguage']);

    // Education routes
    Route::post('educations', [EducationController::class, 'addEducation']);
    Route::put('educations/{education}', [EducationController::class, 'updateEducation']);
    Route::delete('educations/{education}', [EducationController::class, 'deleteEducation']);

    // Employment routes
    Route::post('employments', [EmploymentController::class, 'addEmployment']);
    Route::put('employments/{employment}', [EmploymentController::class, 'updateEmployment']);
    Route::delete('employments/{employment}', [EmploymentController::class, 'deleteEmployment']);

    // Certification routes
    Route::post('certifications', [CertificationController::class, 'addCertification']);
    Route::put('certifications/{certification}', [CertificationController::class, 'updateCertification']);
    Route::delete('certifications/{certification}', [CertificationController::class, 'deleteCertification']);
});


Route::get('/freelancers/{specialization_id?}', [ProfileController::class, 'getFreelancers']);
Route::get('/specializations', [ProfileController::class, 'getSpecializations']);
Route::get('/jobs/specialization/{specialization_id?}', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);


Route::middleware('jwt.verify')->group(function () {

    // jobs
    Route::get('/jobs', [JobController::class, 'getForClient']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{job}', [JobController::class, 'update']);

    // invitations
    Route::post('/invitations', [InvitationController::class, 'store']);

    // Applications
    Route::get('/applications', [ApplicationController::class, 'getForFreelancer']);
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

    // rate
    Route::get('/rate', [RateController::class, 'index']);
    Route::post('/rate', [RateController::class, 'store']);
    Route::get('/rate/{rate}', [RateController::class, 'show']);

    // Message
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/messages/{user_id}', [MessageController::class, 'show']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead']);

    // Notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read/{id}', [NotificationController::class, 'read']);
});


Route::prefix('payment')->middleware(['jwt.verify'])->group(function () {
    Route::post('initiate', [PaymentGatewayController::class, 'initiate'])->name('payment.initiate');
    Route::post('withdraw', [PaymentGatewayController::class, 'withdraw'])->name('payment.withdraw');
});
