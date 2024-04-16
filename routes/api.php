<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SectionController;
use Illuminate\Http\Request;
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


Route::middleware('jwt.verify')->group(function () {
});
