<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\EvaluationCriteria;
use App\Models\InterviewSchedule;
use App\Models\Announcement;
use App\Models\User;
use App\Services\ProfileMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminWebController extends Controller
{
    protected $dss;

    public function __construct(ProfileMatchingService $dss)
    {
        $this->dss = $dss;
    }

    // ─────────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_candidates' => Candidate::count(),
            'total_registered' => Candidate::where('status', 'registered')->count(),
            'total_scheduled' => Candidate::where('status', 'scheduled')->count(),
            'total_evaluated' => Candidate::where('status', 'evaluated')->count(),
        ];
        $departments = Departmentsbiro::withCount(['firstChoiceCandidates', 'secondChoiceCandidates'])->get();
        return view('admin.dashboard', compact('stats', 'departments'));
    }

    // ─────────────────────────────────────────────────────────────────
    // DEPARTMENTS CRUD
    // ─────────────────────────────────────────────────────────────────

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name',
            'description' => 'nullable|string',
            'core_factor_weight' => 'required|numeric|min:0|max:1',
            'secondary_factor_weight' => 'required|numeric|min:0|max:1',
        ]);

        Departmentsbiro::create($request->only('name', 'description', 'core_factor_weight', 'secondary_factor_weight'));

        return back()->with('success', "Department '{$request->name}' created successfully!");
    }

    public function updateDepartment(Request $request, Departmentsbiro $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name,' . $department->id,
            'description' => 'nullable|string',
            'core_factor_weight' => 'required|numeric|min:0|max:1',
            'secondary_factor_weight' => 'required|numeric|min:0|max:1',
        ]);

        $department->update($request->only('name', 'description', 'core_factor_weight', 'secondary_factor_weight'));

        return back()->with('success', "Department '{$department->name}' updated successfully!");
    }

    public function destroyDepartment(Departmentsbiro $department)
    {
        $department->delete();
        return back()->with('success', "Department deleted successfully.");
    }

    // ─────────────────────────────────────────────────────────────────
    // RANKINGS
    // ─────────────────────────────────────────────────────────────────

    public function showRankings(Departmentsbiro $department)
    {
        $rankings = $this->dss->getDepartmentRankings($department);
        $announcements = Announcement::whereIn('candidate_id', collect($rankings)->pluck('candidate.id'))
            ->get()
            ->keyBy('candidate_id');
        return view('admin.rankings', compact('department', 'rankings', 'announcements'));
    }

    // ─────────────────────────────────────────────────────────────────
    // EVALUATION CRITERIA CRUD
    // ─────────────────────────────────────────────────────────────────

    public function listCriteria(Departmentsbiro $department)
    {
        $criteria = $department->evaluationCriteria;
        return view('admin.criteria', compact('department', 'criteria'));
    }

    public function storeCriterion(Request $request, Departmentsbiro $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ]);
        $department->evaluationCriteria()->create($request->only('name', 'type', 'target_score', 'description'));
        return back()->with('success', 'Criterion added successfully!');
    }

    public function updateCriterion(Request $request, Departmentsbiro $department, EvaluationCriteria $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ]);
        $criterion->update($request->only('name', 'type', 'target_score', 'description'));
        return back()->with('success', "Criterion '{$criterion->name}' updated successfully!");
    }

    public function destroyCriterion(Departmentsbiro $department, EvaluationCriteria $criterion)
    {
        $criterion->delete();
        return back()->with('success', 'Criterion deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEW SCHEDULES CRUD
    // ─────────────────────────────────────────────────────────────────

    public function listSchedules()
    {
        $schedules = InterviewSchedule::with(['candidate.user', 'interviewers'])->orderBy('scheduled_at')->get();
        $interviewers = User::where('role', 'interviewer')->get();
        return view('admin.schedules', compact('schedules', 'interviewers'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'required|string|max:255',
            'interviewer_ids' => 'nullable|array',
            'interviewer_ids.*' => 'exists:users,id',
        ]);

        $schedule = InterviewSchedule::create([
            'session_name' => $request->session_name,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
        ]);

        if ($request->interviewer_ids) {
            $schedule->interviewers()->sync($request->interviewer_ids);
        }

        return back()->with('success', "Schedule '{$request->session_name}' created successfully!");
    }

    public function updateSchedule(Request $request, InterviewSchedule $schedule)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'required|string|max:255',
            'interviewer_ids' => 'nullable|array',
            'interviewer_ids.*' => 'exists:users,id',
        ]);

        $schedule->update([
            'session_name' => $request->session_name,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
        ]);

        $schedule->interviewers()->sync($request->interviewer_ids ?? []);

        return back()->with('success', "Schedule '{$schedule->session_name}' updated successfully!");
    }

    public function destroySchedule(InterviewSchedule $schedule)
    {
        $schedule->interviewers()->detach();
        $schedule->delete();
        return back()->with('success', 'Schedule deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEWERS CRUD
    // ─────────────────────────────────────────────────────────────────

    public function listInterviewers()
    {
        $interviewers = User::where('role', 'interviewer')->get();
        return view('admin.interviewers', compact('interviewers'));
    }

    public function storeInterviewer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'interviewer',
        ]);

        return back()->with('success', "Interviewer '{$request->name}' created successfully!");
    }

    public function updateInterviewer(Request $request, User $user)
    {
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

        return back()->with('success', "Interviewer '{$user->name}' updated successfully!");
    }

    public function destroyInterviewer(User $user)
    {
        $user->delete();
        return back()->with('success', 'Interviewer account deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // DECISIONS & ANNOUNCEMENTS
    // ─────────────────────────────────────────────────────────────────

    public function decideCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'assigned_department_id' => 'required_if:status,accepted|exists:departmentsbiro,id|nullable',
        ]);
        Announcement::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'status' => $request->status,
                'assigned_department_id' => $request->status === 'accepted' ? $request->assigned_department_id : null,
            ]
        );
        return back()->with('success', "Decision saved successfully for {$candidate->user->name}.");
    }

    public function publishAnnouncements(Request $request)
    {
        $request->validate([
            'is_published' => 'required|boolean',
        ]);
        Announcement::query()->update([
            'is_published' => $request->is_published,
            'published_at' => $request->is_published ? now() : null,
        ]);
        $status = $request->is_published ? 'published' : 'hidden';
        return back()->with('success', "All final announcements successfully {$status} on the public board!");
    }
}
