<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\DefaultEvaluationCriteria;
use App\Models\Evaluation;
use App\Models\EvaluationCriteria;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Services\ProfileMatchingService;
use App\Support\SpkCriteriaDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'total_departments' => Departmentsbiro::count(),
            'total_criteria' => EvaluationCriteria::count(),
            'total_evaluated' => Candidate::whereHas('evaluations')->distinct('id')->count(),
        ];

        $departments = Departmentsbiro::withCount(['firstChoiceCandidates', 'secondChoiceCandidates'])->get();

        $recentCandidates = Candidate::with('user', 'departmentChoices.department')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'departments', 'recentCandidates'));
    }

    // ─────────────────────────────────────────────────────────────────
    // DEPARTMENTS CRUD
    // ─────────────────────────────────────────────────────────────────

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name',
            'description' => 'nullable|string',
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        Departmentsbiro::create([
            ...$request->only('name', 'description', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Department '{$request->name}' created successfully!");
    }

    public function updateDepartment(Request $request, Departmentsbiro $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name,' . $department->id,
            'description' => 'nullable|string',
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $department->update([
            ...$request->only('name', 'description', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', $department->is_active),
        ]);

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
        $criteria = $department->evaluationCriteria()->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.criteria', compact('department', 'criteria'));
    }

    public function storeCriterion(Request $request, Departmentsbiro $department)
    {
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
        $department->evaluationCriteria()->create([
            ...$request->only('name', 'code', 'type', 'aspect', 'target_score', 'description', 'catatan'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);
        return back()->with('success', 'Criterion added successfully!');
    }

    public function updateCriterion(Request $request, Departmentsbiro $department, EvaluationCriteria $criterion)
    {
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
        return back()->with('success', "Criterion '{$criterion->name}' updated successfully!");
    }

    public function destroyCriterion(Departmentsbiro $department, EvaluationCriteria $criterion)
    {
        $criterion->delete();
        return back()->with('success', 'Criterion deleted successfully.');
    }

    public function resetCriteria(Departmentsbiro $department)
    {
        $department->evaluationCriteria()->delete();
        DefaultEvaluationCriteria::where('is_active', true)->orderBy('sort_order')->get()
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

        return back()->with('success', 'Criteria reset to defaults successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEW SCHEDULES CRUD
    // ─────────────────────────────────────────────────────────────────

    public function listSchedules()
    {
        $schedules = InterviewSchedule::with(['department', 'booking.candidate.user', 'interviewers'])->orderBy('scheduled_at')->get();
        $interviewers = User::where('role', 'interviewer')->get();
        $departments = Departmentsbiro::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules', compact('schedules', 'interviewers', 'departments'));
    }

    public function storeSchedule(Request $request)
    {
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

        return back()->with('success', "Schedule '{$request->session_name}' created successfully!");
    }

    public function updateSchedule(Request $request, InterviewSchedule $schedule)
    {
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

    // ─────────────────────────────────────────────────────────────────
    // DSS PROFILE MATCHING — TESTING PAGE
    // ─────────────────────────────────────────────────────────────────

    public function testing(Request $request)
    {
        $departments = Departmentsbiro::with('evaluationCriteria')->get();

        $selectedDepartment = null;
        $criteria = collect();
        $rankings = [];
        $candidates = collect();
        $existingScores = []; // [candidate_id][criteria_id] => score
        $error = null;
        $search = $request->get('search', '');

        if ($request->filled('department_id')) {
            $selectedDepartment = Departmentsbiro::with('evaluationCriteria')
                ->find($request->department_id);

            if (!$selectedDepartment) {
                $error = 'Department not found.';
            } else {
                $criteria = $selectedDepartment->evaluationCriteria()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

                if ($criteria->isEmpty()) {
                    $error = "Department \"{$selectedDepartment->name}\" has no evaluation criteria defined yet. Add criteria via Admin → Criteria.";
                } else {
                    // All candidates who chose this department (choice 1 or 2) + search
                    $candidatesQuery = Candidate::with('user', 'departmentChoices.department')
                        ->whereHas('departmentChoices', function ($q) use ($selectedDepartment) {
                            $q->where('departmentsbiro_id', $selectedDepartment->id);
                        });

                    if ($search) {
                        $candidatesQuery->where(function ($q) use ($search) {
                            $q->whereHas('user', function ($uq) use ($search) {
                                $uq->where('name', 'like', '%' . $search . '%');
                            })->orWhere('nim', 'like', '%' . $search . '%');
                        });
                    }

                    $candidates = $candidatesQuery->paginate(5);

                    // Pre-load existing evaluation scores into a 2D map
                    if ($candidates->isNotEmpty()) {
                        Evaluation::where('department_id', $selectedDepartment->id)
                            ->whereIn('candidate_id', $candidates->pluck('id'))
                            ->get()
                            ->each(function ($ev) use (&$existingScores) {
                                $existingScores[$ev->candidate_id][$ev->criteria_id] = $ev->score;
                            });
                    }

                    // Always run rankings (service handles empty gracefully)
                    $rankings = $this->dss->getDepartmentRankings($selectedDepartment);
                }
            }
        }

        return view('admin.testing', compact(
            'departments',
            'selectedDepartment',
            'criteria',
            'rankings',
            'candidates',
            'existingScores',
            'error',
            'search'
        ));
    }

    /**
     * CRUD: Save (upsert) evaluation scores for a candidate in a department.
     * Admin acts as the evaluator (interviewer_id = auth()->id()).
     */
    public function testingSaveScores(Request $request, Candidate $candidate, Departmentsbiro $department)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|integer|min:1|max:5',
        ]);

        $validCriteriaIds = $department->evaluationCriteria()->where('is_active', true)->pluck('id')->toArray();

        foreach ($request->scores as $criteriaId => $score) {
            if ($score === null) {
                continue; // Skip empty fields to avoid overwriting with null
            }
            if (!in_array((int) $criteriaId, $validCriteriaIds)) {
                continue; // Reject criteria not belonging to this department
            }

            $evaluation = Evaluation::firstOrNew([
                'candidate_id' => $candidate->id,
                'department_id' => $department->id,
                'criteria_id' => (int) $criteriaId,
            ]);
            $evaluation->score = (int) $score;
            $evaluation->interviewer_id = auth()->id();
            $evaluation->version = $evaluation->exists ? $evaluation->version + 1 : 1;
            $evaluation->save();
        }

        return redirect()
            ->route('admin.testing', ['department_id' => $department->id])
            ->with('success', "Skor untuk \"" . ($candidate->user->name ?? 'Kandidat') . "\" berhasil disimpan!");
    }

    /**
     * CRUD: Delete all evaluation scores for a candidate in a department.
     */
    public function testingResetScores(Candidate $candidate, Departmentsbiro $department)
    {
        $deleted = Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->delete();

        return redirect()
            ->route('admin.testing', ['department_id' => $department->id])
            ->with('info', "Semua skor untuk \"" . ($candidate->user->name ?? 'Kandidat') . "\" telah dihapus. ({$deleted} record)");
    }

    /**
     * CRUD: Create a new User (role: candidate) + Candidate record for testing.
     * photo_path is set to a placeholder since it is NOT nullable in the DB.
     */
    public function testingStoreCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|unique:candidates,nim',
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'nullable|exists:departmentsbiro,id|different:first_choice_id',
            'department_id' => 'required|exists:departmentsbiro,id',
        ]);

        \DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('testing123'), // default password for test accounts
                'role' => 'candidate',
            ]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => 'staff',
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'phone' => $request->phone,
                'photo_path' => 'testing/placeholder.jpg', // placeholder — required field
                'status' => 'registered',
            ]);
            $candidate->departmentChoices()->create([
                'departmentsbiro_id' => $request->first_choice_id,
                'choice_order' => 1,
            ]);
            if ($request->second_choice_id) {
                $candidate->departmentChoices()->create([
                    'departmentsbiro_id' => $request->second_choice_id,
                    'choice_order' => 2,
                ]);
            }
        });

        return redirect()
            ->route('admin.testing', ['department_id' => $request->department_id])
            ->with('success', "Kandidat \"{$request->name}\" berhasil ditambahkan! (password default: testing123)");
    }

    /**
     * CRUD: Update candidate profile & their linked user name/email.
     */
    public function testingUpdateCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $candidate->user_id,
            'nim' => 'required|string|unique:candidates,nim,' . $candidate->id,
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'nullable|exists:departmentsbiro,id|different:first_choice_id',
            'department_id' => 'required|exists:departmentsbiro,id',
        ]);

        $candidate->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $candidate->update([
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'kelas' => $request->kelas,
            'phone' => $request->phone,
        ]);
        $candidate->departmentChoices()->delete();
        $candidate->departmentChoices()->create([
            'departmentsbiro_id' => $request->first_choice_id,
            'choice_order' => 1,
        ]);
        if ($request->second_choice_id) {
            $candidate->departmentChoices()->create([
                'departmentsbiro_id' => $request->second_choice_id,
                'choice_order' => 2,
            ]);
        }

        return redirect()
            ->route('admin.testing', ['department_id' => $request->department_id])
            ->with('success', "Kandidat \"{$request->name}\" berhasil diperbarui!");
    }

    /**
     * CRUD: Delete a candidate and their linked User account.
     * Also cascades to evaluations via DB foreign key.
     */
    public function testingDestroyCandidate(Candidate $candidate)
    {
        $deptId = $candidate->first_choice_department?->id;
        $name = $candidate->user->name ?? 'Kandidat';

        // Delete the user — candidate is cascade-deleted via FK
        $candidate->user->delete();

        return redirect()
            ->route('admin.testing', ['department_id' => $deptId])
            ->with('success', "Kandidat \"{$name}\" dan akun user-nya berhasil dihapus.");
    }
}
