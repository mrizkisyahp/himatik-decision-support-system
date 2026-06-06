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

        Route::get('/candidate/dashboard', [CandidateWebController::class, 'showDashboard'])->name('candidate.dashboard');
        Route::get('/schedule', [CandidateWebController::class, 'showScheduleForm'])->name('candidate.schedule.view');
        Route::post('/schedule/book', [CandidateWebController::class, 'bookSchedule'])->name('candidate.schedule.book');
    });

    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/schedule', [InterviewerWebController::class, 'index'])->name('interviewer.schedule');
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'showGradingForm'])->name('interviewer.grade.view');
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'submitScores'])->name('interviewer.grade.post');
        Route::post('/interviewer/decide/{candidate}', [InterviewerWebController::class, 'decideCandidate'])->name('interviewer.decide');

        // Interviewer Criteria CRUD
        Route::get('/interviewer/criteria', [InterviewerWebController::class, 'criteria'])->name('interviewer.criteria');
        Route::post('/interviewer/criteria', [InterviewerWebController::class, 'storeCriterion'])->name('interviewer.criteria.post');
        Route::put('/interviewer/criteria/{criterion}', [InterviewerWebController::class, 'updateCriterion'])->name('interviewer.criteria.update');
        Route::delete('/interviewer/criteria/{criterion}', [InterviewerWebController::class, 'destroyCriterion'])->name('interviewer.criteria.destroy');
        Route::post('/interviewer/criteria/reset', [InterviewerWebController::class, 'resetCriteria'])->name('interviewer.criteria.reset');
        Route::put('/interviewer/criteria/weights', [InterviewerWebController::class, 'updateWeights'])->name('interviewer.criteria.weights');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/pendaftaran', [AdminWebController::class, 'registrations'])->name('admin.registrations');
        Route::get('/admin/open-recruitment', [AdminWebController::class, 'openRecruitment'])->name('admin.open-recruitment');
        Route::post('/admin/open-recruitment', [AdminWebController::class, 'storeOpenRecruitment'])->name('admin.open-recruitment.store');
        Route::put('/admin/open-recruitment/quotas', [AdminWebController::class, 'updateOpenRecruitmentQuotas'])->name('admin.open-recruitment.quotas.update');
        Route::patch('/admin/open-recruitment/{openRecruitment}/status', [AdminWebController::class, 'updateOpenRecruitmentStatus'])->name('admin.open-recruitment.status');
        Route::put('/admin/open-recruitment/{openRecruitment}', [AdminWebController::class, 'updateOpenRecruitment'])->name('admin.open-recruitment.update');
        Route::post('/admin/open-recruitment/{openRecruitment}/extend', [AdminWebController::class, 'extendOpenRecruitment'])->name('admin.open-recruitment.extend');
        Route::get('/admin/pengumuman', [AdminWebController::class, 'announcements'])->name('admin.announcements');
        Route::get('/admin/profile-matching', [AdminWebController::class, 'profileMatching'])->name('admin.profile-matching');
        Route::post('/admin/profile-matching/scores/{candidate}/{department}', [AdminWebController::class, 'profileMatchingSaveScores'])->name('admin.profile-matching.save');
        Route::delete('/admin/profile-matching/scores/{candidate}/{department}', [AdminWebController::class, 'profileMatchingResetScores'])->name('admin.profile-matching.reset');
        Route::get('/admin/default-criteria', [AdminWebController::class, 'defaultCriteria'])->name('admin.default-criteria');
        Route::post('/admin/default-criteria', [AdminWebController::class, 'storeDefaultCriterion'])->name('admin.default-criteria.post');
        Route::put('/admin/default-criteria/{criterion}', [AdminWebController::class, 'updateDefaultCriterion'])->name('admin.default-criteria.update');
        Route::delete('/admin/default-criteria/{criterion}', [AdminWebController::class, 'destroyDefaultCriterion'])->name('admin.default-criteria.destroy');
        Route::get('/admin/departemen-biro', [AdminWebController::class, 'departments'])->name('admin.departments');
        
        // Schedules Spreadsheet Matrix
        Route::get('/admin/schedules', [AdminWebController::class, 'listSchedules'])->name('admin.schedules');
        Route::post('/admin/schedules/generate', [AdminWebController::class, 'generateSchedules'])->name('admin.schedules.generate');
        Route::patch('/admin/schedules/{schedule}/toggle-block', [AdminWebController::class, 'toggleScheduleBlock'])->name('admin.schedules.toggle-block');
        Route::delete('/admin/schedules', [AdminWebController::class, 'clearSchedules'])->name('admin.schedules.clear');

        Route::get('/admin/accounts', [\App\Http\Controllers\Web\AdminAccountController::class, 'index'])->name('admin.accounts');
        Route::post('/admin/accounts', [\App\Http\Controllers\Web\AdminAccountController::class, 'store'])->name('admin.accounts.store');
        Route::put('/admin/accounts/{account}', [\App\Http\Controllers\Web\AdminAccountController::class, 'update'])->name('admin.accounts.update');
        Route::delete('/admin/accounts/{account}', [\App\Http\Controllers\Web\AdminAccountController::class, 'destroy'])->name('admin.accounts.destroy');
        Route::get('/admin/rankings/{department}', [AdminWebController::class, 'showRankings'])->name('admin.rankings');

        Route::post('/admin/departments', [AdminWebController::class, 'storeDepartment'])->name('admin.departments.post');
        Route::get('/admin/departments/{department}', [AdminWebController::class, 'manageDepartment'])->name('admin.departments.manage');
        Route::put('/admin/departments/{department}', [AdminWebController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [AdminWebController::class, 'destroyDepartment'])->name('admin.departments.destroy');
        
        // Department Criteria CRUD
        Route::get('/admin/departments/{department}/criteria', [AdminWebController::class, 'listCriteria'])->name('admin.criteria');
        Route::post('/admin/departments/{department}/criteria', [AdminWebController::class, 'storeCriterion'])->name('admin.criteria.post');
        Route::put('/admin/departments/{department}/criteria/{criterion}', [AdminWebController::class, 'updateCriterion'])->name('admin.criteria.update');
        Route::delete('/admin/departments/{department}/criteria/{criterion}', [AdminWebController::class, 'destroyCriterion'])->name('admin.criteria.destroy');
        Route::post('/admin/departments/{department}/criteria/reset', [AdminWebController::class, 'resetCriteria'])->name('admin.criteria.reset');
        
        // Department Agendas & Work Programs
        Route::post('/admin/departments/{department}/agendas', [AdminWebController::class, 'storeAgenda'])->name('admin.departments.agendas.store');
        Route::put('/admin/departments/{department}/agendas/{agenda}', [AdminWebController::class, 'updateAgenda'])->name('admin.departments.agendas.update');
        Route::delete('/admin/departments/{department}/agendas/{agenda}', [AdminWebController::class, 'destroyAgenda'])->name('admin.departments.agendas.destroy');
        
        Route::post('/admin/departments/{department}/work-programs', [AdminWebController::class, 'storeWorkProgram'])->name('admin.departments.work-programs.store');
        Route::put('/admin/departments/{department}/work-programs/{program}', [AdminWebController::class, 'updateWorkProgram'])->name('admin.departments.work-programs.update');
        Route::delete('/admin/departments/{department}/work-programs/{program}', [AdminWebController::class, 'destroyWorkProgram'])->name('admin.departments.work-programs.destroy');

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
