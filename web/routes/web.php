<?php

use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BladeDocsController;
use App\Http\Controllers\Web\CandidateWebController;
use App\Http\Controllers\Web\InterviewerWebController;
use App\Http\Controllers\Web\LandingWebController;
use App\Http\Controllers\Web\PublicAnnouncementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingWebController::class, 'index'])->name('landing');
Route::get('/announcements', [PublicAnnouncementController::class, 'showAcceptedList'])->name('public.announcements');
Route::get('/docs/blade', [BladeDocsController::class, 'index'])->name('docs.blade');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');

    Route::get('/register', [CandidateWebController::class, 'showUserRegisterForm'])->name('user.register.view');
    Route::post('/register', [CandidateWebController::class, 'registerUser'])->name('user.register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

    Route::middleware('role:candidate')->group(function () {
        Route::get('/verify-email', [CandidateWebController::class, 'showOtpForm'])->name('candidate.otp.view');
        Route::post('/verify-email', [CandidateWebController::class, 'verifyOtp'])->name('candidate.otp.verify');
        Route::post('/verify-email/resend', [CandidateWebController::class, 'resendOtp'])->name('candidate.otp.resend');

        Route::get('/register-candidate', [CandidateWebController::class, 'showCandidateRegisterForm'])->name('candidate.register.view');
        Route::post('/register-candidate', [CandidateWebController::class, 'registerCandidate'])->name('candidate.register.post');

        Route::get('/schedule', [CandidateWebController::class, 'showScheduleForm'])->name('candidate.schedule.view');
        Route::post('/schedule/book', [CandidateWebController::class, 'bookSchedule'])->name('candidate.schedule.book');
    });

    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/schedule', [InterviewerWebController::class, 'index'])->name('interviewer.schedule');
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'showGradingForm'])->name('interviewer.grade.view');
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'submitScores'])->name('interviewer.grade.post');
        Route::post('/interviewer/decide/{candidate}', [InterviewerWebController::class, 'decideCandidate'])->name('interviewer.decide');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/rankings/{department}', [AdminWebController::class, 'showRankings'])->name('admin.rankings');

        Route::post('/admin/departments', [AdminWebController::class, 'storeDepartment'])->name('admin.departments.post');
        Route::put('/admin/departments/{department}', [AdminWebController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [AdminWebController::class, 'destroyDepartment'])->name('admin.departments.destroy');

        Route::get('/admin/criteria/{department}', [AdminWebController::class, 'listCriteria'])->name('admin.criteria');
        Route::post('/admin/criteria/{department}', [AdminWebController::class, 'storeCriterion'])->name('admin.criteria.post');
        Route::post('/admin/criteria/{department}/reset', [AdminWebController::class, 'resetCriteria'])->name('admin.criteria.reset');
        Route::put('/admin/criteria/{department}/{criterion}', [AdminWebController::class, 'updateCriterion'])->name('admin.criteria.update');
        Route::delete('/admin/criteria/{department}/{criterion}', [AdminWebController::class, 'destroyCriterion'])->name('admin.criteria.destroy');

        Route::get('/admin/schedules', [AdminWebController::class, 'listSchedules'])->name('admin.schedules');
        Route::post('/admin/schedules', [AdminWebController::class, 'storeSchedule'])->name('admin.schedules.post');
        Route::put('/admin/schedules/{schedule}', [AdminWebController::class, 'updateSchedule'])->name('admin.schedules.update');
        Route::delete('/admin/schedules/{schedule}', [AdminWebController::class, 'destroySchedule'])->name('admin.schedules.destroy');

        Route::get('/admin/interviewers', [AdminWebController::class, 'listInterviewers'])->name('admin.interviewers');
        Route::post('/admin/interviewers', [AdminWebController::class, 'storeInterviewer'])->name('admin.interviewers.post');
        Route::put('/admin/interviewers/{user}', [AdminWebController::class, 'updateInterviewer'])->name('admin.interviewers.update');
        Route::delete('/admin/interviewers/{user}', [AdminWebController::class, 'destroyInterviewer'])->name('admin.interviewers.destroy');

        Route::post('/admin/decide/{candidate}', [AdminWebController::class, 'decideCandidate'])->name('admin.decide');
        Route::post('/admin/publish', [AdminWebController::class, 'publishAnnouncements'])->name('admin.publish');

        Route::get('/admin/testing', [AdminWebController::class, 'testing'])->name('admin.testing');
        Route::post('/admin/testing/scores/{candidate}/{department}', [AdminWebController::class, 'testingSaveScores'])->name('admin.testing.save');
        Route::delete('/admin/testing/scores/{candidate}/{department}', [AdminWebController::class, 'testingResetScores'])->name('admin.testing.reset');

        // Candidate CRUD for testing
        Route::post('/admin/testing/candidates', [AdminWebController::class, 'testingStoreCandidate'])->name('admin.testing.candidates.store');
        Route::put('/admin/testing/candidates/{candidate}', [AdminWebController::class, 'testingUpdateCandidate'])->name('admin.testing.candidates.update');
        Route::delete('/admin/testing/candidates/{candidate}', [AdminWebController::class, 'testingDestroyCandidate'])->name('admin.testing.candidates.destroy');
    });
});
