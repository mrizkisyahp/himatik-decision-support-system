<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;
use App\Models\Announcement;
use App\Models\DefaultEvaluationCriteria;
use App\Models\SpkResult;
use App\Services\ProfileMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterviewerWebController extends Controller
{
    protected $dss;

    public function __construct(ProfileMatchingService $dss)
    {
        $this->dss = $dss;
    }
    public function dashboard()
    {
        $interviewer = Auth::user();
        $department = $this->getDepartment();

        // Daftar Wawancara Hari Ini
        $todaySchedules = \App\Models\InterviewSchedule::where('department_id', $department->id)
            ->whereDate('date', today())
            ->whereHas('booking')
            ->with(['booking.candidate.user'])
            ->orderBy('start_time')
            ->get();

        // Top 3 Kandidat using ProfileMatchingService
        $rankings = $this->dss->getDepartmentRankings($department);
        $topCandidates = collect($rankings)->take(3)->map(function ($r) {
            $candidate = $r['candidate'];
            $candidate->total_score = $r['total_score'];
            return $candidate;
        });

        return view('interviewer.dashboard', compact('todaySchedules', 'topCandidates', 'department'));
    }

    public function registrations()
    {
        $candidates = \App\Models\Candidate::with(['user', 'departmentChoices.department'])->latest()->get();
        return view('interviewer.registrations', compact('candidates'));
    }

    public function schedules(Request $request)
    {
        $department = $this->getDepartment();
        $activeDepartmentId = $request->get('department_id', $department->id);

        $departments = \App\Models\Departmentsbiro::where('is_active', true)->get();

        $query = \App\Models\InterviewSchedule::with(['booking.candidate.user', 'department'])
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc');

        if ($activeDepartmentId) {
            $query->where('department_id', $activeDepartmentId);
        }

        $allSchedules = $query->get();

        $dates = $allSchedules->pluck('date')->unique()->sort()->values();
        $timeSlots = $allSchedules->map(function ($sch) {
            return $sch->start_time . ' - ' . $sch->end_time;
        })->unique()->sort()->values();

        $schedules = [];
        foreach ($allSchedules as $sch) {
            $timeKey = $sch->start_time . ' - ' . $sch->end_time;
            $dateKey = is_string($sch->date) ? \Carbon\Carbon::parse($sch->date)->format('Y-m-d') : $sch->date->format('Y-m-d');
            $schedules[$dateKey][$timeKey] = $sch;
        }

        return view('interviewer.schedules', compact('departments', 'activeDepartmentId', 'schedules', 'dates', 'timeSlots', 'department'));
    }

    public function toggleScheduleBlock(\App\Models\InterviewSchedule $schedule)
    {
        $department = $this->getDepartment();
        if ($schedule->department_id !== $department->id) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda hanya bisa mengubah jadwal departemen Anda sendiri.'], 403);
            }
            abort(403, 'Anda hanya bisa mengubah jadwal departemen Anda sendiri.');
        }

        $schedule->update(['is_blocked' => !$schedule->is_blocked]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Status jadwal berhasil diubah.');
    }

    public function profileMatching(Request $request)
    {
        $department = $this->getDepartment();
        $search = $request->get('search', '');
        $error = null;

        $criteria = $department->evaluationCriteria()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($criteria->isEmpty()) {
            $error = "Departemen \"{$department->name}\" belum memiliki kriteria evaluasi. Tambahkan melalui Kelola Kriteria.";
            $candidates = collect();
            $existingScores = [];
            $rankings = [];
        } else {
            $candidatesQuery = Candidate::with('user', 'departmentChoices.department')
                ->whereHas('departmentChoices', function ($q) use ($department) {
                    $q->where('departmentsbiro_id', $department->id);
                });

            if ($search) {
                $candidatesQuery->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                           ->orWhere('nim', 'like', '%' . $search . '%');
                    });
                });
            }

            $candidates = $candidatesQuery->paginate(5);
            $existingScores = [];

            if ($candidates->isNotEmpty()) {
                Evaluation::where('department_id', $department->id)
                    ->whereIn('candidate_id', $candidates->pluck('id'))
                    ->get()
                    ->each(function ($ev) use (&$existingScores) {
                        $existingScores[$ev->candidate_id][$ev->criteria_id] = [
                            'score' => $ev->score,
                            'notes' => $ev->notes,
                        ];
                    });
            }

            $rankings = $this->dss->getDepartmentRankings($department);
        }

        // Variable remapping to match the blade template
        $selectedDepartment = $department;

        return view('interviewer.profile-matching', compact(
            'department',
            'selectedDepartment',
            'criteria',
            'rankings',
            'candidates',
            'existingScores',
            'error',
            'search'
        ));
    }

    public function profileMatchingCalculation(Candidate $candidate)
    {
        $department = $this->getDepartment();
        $candidate->loadMissing(['user', 'departmentChoices.department']);

        $belongsToDepartment = $candidate->departmentChoices()
            ->where('departmentsbiro_id', $department->id)
            ->exists();

        if (! $belongsToDepartment) {
            abort(404);
        }

        $spkResult = SpkResult::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->first();

        if (! $spkResult) {
            $this->dss->calculateScore($candidate, $department, Auth::id());

            $spkResult = SpkResult::where('candidate_id', $candidate->id)
                ->where('department_id', $department->id)
                ->first();
        }

        $details = $spkResult?->calculation_details ?? [];
        $breakdown = collect(data_get($details, 'breakdown', []));
        $weights = data_get($details, 'weights', []);
        $gapWeights = collect(data_get($details, 'gap_weights', []));

        $groupedBreakdown = [
            'personal' => [
                'core' => $breakdown->where('aspect', 'personal')->where('criteria_type', 'core')->values(),
                'secondary' => $breakdown->where('aspect', 'personal')->where('criteria_type', 'secondary')->values(),
            ],
            'organizational' => [
                'core' => $breakdown->where('aspect', 'organizational')->where('criteria_type', 'core')->values(),
                'secondary' => $breakdown->where('aspect', 'organizational')->where('criteria_type', 'secondary')->values(),
            ],
        ];

        return view('interviewer.profile-matching-calculation', compact(
            'candidate',
            'department',
            'spkResult',
            'details',
            'breakdown',
            'weights',
            'gapWeights',
            'groupedBreakdown'
        ));
    }

    // Show grading form
    public function showGradingForm(Candidate $candidate, Departmentsbiro $department)
    {
        $criteria = EvaluationCriteria::where('department_id', $department->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $existingScores = Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->pluck('score', 'criteria_id');

        // Fetch existing decision for this candidate
        $announcement = Announcement::where('candidate_id', $candidate->id)->first();

        $departments = Departmentsbiro::all();

        return view('interviewer.grade', compact('candidate', 'department', 'criteria', 'existingScores', 'announcement', 'departments'));
    }

    // Submit consensus scores
    public function submitScores(Request $request, Candidate $candidate, Departmentsbiro $department)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
            'global_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $first = true;
            foreach ($request->scores as $criteriaId => $score) {
                $criteriaExists = EvaluationCriteria::where('id', $criteriaId)
                    ->where('department_id', $department->id)
                    ->exists();
                if (!$criteriaExists) continue;

                $evaluation = Evaluation::firstOrNew([
                    'candidate_id' => $candidate->id,
                    'department_id' => $department->id,
                    'criteria_id' => $criteriaId,
                ]);
                $evaluation->score = $score;
                if ($first) {
                    $evaluation->notes = $request->input('global_notes');
                    $first = false;
                } else {
                    $evaluation->notes = null;
                }
                $evaluation->interviewer_id = Auth::id();
                $evaluation->version = $evaluation->exists ? $evaluation->version + 1 : 1;
                $evaluation->save();
            }
            $candidate->update(['status' => 'evaluated']);
            DB::commit();
            return back()->with('success', "Scores for {$candidate->user->name} in {$department->name} saved successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save scores. Please try again.');
        }
    }

    public function profileMatchingResetScores(Candidate $candidate, Departmentsbiro $department)
    {
        $ownDepartment = $this->getDepartment();
        if ($department->id !== $ownDepartment->id) {
            abort(403);
        }

        Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->delete();

        // Optionally revert status if no other departments evaluated
        if ($candidate->evaluations()->count() === 0) {
            $candidate->update(['status' => 'scheduled']);
            $candidate->announcement()->delete();
        }

        return back()->with('success', "Scores for {$candidate->user->name} have been reset.");
    }

    // Decide candidate (accept/reject) — interviewers can also do this
    public function decideCandidate(Request $request, Candidate $candidate)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['interviewer', 'admin'])) {
            return back()->with('success', 'Keputusan berhasil disimpan!');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'assigned_department_id' => 'required_if:status,accepted|exists:departmentsbiro,id|nullable',
        ]);

        Announcement::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'status' => $request->status,
                'assigned_department_id' => $request->status === 'accepted'
                    ? $request->assigned_department_id
                    : null,
            ]
        );

        $label = ucfirst($request->status);
        return back()->with('success', "{$candidate->user->name} has been {$label}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // CRITERIA CRUD FOR INTERVIEWER
    // ─────────────────────────────────────────────────────────────────

    private function getDepartment()
    {
        $interviewer = Auth::user();
        if ($interviewer->role !== 'interviewer' || !$interviewer->department) {
            abort(403, 'Hanya pewawancara yang memiliki akses.');
        }
        return $interviewer->department;
    }

    public function criteria()
    {
        $department = $this->getDepartment();
        if (!$department) abort(404, 'Departemen tidak ditemukan.');

        $criteria = $department->evaluationCriteria()->orderBy('sort_order')->orderBy('id')->get();
        $defaultCriteria = DefaultEvaluationCriteria::orderBy('sort_order')->orderBy('id')->get();
        
        // Check if dirty
        $isDirty = false;
        if ($criteria->count() !== $defaultCriteria->count()) {
            $isDirty = true;
        } else {
            foreach ($criteria as $index => $c) {
                $dc = $defaultCriteria[$index];
                if (
                    $c->code !== $dc->code ||
                    $c->name !== $dc->name ||
                    $c->type !== $dc->type ||
                    $c->aspect !== $dc->aspect ||
                    $c->target_score !== $dc->target_score
                ) {
                    $isDirty = true;
                    break;
                }
            }
        }

        return view('interviewer.criteria', compact('department', 'criteria', 'isDirty'));
    }

    public function updateWeights(Request $request)
    {
        $department = $this->getDepartment();
        $request->validate([
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
        ]);

        if (floatval($request->personal_aspect_weight) + floatval($request->organizational_aspect_weight) !== 100.0) {
            return back()->withErrors(['weight' => 'Total Bobot Personal dan Bobot Organizational harus 100%'])->withInput();
        }

        if (floatval($request->core_factor_weight) + floatval($request->secondary_factor_weight) !== 100.0) {
            return back()->withErrors(['weight' => 'Total Bobot Core Factor dan Bobot Secondary Factor harus 100%'])->withInput();
        }

        $department->update($request->only(
            'personal_aspect_weight', 
            'organizational_aspect_weight', 
            'core_factor_weight', 
            'secondary_factor_weight'
        ));

        return back()->with('success', 'Bobot departemen berhasil diperbarui!');
    }

    public function storeCriterion(Request $request)
    {
        $department = $this->getDepartment();
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
        
        return back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function updateCriterion(Request $request, EvaluationCriteria $criterion)
    {
        $department = $this->getDepartment();
        if ($criterion->department_id !== $department->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        $criterion->update([
            ...$request->only('name', 'code', 'type', 'target_score', 'description', 'catatan'),
            'is_active' => $request->boolean('is_active', $criterion->is_active),
            'sort_order' => $request->sort_order ?? $criterion->sort_order,
        ]);
        
        return back()->with('success', "Kriteria '{$criterion->name}' berhasil diperbarui!");
    }

    public function destroyCriterion(EvaluationCriteria $criterion)
    {
        $department = $this->getDepartment();
        if ($criterion->department_id !== $department->id) abort(403);

        $criterion->delete();
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }

    public function resetCriteria()
    {
        $department = $this->getDepartment();
        
        $department->evaluationCriteria()->delete();
        
        DefaultEvaluationCriteria::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->each(function (DefaultEvaluationCriteria $default) use ($department) {
                $department->evaluationCriteria()->create([
                    'default_criteria_id' => $default->id,
                    'code' => $default->code,
                    'name' => $default->name,
                    'description' => $default->description,
                    'type' => $default->type,
                    'aspect' => $default->aspect,
                    'target_score' => $default->target_score,
                    'catatan' => $default->catatan,
                    'is_active' => $default->is_active,
                    'sort_order' => $default->sort_order,
                ]);
            });

        return back()->with('success', 'Kriteria departemen berhasil di-reset ke Default Criteria.');
    }
}
