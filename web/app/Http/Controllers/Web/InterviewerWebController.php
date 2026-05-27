<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;
use App\Models\Announcement;
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
                ->with(['candidate.user', 'candidate.firstChoice', 'candidate.secondChoice'])
                ->orderBy('scheduled_at', 'asc')
                ->get();
        }

        return view('interviewer.dashboard', compact('schedules'));
    }

    // Show grading form
    public function showGradingForm(Candidate $candidate, Departmentsbiro $department)
    {
        $criteria = EvaluationCriteria::where('department_id', $department->id)->get();

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

                Evaluation::updateOrCreate(
                    [
                        'candidate_id' => $candidate->id,
                        'department_id' => $department->id,
                        'criteria_id' => $criteriaId,
                    ],
                    [
                        'score' => $score,
                        'interviewer_id' => Auth::id(),
                    ]
                );
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
            return back()->with('error', 'Unauthorized.');
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
}