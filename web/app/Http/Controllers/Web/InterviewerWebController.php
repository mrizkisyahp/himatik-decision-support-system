<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;
use App\Models\Announcement;
use App\Models\DefaultEvaluationCriteria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterviewerWebController extends Controller
{
    // Show dashboard interview schedules
    public function index()
    {
        $interviewer = Auth::user() ?: \App\Models\User::where('role', 'interviewer')->first() ?: \App\Models\User::first();

        $schedules = collect();
        if ($interviewer) {
            $schedules = $interviewer->interviewSchedules()
                ->with(['department', 'booking.candidate.user', 'booking.candidate.departmentChoices.department'])
                ->orderBy('scheduled_at', 'asc')
                ->get();
        }

        return view('interviewer.dashboard', compact('schedules'));
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
        ]);

        DB::beginTransaction();
        try {
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
                $evaluation->interviewer_id = Auth::id();
                $evaluation->version = $evaluation->exists ? $evaluation->version + 1 : 1;
                $evaluation->save();
            }
            $candidate->update(['status' => 'evaluated']);
            DB::commit();
            return redirect()->route('interviewer.schedule')
                ->with('success', "Scores for {$candidate->user->name} in {$department->name} saved successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save scores. Please try again.');
        }
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
