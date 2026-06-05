<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\CandidateCommittee;
use App\Models\CandidateEducation;
use App\Models\CandidateFacility;
use App\Models\CandidateInterviewSchedule;
use App\Models\CandidateOrganization;
use App\Models\CandidateSkill;
use App\Models\DefaultEvaluationCriteria;
use App\Models\Departmentsbiro;
use App\Models\Evaluation;
use App\Models\InterviewSchedule;
use App\Models\SpkGapWeight;
use App\Models\User;
use App\Support\SpkCriteriaDefaults;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecruitmentTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Himatik',
            'email' => 'admin@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $interviewers = collect([
            ['Falih', 'diktek@himatik.org'],
            ['Owen', 'kominfo@himatik.org'],
            ['Alif', 'sosma@himatik.org'],
            ['Daffa', 'kreatif@himatik.org'],
            ['Dandy', 'kestari@himatik.org'],
            ['Doni', 'sospol@himatik.org'],
            ['Hardimas', 'bismit@himatik.org'],
            ['Rania', 'bendum@himatik.org'],
            ['Zaldi', 'kesma@himatik.org'],
            ['Rizki', 'keroh@himatik.org'],
        ])->map(fn ($row) => User::create([
            'name' => $row[0],
            'email' => $row[1],
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
            'email_verified_at' => now(),
        ]));

        $departments = collect([
            ['Departemen Pendidikan dan Teknologi', 'Berfokus pada akademik, teknologi, dan pengembangan mahasiswa TIK.'],
            ['Departemen Kesehatan Mahasiswa', 'Mengelola kesehatan dan kebersihan lingkungan mahasiswa TIK.'],
            ['Biro Bendahara Umum', 'Mengelola pembukuan dan keuangan HIMATIK.'],
            ['Departemen Komunikasi dan Informasi', 'Mengelola komunikasi, publikasi, dan arus informasi.'],
            ['Departemen Bisnis dan Kemitraan', 'Mengelola wirausaha, pendanaan, kemitraan, dan logistik.'],
            ['Departemen Sosial Politik', 'Mengelola advokasi serta isu sosial politik internal dan eksternal.'],
            ['Biro Kesekretariatan', 'Mengelola administrasi dan kesekretariatan organisasi.'],
            ['Biro Kreatif', 'Mengelola desain, publikasi, dan kebutuhan kreatif organisasi.'],
            ['Departemen Kerohanian', 'Mengelola kegiatan dan informasi kerohanian.'],
            ['Departemen Sosial Mahasiswa', 'Mengelola advokasi dan kegiatan sosial kemahasiswaan.'],
        ])->map(fn ($row) => Departmentsbiro::create([
            'name' => $row[0],
            'slug' => Str::slug($row[0]),
            'description' => $row[1],
            'personal_aspect_weight' => 60.00,
            'organizational_aspect_weight' => 40.00,
            'core_factor_weight' => 60.00,
            'secondary_factor_weight' => 40.00,
            'is_active' => true,
        ]));

        $defaultCriteria = collect(SpkCriteriaDefaults::criteria())->map(fn ($row, $index) => DefaultEvaluationCriteria::create([
            'code' => $row['code'],
            'name' => $row['name'],
            'description' => $row['description'],
            'type' => $row['type'],
            'aspect' => $row['aspect'],
            'target_score' => $row['target_score'],
            'is_active' => true,
            'sort_order' => $index + 1,
        ]));

        foreach ($departments as $department) {
            foreach ($defaultCriteria as $default) {
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
            }
        }

        foreach ([[0, 5.0], [1, 4.5], [-1, 4.0], [2, 3.5], [-2, 3.0], [3, 2.5], [-3, 2.0], [4, 1.5], [-4, 1.0]] as [$gap, $weight]) {
            SpkGapWeight::create([
                'gap' => $gap,
                'weight' => $weight,
            ]);
        }

        $now = Carbon::now()->addDays(2)->setTime(9, 0);
        foreach ($departments as $index => $department) {
            $schedule = InterviewSchedule::create([
                'department_id' => $department->id,
                'session_name' => 'Sesi ' . ($index + 1) . ' - ' . $department->name,
                'scheduled_at' => $now->copy()->addHours($index % 5),
                'location' => 'Ruang HIMATIK',
                'is_active' => true,
            ]);
            $schedule->interviewers()->sync([
                $interviewers[$index % $interviewers->count()]->id,
                $interviewers[($index + 1) % $interviewers->count()]->id,
            ]);
        }

        $candidateRows = [
            ['Ahmad Rafif', 'rafif@example.com', '2507411075', 'TI 2B', 0, 3],
            ['Nayla Julita', 'nayla@example.com', '2507411091', 'TI 1C', 1, 0],
            ['Sakha Kirom', 'sakha@example.com', '2507411064', 'TI 2A', 7, 8],
            ['Dinda Aulia', 'dinda@example.com', '2507421003', 'TMJ 2A', 6, 9],
            ['Citra Ramadhani', 'citra@example.com', '2507431023', 'TMD 2C', 7, 4],
        ];

        foreach ($candidateRows as $index => [$name, $email, $nim, $kelas, $firstIndex, $secondIndex]) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'candidate',
                'email_verified_at' => now(),
            ]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => 'staff',
                'nickname' => strtok($name, ' '),
                'nim' => $nim,
                'prodi' => str_starts_with($kelas, 'TMJ') ? 'Teknik Multimedia dan Jaringan' : (str_starts_with($kelas, 'TMD') ? 'Teknik Multimedia dan Digital' : 'Teknik Informatika'),
                'kelas' => $kelas,
                'phone' => '0812345678' . $index,
                'address' => 'Alamat placeholder ' . $name,
                'department_choice_reason' => 'Saya ingin berkontribusi sesuai minat dan kemampuan.',
                'weakness_description' => 'Masih perlu meningkatkan manajemen prioritas.',
                'contribution_plan' => 'Aktif mengikuti program kerja dan menyelesaikan amanah.',
                'photo_path' => 'photos/' . $nim . '.jpg',
                'instagram_proof_path' => 'instagram_proofs/' . $nim . '.jpg',
                'youtube_proof_path' => 'youtube_proofs/' . $nim . '.jpg',
                'political_statement_path' => 'political_statements/' . $nim . '.pdf',
                'candidate_signature_path' => 'candidate_signatures/' . $nim . '.png',
                'parent_signature_path' => 'parent_signatures/' . $nim . '.png',
                'status' => 'scheduled',
            ]);

            $candidate->departmentChoices()->create([
                'departmentsbiro_id' => $departments[$firstIndex]->id,
                'choice_order' => 1,
            ]);
            $candidate->departmentChoices()->create([
                'departmentsbiro_id' => $departments[$secondIndex]->id,
                'choice_order' => 2,
            ]);

            CandidateEducation::create([
                'candidate_id' => $candidate->id,
                'education_type' => 'formal',
                'school_name' => 'SMK Placeholder',
                'start_year' => 2022,
                'end_year' => 2025,
                'city' => 'Depok',
                'major' => 'Teknik Komputer',
            ]);
            CandidateOrganization::create([
                'candidate_id' => $candidate->id,
                'organization_name' => 'OSIS Placeholder',
                'start_year' => 2023,
                'end_year' => 2024,
                'place_or_institution' => 'Sekolah',
                'position' => 'Anggota',
            ]);
            CandidateCommittee::create([
                'candidate_id' => $candidate->id,
                'committee_name' => 'Panitia Placeholder',
                'start_year' => 2024,
                'end_year' => 2024,
                'organizer' => 'Sekolah',
                'position' => 'Staff',
            ]);
            CandidateSkill::create([
                'candidate_id' => $candidate->id,
                'skill_type' => 'soft',
                'skill_name' => 'Communication',
                'proficiency' => 'sedang',
            ]);
            CandidateFacility::create([
                'candidate_id' => $candidate->id,
                'facility_name' => 'Laptop',
            ]);

            $schedule = InterviewSchedule::where('department_id', $departments[$firstIndex]->id)
                ->whereDoesntHave('booking')
                ->first();

            if ($schedule) {
                CandidateInterviewSchedule::create([
                    'candidate_id' => $candidate->id,
                    'interview_schedule_id' => $schedule->id,
                    'department_id' => $schedule->department_id,
                ]);
            }

            Announcement::create([
                'candidate_id' => $candidate->id,
                'status' => 'pending',
                'is_published' => false,
            ]);

            foreach ($departments[$firstIndex]->evaluationCriteria as $criteria) {
                Evaluation::create([
                    'candidate_id' => $candidate->id,
                    'department_id' => $departments[$firstIndex]->id,
                    'criteria_id' => $criteria->id,
                    'score' => rand(3, 5),
                    'interviewer_id' => $admin->id,
                    'version' => 1,
                ]);
            }
        }
    }
}
