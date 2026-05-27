<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CandidateApiController;
use App\Http\Controllers\Api\InterviewerApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\PublicAnnouncementApiController;
use App\Http\Controllers\Api\LandingApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API endpoints (No login required)
Route::get('/landing', [LandingApiController::class, 'index']);
Route::get('/departments', [CandidateApiController::class, 'getDepartments']);
Route::post('/register', [CandidateApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// Private API endpoints (Requires Flutter to pass the Sanctum Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    /**
     * Get Candidate Profile, Schedule & Results
     *
     * Returns the authenticated user's profile info, including their candidate details (choices),
     * booked interview schedule slot, and final announcement outcome & DSS score breakdown if published.
     *
     * @group Candidate
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "user": {"id": 1, "name": "Ahmad Rizki", "email": "candidate@himatik.ac.id", "role": "candidate"},
     *   "candidate": {"id": 1, "nim": "2211501234", "first_choice": {"name": "Biro Humas"}, "second_choice": {"name": "Biro Akademik"}, "status": "evaluated"},
     *   "schedule": {"id": 3, "session_name": "Sesi Pagi A", "scheduled_at": "2025-08-15T09:00:00Z", "location": "Ruang Rapat HIMATIK"},
     *   "announcement": {"id": 1, "candidate_id": 1, "status": "accepted", "assigned_department_id": 1, "is_published": true},
     *   "dss_results": {"total_score": 4.5, "ncf": 4.67, "nsf": 4.25, "breakdown": []}
     * }
     */
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        $candidate = $user->candidate;
        
        $announcement = null;
        $dssResults = null;
        $schedule = null;

        if ($candidate) {
            $candidate->load(['firstChoice', 'secondChoice']);
            $schedule = \App\Models\InterviewSchedule::where('candidate_id', $candidate->id)->first();
            $announcement = \App\Models\Announcement::where('candidate_id', $candidate->id)->first();

            if ($announcement && $announcement->is_published && in_array($candidate->status, ['evaluated', 'completed'])) {
                $dss = app(\App\Services\ProfileMatchingService::class);
                $targetDept = $announcement->assigned_department_id ?: $candidate->first_choice_id;
                $deptModel = \App\Models\Departmentsbiro::find($targetDept);
                if ($deptModel) {
                    $dssResults = $dss->calculateScore($candidate, $deptModel);
                }
            }
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'candidate' => $candidate,
            'schedule' => $schedule,
            'announcement' => $announcement,
            'dss_results' => $dssResults
        ]);
    });

    Route::post('/logout', [AuthApiController::class, 'logout']);

    // ── CANDIDATE ───────────────────────────────────────────────────
    Route::middleware('role:candidate')->group(function () {
        Route::get('/schedules', [CandidateApiController::class, 'getAvailableSchedules']);
        Route::post('/schedules/book', [CandidateApiController::class, 'bookSchedule']);
    });

    // ── INTERVIEWER ──────────────────────────────────────────────────
    Route::middleware('role:interviewer')->group(function () {
        Route::get('/interviewer/schedules', [InterviewerApiController::class, 'getSchedules']);
        Route::get('/interviewer/grade/{candidate}/{department}', [InterviewerApiController::class, 'getGradingDetails']);
        Route::post('/interviewer/grade/{candidate}/{department}', [InterviewerApiController::class, 'submitScores']);
    });
    
    // Joint endpoint for Interviewer/Admin to decide on a candidate
    Route::post('/interviewer/decide/{candidate}', [AdminApiController::class, 'decideCandidate']);

    // ── ADMIN ────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [AdminApiController::class, 'getStats']);
        Route::get('/admin/rankings/{department}', [AdminApiController::class, 'getRankings']);
        Route::post('/admin/decide/{candidate}', [AdminApiController::class, 'decideCandidate']);
        Route::post('/admin/publish', [AdminApiController::class, 'publishAnnouncements']);

        // Admin: Departments CRUD
        Route::post('/admin/departments', [AdminApiController::class, 'storeDepartment']);
        Route::put('/admin/departments/{department}', [AdminApiController::class, 'updateDepartment']);
        Route::delete('/admin/departments/{department}', [AdminApiController::class, 'destroyDepartment']);

        // Admin: Interview Schedules CRUD
        Route::get('/admin/schedules', [AdminApiController::class, 'getAdminSchedules']);
        Route::post('/admin/schedules', [AdminApiController::class, 'storeSchedule']);
        Route::put('/admin/schedules/{schedule}', [AdminApiController::class, 'updateSchedule']);
        Route::delete('/admin/schedules/{schedule}', [AdminApiController::class, 'destroySchedule']);

        // Admin: Evaluation Criteria CRUD
        Route::get('/admin/criteria/{department}', [AdminApiController::class, 'getCriteria']);
        Route::post('/admin/criteria/{department}', [AdminApiController::class, 'storeCriterion']);
        Route::put('/admin/criteria/{criterion}', [AdminApiController::class, 'updateCriterion']);
        Route::delete('/admin/criteria/{criterion}', [AdminApiController::class, 'destroyCriterion']);

        // Admin: Interviewers CRUD
        Route::get('/admin/interviewers', [AdminApiController::class, 'getInterviewers']);
        Route::post('/admin/interviewers', [AdminApiController::class, 'storeInterviewer']);
        Route::put('/admin/interviewers/{user}', [AdminApiController::class, 'updateInterviewer']);
        Route::delete('/admin/interviewers/{user}', [AdminApiController::class, 'destroyInterviewer']);
    });
});

Route::get('/announcements', [PublicAnnouncementApiController::class, 'getAcceptedList']);