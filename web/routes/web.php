<?php

use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BladeDocsController;
use App\Http\Controllers\Web\CandidateWebController;
use App\Http\Controllers\Web\GoogleAuthController;
use App\Http\Controllers\Web\InterviewerWebController;
use App\Http\Controllers\Web\LandingWebController;
use App\Http\Controllers\Web\PublicAnnouncementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingWebController::class, 'index'])->name('landing');
Route::get('/announcements', [PublicAnnouncementController::class, 'showAcceptedList'])->name('public.announcements');
Route::get('/docs/blade', [BladeDocsController::class, 'index'])->name('docs.blade');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');

    Route::get('/register', [CandidateWebController::class, 'showUserRegisterForm'])->name('user.register.view');
    Route::post('/register', [CandidateWebController::class, 'registerUser'])->name('user.register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');
    Route::get('/documents/download/{candidate}/{field}', [CandidateWebController::class, 'downloadDocument'])->name('documents.download');

    Route::get('/profile', [\App\Http\Controllers\Web\ProfileWebController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Web\ProfileWebController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Web\ProfileWebController::class, 'updatePassword'])->name('profile.password');

    Route::middleware('role:candidate')->group(function () {
        Route::get('/verify-email', [CandidateWebController::class, 'showOtpForm'])->name('candidate.otp.view');
        Route::post('/verify-email', [CandidateWebController::class, 'verifyOtp'])->name('candidate.otp.verify');
        Route::post('/verify-email/resend', [CandidateWebController::class, 'resendOtp'])->name('candidate.otp.resend');

        Route::get('/register-candidate', [CandidateWebController::class, 'showCandidateRegisterForm'])->name('candidate.register.view');
        Route::post('/register-candidate', [CandidateWebController::class, 'registerCandidate'])->name('candidate.register.post');

        Route::get('/candidate/dashboard', [CandidateWebController::class, 'showDashboard'])->name('candidate.dashboard');
        Route::get('/schedule', [CandidateWebController::class, 'showScheduleForm'])->name('candidate.schedule.view');
        Route::post('/schedule/book', [CandidateWebController::class, 'bookSchedule'])->name('candidate.schedule.book');
        Route::get('/candidate/interview-detail', [CandidateWebController::class, 'showInterviewDetail'])->name('candidate.interview.detail');
        Route::get('/candidate/registration-form', [CandidateWebController::class, 'showRegistrationForm'])->name('candidate.registration.form');
        Route::get('/candidate/registration-attachments', [CandidateWebController::class, 'showRegistrationAttachments'])->name('candidate.registration.attachments');
        Route::middleware('check.oprec.active')->group(function () {
            Route::get('/candidate/apply/{openRecruitment}', [CandidateWebController::class, 'showApplyStartPage'])->name('candidate.apply.start');
            Route::post('/candidate/apply', [CandidateWebController::class, 'applyOprec'])->name('candidate.apply.post');
            Route::get('/candidate/preferences', [CandidateWebController::class, 'showPreferencesForm'])->name('candidate.preferences.view');
            Route::post('/candidate/preferences', [CandidateWebController::class, 'savePreferences'])->name('candidate.preferences.post');
            
            Route::get('/candidate/experience', [CandidateWebController::class, 'showExperienceForm'])->name('candidate.experience.view');
            Route::post('/candidate/experience/education', [CandidateWebController::class, 'storeEducation'])->name('candidate.education.store');
            Route::post('/candidate/experience/education/{id}/delete', [CandidateWebController::class, 'destroyEducation'])->name('candidate.education.destroy');
            Route::post('/candidate/experience/organization', [CandidateWebController::class, 'storeOrganization'])->name('candidate.organization.store');
            Route::post('/candidate/experience/organization/{id}/delete', [CandidateWebController::class, 'destroyOrganization'])->name('candidate.organization.destroy');
            Route::post('/candidate/experience/committee', [CandidateWebController::class, 'storeCommittee'])->name('candidate.committee.store');
            Route::post('/candidate/experience/committee/{id}/delete', [CandidateWebController::class, 'destroyCommittee'])->name('candidate.committee.destroy');
            Route::post('/candidate/experience/next', [CandidateWebController::class, 'nextFromExperience'])->name('candidate.experience.next');

            Route::get('/candidate/skills-facilities', [CandidateWebController::class, 'showSkillsFacilitiesForm'])->name('candidate.skills.view');
            Route::post('/candidate/skills', [CandidateWebController::class, 'storeSkill'])->name('candidate.skill.store');
            Route::post('/candidate/skills/{id}/delete', [CandidateWebController::class, 'destroySkill'])->name('candidate.skill.destroy');
            Route::post('/candidate/facilities', [CandidateWebController::class, 'storeFacility'])->name('candidate.facility.store');
            Route::post('/candidate/facilities/{id}/delete', [CandidateWebController::class, 'destroyFacility'])->name('candidate.facility.destroy');
            Route::post('/candidate/skills-facilities/next', [CandidateWebController::class, 'nextFromSkillsFacilities'])->name('candidate.skills.next');

            Route::get('/candidate/documents', [CandidateWebController::class, 'showDocumentsForm'])->name('candidate.documents.view');
            Route::post('/candidate/documents', [CandidateWebController::class, 'saveDocuments'])->name('candidate.documents.post');

            Route::get('/candidate/signatures', [CandidateWebController::class, 'showSignaturesForm'])->name('candidate.signatures.view');
            Route::post('/candidate/signatures', [CandidateWebController::class, 'saveSignatures'])->name('candidate.signatures.post');
        });
    });

    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/dashboard', [InterviewerWebController::class, 'dashboard'])->name('interviewer.dashboard');
        Route::get('/interviewer/pendaftaran', [InterviewerWebController::class, 'registrations'])->name('interviewer.registrations');
        Route::get('/interviewer/schedules', [InterviewerWebController::class, 'schedules'])->name('interviewer.schedules');
        Route::patch('/interviewer/schedules/{schedule}/toggle-block', [InterviewerWebController::class, 'toggleScheduleBlock'])->name('interviewer.schedules.toggle-block');
        Route::get('/interviewer/profile-matching', [InterviewerWebController::class, 'profileMatching'])->name('interviewer.profile-matching');
        Route::get('/interviewer/profile-matching/{candidate}/calculation', [InterviewerWebController::class, 'profileMatchingCalculation'])->name('interviewer.profile-matching.calculation');
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'showGradingForm'])->name('interviewer.grade.view');
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerWebController::class, 'submitScores'])->name('interviewer.grade.post');
        Route::delete('/interviewer/profile-matching/scores/{candidate}/{department}', [InterviewerWebController::class, 'profileMatchingResetScores'])->name('interviewer.profile-matching.reset');
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
        Route::get('/admin/rankings', [AdminWebController::class, 'rankings'])->name('admin.rankings');
        Route::post('/admin/profile-matching/scores/{candidate}/{department}', [AdminWebController::class, 'profileMatchingSaveScores'])->name('admin.profile-matching.save');
        Route::delete('/admin/profile-matching/scores/{candidate}/{department}', [AdminWebController::class, 'profileMatchingResetScores'])->name('admin.profile-matching.reset');
        Route::get('/admin/profile-matching/{department}/{candidate}/calculation', [AdminWebController::class, 'profileMatchingCalculation'])->name('admin.profile-matching.calculation');
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

        Route::post('/admin/decide/{candidate}', [AdminWebController::class, 'decideCandidate'])->name('admin.decide');
        Route::post('/admin/publish', [AdminWebController::class, 'publishAnnouncements'])->name('admin.publish');
    });
});
