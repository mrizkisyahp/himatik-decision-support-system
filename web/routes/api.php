<?php

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CandidateApiController;
use App\Http\Controllers\Api\InterviewerApiController;
use App\Http\Controllers\Api\LandingApiController;
use App\Http\Controllers\Api\PublicAnnouncementApiController;
use Illuminate\Support\Facades\Route;

Route::get('/landing', [LandingApiController::class, 'index']);
Route::get('/departments', [CandidateApiController::class, 'getDepartments']);
Route::post('/register', [CandidateApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/announcements', [PublicAnnouncementApiController::class, 'getAcceptedList']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [CandidateApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/email/verify-otp', [AuthApiController::class, 'verifyOtp']);
    Route::post('/email/resend-otp', [AuthApiController::class, 'resendOtp']);

    Route::middleware('role:candidate')->group(function () {
        Route::post('/candidate/profile', [CandidateApiController::class, 'storeProfile']);
        Route::get('/schedules', [CandidateApiController::class, 'getAvailableSchedules']);
        Route::post('/schedules/book', [CandidateApiController::class, 'bookSchedule']);
    });

    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/schedules', [InterviewerApiController::class, 'getSchedules']);
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerApiController::class, 'getGradingDetails']);
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerApiController::class, 'submitScores']);
    });

    Route::post('/interviewer/decide/{candidate}', [AdminApiController::class, 'decideCandidate']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [AdminApiController::class, 'getStats']);
        Route::get('/admin/rankings/{department}', [AdminApiController::class, 'getRankings']);
        Route::post('/admin/decide/{candidate}', [AdminApiController::class, 'decideCandidate']);
        Route::post('/admin/publish', [AdminApiController::class, 'publishAnnouncements']);

        Route::post('/admin/departments', [AdminApiController::class, 'storeDepartment']);
        Route::put('/admin/departments/{department}', [AdminApiController::class, 'updateDepartment']);
        Route::delete('/admin/departments/{department}', [AdminApiController::class, 'destroyDepartment']);

        Route::get('/admin/schedules', [AdminApiController::class, 'getAdminSchedules']);
        Route::post('/admin/schedules', [AdminApiController::class, 'storeSchedule']);
        Route::put('/admin/schedules/{schedule}', [AdminApiController::class, 'updateSchedule']);
        Route::delete('/admin/schedules/{schedule}', [AdminApiController::class, 'destroySchedule']);

        Route::get('/admin/criteria/{department}', [AdminApiController::class, 'getCriteria']);
        Route::post('/admin/criteria/{department}', [AdminApiController::class, 'storeCriterion']);
        Route::post('/admin/criteria/{department}/reset', [AdminApiController::class, 'resetCriteria']);
        Route::put('/admin/criteria/{criterion}', [AdminApiController::class, 'updateCriterion']);
        Route::delete('/admin/criteria/{criterion}', [AdminApiController::class, 'destroyCriterion']);

        Route::get('/admin/interviewers', [AdminApiController::class, 'getInterviewers']);
        Route::post('/admin/interviewers', [AdminApiController::class, 'storeInterviewer']);
        Route::put('/admin/interviewers/{user}', [AdminApiController::class, 'updateInterviewer']);
        Route::delete('/admin/interviewers/{user}', [AdminApiController::class, 'destroyInterviewer']);
    });
});
