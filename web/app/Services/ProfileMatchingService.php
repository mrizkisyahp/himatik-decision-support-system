<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\Evaluation;

class ProfileMatchingService
{
    public function mapToWeight(int $gap): float
    {
        // Map difference (gap) to DSS weight value
        return match ($gap) {
            0 => 5.0,
            1 => 4.5,
            -1 => 4.0,
            2 => 3.5,
            -2 => 3.0,
            3 => 2.5,
            -3 => 2.0,
            4 => 1.5,
            -4 => 1.0,
            default => 1.0,
        };
    }

    // Calculate score for a single candidate in a department
    public function calculateScore(Candidate $candidate, Departmentsbiro $department): array
    {
        // Get all evaluation criteria defined for this department
        $criteriaList = $department->evaluationCriteria;

        if ($criteriaList->isEmpty()) {
            return [
                'total_score' => 0.0,
                'ncf' => 0.0,
                'nsf' => 0.0,
                'breakdown' => [],
            ];
        }

        // Get all scores submitted for this candidate in this department
        $evaluations = Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->get()
            ->keyBy('criteria_id');

        $coreFactorWeightsSum = 0.0;
        $coreFactorCount = 0;

        $secondaryFactorWeightsSum = 0.0;
        $secondaryFactorCount = 0;

        $breakdown = [];

        // Loop through each criteria and calculate gaps
        foreach ($criteriaList as $criteria) {
            // Get actual score (default to 0 if not evaluated yet)
            $actualScore = isset($evaluations[$criteria->id]) ? $evaluations[$criteria->id]->score : 0;
            $targetScore = $criteria->target_score;

            // Gap calculation (actual - target)
            $gap = $actualScore - $targetScore;

            // Map the gap to weight
            $weight = $this->mapToWeight($gap);

            // Group by core factor and secondary factor
            if ($criteria->type === 'core') {
                $coreFactorWeightsSum += $weight;
                $coreFactorCount++;
            } else {
                $secondaryFactorWeightsSum += $weight;
                $secondaryFactorCount++;
            }

            $breakdown[] = [
                'criteria_name' => $criteria->name,
                'criteria_type' => $criteria->type,
                'actual_score' => $actualScore,
                'target_score' => $targetScore,
                'gap' => $gap,
                'mapped_weight' => $weight,
            ];
        } // <-- Loop ends here!

        // Calculate averages (ncf & nsf) outside the loop
        $ncf = $coreFactorCount > 0 ? ($coreFactorWeightsSum / $coreFactorCount) : 0.0;
        $nsf = $secondaryFactorCount > 0 ? ($secondaryFactorWeightsSum / $secondaryFactorCount) : 0.0;

        // Calculate final score using department-configured weights
        $coreFactorWeight = $department->core_factor_weight;
        $secondaryFactorWeight = $department->secondary_factor_weight;

        $totalScore = ($coreFactorWeight * $ncf) + ($secondaryFactorWeight * $nsf);

        return [
            'total_score' => round($totalScore, 2),
            'ncf' => round($ncf, 2),
            'nsf' => round($nsf, 2),
            'breakdown' => $breakdown,
        ];
    }

    // Get ranked list of candidates for a specific department
    public function getDepartmentRankings(Departmentsbiro $department): array
    {
        // Fetch candidates who applied for this department as Choice 1 or Choice 2
        $candidates = Candidate::where('first_choice_id', $department->id)
            ->orWhere('second_choice_id', $department->id)
            ->get();

        $rankings = [];

        foreach ($candidates as $candidate) {
            /**
             * @var \App\Models\Candidate $candidate
             */
            $scores = $this->calculateScore($candidate, $department);

            $rankings[] = [
                'candidate' => $candidate,
                'total_score' => $scores['total_score'],
                'ncf' => $scores['ncf'],
                'nsf' => $scores['nsf'],
                'breakdown' => $scores['breakdown'],
            ];
        }

        // Sort descending by total score
        usort($rankings, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        return $rankings;
    }
}