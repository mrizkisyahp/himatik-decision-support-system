<?php

use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\CandidateWebController;
use App\Http\Controllers\Web\InterviewerWebController;
use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\PublicAnnouncementController;
use App\Http\Controllers\Web\LandingWebController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\BladeDocsController;

// ─────────────────────────────────────────────────────────────────
// PUBLIC — no auth required
// ─────────────────────────────────────────────────────────────────
Route::get('/', [LandingWebController::class, 'index'])->name('landing');
Route::get('/announcements', [PublicAnnouncementController::class, 'showAcceptedList'])->name('public.announcements');
Route::get('/docs/blade', [BladeDocsController::class, 'index'])->name('docs.blade');

// ─────────────────────────────────────────────────────────────────
// GUEST ONLY — redirect to home if already logged in
// ─────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');

    // Stage 1: Create a user account
    Route::get('/register', [CandidateWebController::class, 'showUserRegisterForm'])->name('user.register.view');
    Route::post('/register', [CandidateWebController::class, 'registerUser'])->name('user.register.post');
});

// ─────────────────────────────────────────────────────────────────
// AUTH — must be logged in
// ─────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

    // ── CANDIDATE ───────────────────────────────────────────────────
    Route::middleware('role:candidate')->group(function () {
        // Stage 2: Complete candidate profile
        Route::get('/register-candidate', [CandidateWebController::class, 'showCandidateRegisterForm'])->name('candidate.register.view');
        Route::post('/register-candidate', [CandidateWebController::class, 'registerCandidate'])->name('candidate.register.post');

        // Candidate: book interview schedule
        Route::get('/schedule', [CandidateWebController::class, 'showScheduleForm'])->name('candidate.schedule.view');
        Route::post('/schedule/book', [CandidateWebController::class, 'bookSchedule'])->name('candidate.schedule.book');
    });

    // ── INTERVIEWER ──────────────────────────────────────────────────
    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/schedule', [InterviewerWebController::class, 'index'])->name('interviewer.schedule');
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'showGradingForm'])->name('interviewer.grade.view');
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'submitScores'])->name('interviewer.grade.post');
        Route::post('/interviewer/decide/{candidate}', [InterviewerWebController::class, 'decideCandidate'])->name('interviewer.decide');
    });

    // ── ADMIN ────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/rankings/{department}', [AdminWebController::class, 'showRankings'])->name('admin.rankings');

        // Departments CRUD
        Route::post('/admin/departments', [AdminWebController::class, 'storeDepartment'])->name('admin.departments.post');
        Route::put('/admin/departments/{department}', [AdminWebController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [AdminWebController::class, 'destroyDepartment'])->name('admin.departments.destroy');

        // Evaluation Criteria CRUD
        Route::get('/admin/criteria/{department}', [AdminWebController::class, 'listCriteria'])->name('admin.criteria');
        Route::post('/admin/criteria/{department}', [AdminWebController::class, 'storeCriterion'])->name('admin.criteria.post');
        Route::put('/admin/criteria/{department}/{criterion}', [AdminWebController::class, 'updateCriterion'])->name('admin.criteria.update');
        Route::delete('/admin/criteria/{department}/{criterion}', [AdminWebController::class, 'destroyCriterion'])->name('admin.criteria.destroy');

        // Interview Schedules CRUD
        Route::get('/admin/schedules', [AdminWebController::class, 'listSchedules'])->name('admin.schedules');
        Route::post('/admin/schedules', [AdminWebController::class, 'storeSchedule'])->name('admin.schedules.post');
        Route::put('/admin/schedules/{schedule}', [AdminWebController::class, 'updateSchedule'])->name('admin.schedules.update');
        Route::delete('/admin/schedules/{schedule}', [AdminWebController::class, 'destroySchedule'])->name('admin.schedules.destroy');

        // Interviewers CRUD
        Route::get('/admin/interviewers', [AdminWebController::class, 'listInterviewers'])->name('admin.interviewers');
        Route::post('/admin/interviewers', [AdminWebController::class, 'storeInterviewer'])->name('admin.interviewers.post');
        Route::put('/admin/interviewers/{user}', [AdminWebController::class, 'updateInterviewer'])->name('admin.interviewers.update');
        Route::delete('/admin/interviewers/{user}', [AdminWebController::class, 'destroyInterviewer'])->name('admin.interviewers.destroy');

        // Decisions & Announcements
        Route::post('/admin/decide/{candidate}', [AdminWebController::class, 'decideCandidate'])->name('admin.decide');
        Route::post('/admin/publish', [AdminWebController::class, 'publishAnnouncements'])->name('admin.publish');
    });
});