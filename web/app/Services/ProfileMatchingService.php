<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\Evaluation;
use App\Models\SpkCalculationLog;
use App\Models\SpkGapWeight;
use App\Models\SpkResult;
use Illuminate\Support\Facades\Auth;

class ProfileMatchingService
{
    public function mapToWeight(int $gap): float
    {
        return (float) SpkGapWeight::where('gap', $gap)->value('weight') ?: 1.0;
    }

    public function calculateScore(Candidate $candidate, Departmentsbiro $department, ?int $calculatedBy = null): array
    {
        $startedAt = microtime(true);
        $criteriaList = $department->evaluationCriteria()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($criteriaList->isEmpty()) {
            return $this->emptyResult();
        }

        $evaluations = Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->get()
            ->keyBy('criteria_id');

        $gapWeights = SpkGapWeight::orderBy('gap')->get()->pluck('weight', 'gap');
        $groups = [
            'personal' => ['core' => [], 'secondary' => []],
            'organizational' => ['core' => [], 'secondary' => []],
        ];
        $breakdown = [];

        foreach ($criteriaList as $criteria) {
            $actualScore = (int) ($evaluations[$criteria->id]?->score ?? 0);
            $targetScore = (int) $criteria->target_score;
            $gap = $actualScore - $targetScore;
            $weight = (float) ($gapWeights[$gap] ?? 1.0);

            $groups[$criteria->aspect][$criteria->type][] = $weight;
            $breakdown[] = [
                'criteria_id' => $criteria->id,
                'code' => $criteria->code,
                'criteria_name' => $criteria->name,
                'aspect' => $criteria->aspect,
                'criteria_type' => $criteria->type,
                'actual_score' => $actualScore,
                'target_score' => $targetScore,
                'gap' => $gap,
                'mapped_weight' => round($weight, 4),
            ];
        }

        $personalCore = $this->average($groups['personal']['core']);
        $personalSecondary = $this->average($groups['personal']['secondary']);
        $organizationalCore = $this->average($groups['organizational']['core']);
        $organizationalSecondary = $this->average($groups['organizational']['secondary']);

        $coreWeight = ((float) $department->core_factor_weight) / 100;
        $secondaryWeight = ((float) $department->secondary_factor_weight) / 100;
        $personalWeight = ((float) $department->personal_aspect_weight) / 100;
        $organizationalWeight = ((float) $department->organizational_aspect_weight) / 100;

        $personalScore = ($coreWeight * $personalCore) + ($secondaryWeight * $personalSecondary);
        $organizationalScore = ($coreWeight * $organizationalCore) + ($secondaryWeight * $organizationalSecondary);
        $finalScore = ($personalWeight * $personalScore) + ($organizationalWeight * $organizationalScore);

        $result = [
            'total_score' => round($finalScore, 4),
            'final_score' => round($finalScore, 4),
            'ncf' => round(($personalCore + $organizationalCore) / 2, 4),
            'nsf' => round(($personalSecondary + $organizationalSecondary) / 2, 4),
            'personal_core_score' => round($personalCore, 4),
            'personal_secondary_score' => round($personalSecondary, 4),
            'personal_score' => round($personalScore, 4),
            'organizational_core_score' => round($organizationalCore, 4),
            'organizational_secondary_score' => round($organizationalSecondary, 4),
            'organizational_score' => round($organizationalScore, 4),
            'breakdown' => $breakdown,
            'weights' => [
                'personal_aspect_weight' => (float) $department->personal_aspect_weight,
                'organizational_aspect_weight' => (float) $department->organizational_aspect_weight,
                'core_factor_weight' => (float) $department->core_factor_weight,
                'secondary_factor_weight' => (float) $department->secondary_factor_weight,
            ],
            'gap_weights' => $gapWeights->map(fn ($weight) => (float) $weight)->all(),
        ];

        SpkResult::updateOrCreate(
            [
                'candidate_id' => $candidate->id,
                'department_id' => $department->id,
            ],
            [
                'final_score' => $result['final_score'],
                'personal_core_score' => $result['personal_core_score'],
                'personal_secondary_score' => $result['personal_secondary_score'],
                'personal_score' => $result['personal_score'],
                'organizational_core_score' => $result['organizational_core_score'],
                'organizational_secondary_score' => $result['organizational_secondary_score'],
                'organizational_score' => $result['organizational_score'],
                'calculation_details' => $result,
                'calculated_by' => $calculatedBy ?: Auth::id(),
                'calculated_at' => now(),
            ]
        );

        SpkCalculationLog::create([
            'department_id' => $department->id,
            'trigger_type' => 'manual',
            'triggered_by' => $calculatedBy ?: Auth::id(),
            'status' => 'success',
            'candidates_count' => 1,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);

        return $result;
    }

    public function getDepartmentRankings(Departmentsbiro $department): array
    {
        $candidates = Candidate::whereHas('departmentChoices', function ($query) use ($department) {
            $query->where('departmentsbiro_id', $department->id);
        })->with(['user', 'departmentChoices.department'])->get();

        $rankings = [];

        foreach ($candidates as $candidate) {
            $scores = $this->calculateScore($candidate, $department);
            $rankings[] = [
                'candidate' => $candidate,
                'total_score' => $scores['total_score'],
                'final_score' => $scores['final_score'],
                'ncf' => $scores['ncf'],
                'nsf' => $scores['nsf'],
                'personal_score' => $scores['personal_score'],
                'organizational_score' => $scores['organizational_score'],
                'breakdown' => $scores['breakdown'],
            ];
        }

        usort($rankings, fn ($a, $b) => $b['total_score'] <=> $a['total_score']);

        foreach ($rankings as $index => $ranking) {
            SpkResult::where('candidate_id', $ranking['candidate']->id)
                ->where('department_id', $department->id)
                ->update(['rank_position' => $index + 1]);
            $rankings[$index]['rank_position'] = $index + 1;
        }

        return $rankings;
    }

    private function average(array $values): float
    {
        return count($values) > 0 ? array_sum($values) / count($values) : 0.0;
    }

    private function emptyResult(): array
    {
        return [
            'total_score' => 0.0,
            'final_score' => 0.0,
            'ncf' => 0.0,
            'nsf' => 0.0,
            'personal_core_score' => 0.0,
            'personal_secondary_score' => 0.0,
            'personal_score' => 0.0,
            'organizational_core_score' => 0.0,
            'organizational_secondary_score' => 0.0,
            'organizational_score' => 0.0,
            'breakdown' => [],
        ];
    }
}
