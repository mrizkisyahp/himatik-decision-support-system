<?php

namespace Database\Seeders;

use App\Models\Departmentsbiro;
use App\Models\OpenRecruitment;
use App\Models\OpenRecruitmentQuota;
use Illuminate\Database\Seeder;

class OpenRecruitmentSeeder extends Seeder
{
    public function run(): void
    {
        OpenRecruitment::updateOrCreate(
            ['candidate_type' => 'staff'],
            [
                'starts_at' => now()->subDay()->setTime(8, 0),
                'ends_at' => now()->addDays(14)->setTime(23, 59),
                'status' => 'open',
            ]
        );

        OpenRecruitment::updateOrCreate(
            ['candidate_type' => 'bph'],
            [
                'starts_at' => now()->subDay()->setTime(8, 0),
                'ends_at' => now()->addDays(10)->setTime(23, 59),
                'status' => 'open',
            ]
        );

        $departments = Departmentsbiro::where('is_active', true)->get(['id']);

        foreach (['staff', 'bph'] as $type) {
            foreach ($departments as $department) {
                OpenRecruitmentQuota::updateOrCreate(
                    [
                        'candidate_type' => $type,
                        'department_id' => $department->id,
                    ],
                    [
                        'quota' => $type === 'staff' ? 5 : 2,
                    ]
                );
            }
        }
    }
}
