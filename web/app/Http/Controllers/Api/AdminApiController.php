<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\DefaultEvaluationCriteria;
use App\Models\EvaluationCriteria;
use App\Models\InterviewSchedule;
use App\Models\Announcement;
use App\Models\User;
use App\Services\ProfileMatchingService;
use App\Support\SpkCriteriaDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminApiController extends Controller
{
    protected $dss;

    public function __construct(ProfileMatchingService $dss)
    {
        $this->dss = $dss;
    }

    // ─────────────────────────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────────────────────────

    private function requireAdmin(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        return null;
    }

    // ─────────────────────────────────────────────────────────────────
    // STATS & RANKINGS (existing)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Get Dashboard Stats
     *
     * Returns an overview of recruitment statistics including candidate counts per status,
     * and department-level candidate application counts. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "stats": {"total_candidates": 42, "total_registered": 15, "total_scheduled": 20, "total_evaluated": 7},
     *     "departments": [{"id": 1, "name": "Biro Humas", "first_choice_candidates_count": 10}]
     *   }
     * }
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function getStats(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $stats = [
            'total_candidates' => Candidate::count(),
            'total_registered' => Candidate::where('status', 'registered')->count(),
            'total_scheduled' => Candidate::where('status', 'scheduled')->count(),
            'total_evaluated' => Candidate::where('status', 'evaluated')->count(),
        ];
        $departments = Departmentsbiro::withCount(['firstChoiceCandidates', 'secondChoiceCandidates'])->get();
        return response()->json(['success' => true, 'data' => ['stats' => $stats, 'departments' => $departments]]);
    }

    /**
     * Get DSS Rankings for Department
     *
     * Returns Profile Matching DSS-calculated candidate rankings for a specific department. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam department integer required The department/biro ID. Example: 1
     *
     * @response 200 {"success": true, "department": {"id": 1, "name": "Biro Humas"}, "rankings": [], "announcements": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function getRankings(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $rankings = $this->dss->getDepartmentRankings($department);
        $announcements = Announcement::whereIn('candidate_id', collect($rankings)->pluck('candidate.id'))
            ->get()->keyBy('candidate_id');
        return response()->json(['success' => true, 'department' => $department, 'rankings' => $rankings, 'announcements' => $announcements]);
    }

    /**
     * Decide Candidate (Accept/Reject)
     *
     * Save the admin's or interviewer's decision for a candidate.
     * Both `admin` and `interviewer` roles can call this endpoint.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam candidate integer required The candidate ID. Example: 1
     * @bodyParam status string required One of: `accepted`, `rejected`. Example: accepted
     * @bodyParam assigned_department_id integer required if accepted. Department to assign. Example: 1
     *
     * @response 200 {"success": true, "message": "Decision saved successfully!", "data": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function decideCandidate(Request $request, Candidate $candidate)
    {
        if (!in_array($request->user()->role, ['admin', 'interviewer'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'assigned_department_id' => 'required_if:status,accepted|exists:departmentsbiro,id|nullable',
        ]);
        $announcement = Announcement::updateOrCreate(
            ['candidate_id' => $candidate->id],
            ['status' => $request->status, 'assigned_department_id' => $request->status === 'accepted' ? $request->assigned_department_id : null]
        );
        return response()->json(['success' => true, 'message' => 'Decision saved successfully!', 'data' => $announcement]);
    }

    /**
     * Toggle Announcement Publish State
     *
     * Publish or unpublish all candidate announcements at once. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @bodyParam is_published boolean required `true` to publish, `false` to unpublish. Example: true
     *
     * @response 200 {"success": true, "message": "Announcement board visibility toggled successfully!"}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function publishAnnouncements(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate(['is_published' => 'required|boolean']);
        Announcement::query()->update([
            'is_published' => $request->is_published,
            'published_at' => $request->is_published ? now() : null,
        ]);
        return response()->json(['success' => true, 'message' => 'Announcement board visibility toggled successfully!']);
    }

    // ─────────────────────────────────────────────────────────────────
    // DEPARTMENTS CRUD
    // ─────────────────────────────────────────────────────────────────

    /**
     * Create Department
     *
     * Create a new department/biro. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @bodyParam name string required Department name. Must be unique. Example: Biro Humas
     * @bodyParam description string optional Description. Example: Biro Hubungan Masyarakat
     * @bodyParam core_factor_weight number required Core factor weight (0–1). Example: 0.6
     * @bodyParam secondary_factor_weight number required Secondary factor weight (0–1). Example: 0.4
     *
     * @response 201 {"success": true, "message": "Department created successfully!", "data": {"id": 3, "name": "Biro Humas"}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function storeDepartment(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:departmentsbiro,slug',
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $department = Departmentsbiro::create([
            ...$request->only('name', 'description', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => $request->slug ?: \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json(['success' => true, 'message' => 'Department created successfully!', 'data' => $department], 201);
    }

    /**
     * Update Department
     *
     * Update an existing department/biro. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam department integer required The department ID. Example: 1
     * @bodyParam name string required Department name. Example: Biro Humas
     * @bodyParam description string optional Description. Example: Updated description
     * @bodyParam core_factor_weight number required Core factor weight (0–1). Example: 0.6
     * @bodyParam secondary_factor_weight number required Secondary factor weight (0–1). Example: 0.4
     *
     * @response 200 {"success": true, "message": "Department updated successfully!", "data": {"id": 1, "name": "Biro Humas"}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function updateDepartment(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name,' . $department->id,
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:departmentsbiro,slug,' . $department->id,
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $department->update([
            ...$request->only('name', 'description', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => $request->slug ?: \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->boolean('is_active', $department->is_active),
        ]);

        return response()->json(['success' => true, 'message' => 'Department updated successfully!', 'data' => $department]);
    }

    /**
     * Delete Department
     *
     * Delete a department/biro. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam department integer required The department ID. Example: 1
     *
     * @response 200 {"success": true, "message": "Department deleted successfully."}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function destroyDepartment(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEW SCHEDULES CRUD
    // ─────────────────────────────────────────────────────────────────

    /**
     * Get All Schedules (Admin)
     *
     * Returns all interview schedule slots with candidate and interviewer details. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": [{"id": 1, "session_name": "Sesi Pagi A", "scheduled_at": "2025-08-15T09:00:00Z", "location": "Ruang Rapat", "candidate": null, "interviewers": []}]
     * }
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function getAdminSchedules(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $schedules = InterviewSchedule::with(['department', 'booking.candidate.user', 'interviewers'])->orderBy('scheduled_at')->get();

        return response()->json(['success' => true, 'data' => $schedules]);
    }

    /**
     * Create Interview Schedule
     *
     * Create a new interview time slot and optionally assign interviewers. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @bodyParam session_name string required Session name. Example: Sesi Pagi A
     * @bodyParam scheduled_at string required Date and time (ISO 8601). Example: 2025-08-15T09:00:00
     * @bodyParam location string required Location. Example: Ruang Rapat HIMATIK
     * @bodyParam interviewer_ids integer[] optional Array of user IDs (interviewers) to assign. Example: [2, 3]
     *
     * @response 201 {"success": true, "message": "Schedule created successfully!", "data": {"id": 5, "session_name": "Sesi Pagi A"}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function storeSchedule(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'session_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departmentsbiro,id',
            'scheduled_at' => 'required|date',
            'location' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'interviewer_ids' => 'nullable|array',
            'interviewer_ids.*' => 'exists:users,id',
        ]);

        $schedule = InterviewSchedule::create([
            'department_id' => $request->department_id,
            'session_name' => $request->session_name,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->interviewer_ids) {
            $schedule->interviewers()->sync($request->interviewer_ids);
        }

        return response()->json(['success' => true, 'message' => 'Schedule created successfully!', 'data' => $schedule->load('interviewers')], 201);
    }

    /**
     * Update Interview Schedule
     *
     * Update an existing interview schedule slot. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam schedule integer required The schedule ID. Example: 1
     * @bodyParam session_name string required Session name. Example: Sesi Pagi A
     * @bodyParam scheduled_at string required Date and time (ISO 8601). Example: 2025-08-15T09:00:00
     * @bodyParam location string required Location. Example: Ruang Rapat HIMATIK
     * @bodyParam interviewer_ids integer[] optional Array of user IDs to assign. Example: [2, 3]
     *
     * @response 200 {"success": true, "message": "Schedule updated successfully!", "data": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function updateSchedule(Request $request, InterviewSchedule $schedule)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'session_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departmentsbiro,id',
            'scheduled_at' => 'required|date',
            'location' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'interviewer_ids' => 'nullable|array',
            'interviewer_ids.*' => 'exists:users,id',
        ]);

        $schedule->update([
            'department_id' => $request->department_id,
            'session_name' => $request->session_name,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active', $schedule->is_active),
        ]);

        $schedule->interviewers()->sync($request->interviewer_ids ?? []);

        return response()->json(['success' => true, 'message' => 'Schedule updated successfully!', 'data' => $schedule->load('interviewers')]);
    }

    /**
     * Delete Interview Schedule
     *
     * Delete an interview schedule slot. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam schedule integer required The schedule ID. Example: 1
     *
     * @response 200 {"success": true, "message": "Schedule deleted successfully."}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function destroySchedule(Request $request, InterviewSchedule $schedule)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $schedule->interviewers()->detach();
        $schedule->delete();

        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // EVALUATION CRITERIA CRUD
    // ─────────────────────────────────────────────────────────────────

    /**
     * Get Evaluation Criteria
     *
     * Returns all evaluation criteria for a specific department. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam department integer required The department ID. Example: 1
     *
     * @response 200 {"success": true, "data": [{"id": 1, "name": "Komunikasi", "type": "core", "target_score": 4}]}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function getCriteria(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $criteria = EvaluationCriteria::where('department_id', $department->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json(['success' => true, 'data' => $criteria]);
    }

    /**
     * Create Evaluation Criterion
     *
     * Add a new evaluation criterion for a specific department. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam department integer required The department ID. Example: 1
     * @bodyParam name string required Criterion name. Example: Komunikasi
     * @bodyParam type string required Factor type. One of: `core`, `secondary`. Example: core
     * @bodyParam target_score integer required Target score (1–5). Example: 4
     * @bodyParam description string optional Description. Example: Kemampuan berbicara di depan umum
     *
     * @response 201 {"success": true, "message": "Criterion created successfully!", "data": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function storeCriterion(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'aspect' => 'required|in:personal,organizational',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $criterion = EvaluationCriteria::create([
            'department_id' => $department->id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'aspect' => $request->aspect,
            'target_score' => $request->target_score,
            'description' => $request->description,
            'catatan' => $request->catatan,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Criterion created successfully!', 'data' => $criterion], 201);
    }

    /**
     * Update Evaluation Criterion
     *
     * Update an existing evaluation criterion for a department. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam criterion integer required The criterion ID. Example: 1
     * @bodyParam name string required Criterion name. Example: Komunikasi
     * @bodyParam type string required Factor type. One of: `core`, `secondary`. Example: core
     * @bodyParam target_score integer required Target score (1–5). Example: 4
     * @bodyParam description string optional Description. Example: Updated description
     *
     * @response 200 {"success": true, "message": "Criterion updated successfully!", "data": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function updateCriterion(Request $request, EvaluationCriteria $criterion)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'aspect' => 'required|in:personal,organizational',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $criterion->update([
            ...$request->only('name', 'code', 'type', 'aspect', 'target_score', 'description', 'catatan'),
            'is_active' => $request->boolean('is_active', $criterion->is_active),
            'sort_order' => $request->sort_order ?? $criterion->sort_order,
        ]);

        return response()->json(['success' => true, 'message' => 'Criterion updated successfully!', 'data' => $criterion]);
    }

    /**
     * Delete Evaluation Criterion
     *
     * Delete an evaluation criterion. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam criterion integer required The criterion ID. Example: 1
     *
     * @response 200 {"success": true, "message": "Criterion deleted successfully."}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function destroyCriterion(Request $request, EvaluationCriteria $criterion)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $criterion->delete();

        return response()->json(['success' => true, 'message' => 'Criterion deleted successfully.']);
    }

    public function resetCriteria(Request $request, Departmentsbiro $department)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $department->evaluationCriteria()->delete();
        DefaultEvaluationCriteria::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->each(function (DefaultEvaluationCriteria $default) use ($department) {
                $department->evaluationCriteria()->create([
                    'default_criteria_id' => $default->id,
                    'code' => $default->code,
                    'name' => $default->name,
                    'description' => $default->description,
                    'type' => $default->type,
                    'aspect' => $default->aspect,
                    'target_score' => SpkCriteriaDefaults::targetScoreFor($department->name, $default->code, $default->target_score),
                    'catatan' => $default->catatan,
                    'is_active' => true,
                    'sort_order' => $default->sort_order,
                ]);
            });

        return response()->json(['success' => true, 'message' => 'Criteria reset to defaults successfully.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEWERS CRUD
    // ─────────────────────────────────────────────────────────────────

    /**
     * Get All Interviewers
     *
     * Returns a list of all users with the `interviewer` role. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": [{"id": 2, "name": "Budi Santoso", "email": "budi@himatik.ac.id", "role": "interviewer"}]
     * }
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function getInterviewers(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $interviewers = User::where('role', 'interviewer')->get();

        return response()->json(['success' => true, 'data' => $interviewers]);
    }

    /**
     * Create Interviewer
     *
     * Create a new interviewer account. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @bodyParam name string required Interviewer's full name. Example: Budi Santoso
     * @bodyParam email string required Email address. Must be unique. Example: budi@himatik.ac.id
     * @bodyParam password string required Min 8 characters. Example: password123
     *
     * @response 201 {"success": true, "message": "Interviewer created successfully!", "data": {"id": 2, "name": "Budi Santoso"}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function storeInterviewer(Request $request)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $interviewer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'interviewer',
        ]);

        return response()->json(['success' => true, 'message' => 'Interviewer created successfully!', 'data' => $interviewer], 201);
    }

    /**
     * Update Interviewer
     *
     * Update an interviewer's account details. Leave `password` blank to keep it unchanged. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam user integer required The user ID of the interviewer. Example: 2
     * @bodyParam name string required Interviewer's full name. Example: Budi Santoso
     * @bodyParam email string required Email address. Example: budi@himatik.ac.id
     * @bodyParam password string optional New password (min 8 chars). Leave blank to keep current. Example: newpassword123
     *
     * @response 200 {"success": true, "message": "Interviewer updated successfully!", "data": {}}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function updateInterviewer(Request $request, User $user)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'Interviewer updated successfully!', 'data' => $user]);
    }

    /**
     * Delete Interviewer
     *
     * Delete an interviewer account. Admin only.
     *
     * @group Admin
     * @authenticated
     *
     * @urlParam user integer required The user ID of the interviewer. Example: 2
     *
     * @response 200 {"success": true, "message": "Interviewer deleted successfully."}
     * @response 403 {"success": false, "message": "Unauthorized."}
     */
    public function destroyInterviewer(Request $request, User $user)
    {
        if ($err = $this->requireAdmin($request)) return $err;

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Interviewer deleted successfully.']);
    }
}
