<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class InterviewerApiController extends Controller
{
    /**
     * Get Interviewer's Schedules
     *
     * Returns interview slots for the currently logged-in interviewer's assigned department,
     * with booked candidate and department details.
     *
     * @group Interviewer
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "date": "2026-06-10",
     *       "start_time": "09:00:00",
     *       "end_time": "10:00:00",
     *       "is_blocked": false,
     *       "candidate": {
     *         "id": 1,
     *         "nim": "2211501234",
     *         "user": {"name": "Ahmad Rizki", "email": "ahmad@student.pnj.ac.id"}
     *       }
     *     }
     *   ]
     * }
     * @response 403 {
     *   "success": false,
     *   "message": "Unauthorized. Only interviewers/admins can access."
     * }
     */
    public function getSchedules(Request $request)
    {
        $interviewer = $request->user();

        if ($interviewer->role !== 'interviewer' && $interviewer->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only interviewers/admins can access.'
            ], 403);
        }

        if (!$interviewer->department_id) {
            return response()->json([
                'success' => false,
                'message' => 'Interviewer belum memiliki departemen/biro yang ditugaskan.',
            ], 422);
        }

        $schedules = \App\Models\InterviewSchedule::with([
                'department',
                'booking.candidate.user',
                'booking.candidate.departmentChoices.department',
            ])
            ->where('department_id', $interviewer->department_id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Get Grading Details
     *
     * Returns evaluation criteria and existing scores for a specific candidate and department pair.
     * Used by the Flutter app to pre-fill the grading form.
     *
     * @group Interviewer
     * @authenticated
     *
     * @urlParam candidate integer required The candidate ID. Example: 1
     * @urlParam department integer required The department/biro ID. Example: 2
     *
     * @response 200 {
     *   "success": true,
     *   "candidate": {"id": 1, "nim": "2211501234", "user": {"name": "Ahmad Rizki"}},
     *   "department": {"id": 2, "name": "Biro Akademik"},
     *   "criteria": [
     *     {"id": 1, "name": "Motivasi", "weight": 0.3},
     *     {"id": 2, "name": "Kemampuan Komunikasi", "weight": 0.3}
     *   ],
     *   "existing_scores": {"1": 4, "2": 3}
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Candidate did not choose this department."
     * }
     */
    public function getGradingDetails(Request $request, Candidate $candidate, Departmentsbiro $department)
    {
        if (!$candidate->departmentChoices()->where('departmentsbiro_id', $department->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate did not choose this department.'
            ], 400);
        }

        $criteria = EvaluationCriteria::where('department_id', $department->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $existingScores = Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->select('criteria_id', 'score', 'version')
            ->get()
            ->keyBy('criteria_id');

        return response()->json([
            'success' => true,
            'candidate' => $candidate->load('user'),
            'department' => $department,
            'criteria' => $criteria,
            'existing_scores' => $existingScores->map(fn ($evaluation) => [
                'score' => $evaluation->score,
                'version' => $evaluation->version,
            ])
        ]);
    }

    /**
     * Submit Evaluation Scores
     *
     * Save or update consensus scores for a candidate per department criteria.
     * Scores must be integers from 1–5. Updates candidate status to `evaluated`.
     *
     * @group Interviewer
     * @authenticated
     *
     * @urlParam candidate integer required The candidate ID. Example: 1
     * @urlParam department integer required The department/biro ID. Example: 2
     *
     * @bodyParam scores object required Key-value map of criteria_id → score (1–5). Example: {"1": 4, "2": 3, "3": 5}
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Consensus scores saved successfully!"
     * }
     * @response 422 scenario="Validation Error" {
     *   "message": "The scores field is required.",
     *   "errors": {"scores": ["The scores field is required."]}
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to save scores.",
     *   "error": "..."
     * }
     */
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
                $evaluation->interviewer_id = $request->user()->id;
                $evaluation->version = $evaluation->exists ? $evaluation->version + 1 : 1;
                $evaluation->save();
            }
            $candidate->update(['status' => 'evaluated']);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Consensus scores saved successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save scores.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
