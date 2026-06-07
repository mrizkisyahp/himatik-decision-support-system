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
use App\Models\DepartmentAgenda;
use App\Models\DepartmentWorkProgram;
use App\Models\Departmentsbiro;
use App\Models\Evaluation;
use App\Models\InterviewSchedule;
use App\Models\OpenRecruitment;
use App\Models\OpenRecruitmentQuota;
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
        ])->map(fn($row) => User::create([
                'name' => $row[0],
                'email' => $row[1],
                'password' => Hash::make('password123'),
                'role' => 'interviewer',
                'email_verified_at' => now(),
            ]));

        $departments = collect([
            ['Departemen Pendidikan dan Teknologi', 'Departemen ini berfokus pada kegiatan-kegiatan berkaitan dengan keilmuan dan utilisasi teknologi dalam lingkup jurusan TIK. Serta berperan penting dalam menjalankan visi misi untuk meningkatkan kualitas mahasiswa TIK baik secara akademik maupun non akademik.'],
            ['Departemen Kesehatan Mahasiswa', 'Departemen Kesehatan Mahasiswa atau Kesma di Jurusan Teknik Informatika dan Komputer (TIK) Kampus Politeknik Negeri Jakarta bertugas mengawasi dan bertanggung jawab atas kebersihan lingkungan serta kesehatan civitas internal di Jurusan TIK. Departemen Kesma aktif dalam menciptakan lingkungan internal TIK yang bersih dan sehat, serta menyelenggarakan kegiatan promosi kesehatan melalui konten digital.'],
            ['Biro Bendahara Umum', 'Biro Bendahara Umum merupakan biro yang bergerak dalam bidang keuangan. Biro Bendahara Umum bertugas untuk membuat pembukuan dan mengatur keuangan dalam HIMATIK.'],
            ['Departemen Komunikasi dan Informasi', 'Departemen Komunikasi dan Informasi, disingkat KOMINFO merupakan departemen yang memiliki banyak ranah terkait komunikasi dan informasi. Departemen Komunikasi dan Informasi bergerak dalam menjalin hubungan informasi yang baik dan bersinergi di dalam maupun di luar Jurusan TIK. Bertanggung jawab dalam mengkoordinir penyebaran serta menjaga kelancaran arus informasi kemahasiswaan kepada civitas akademika dalam rangka menunjang kegiatan HIMATIK.'],
            ['Departemen Bisnis dan Kemitraan', 'Departemen Bisnis dan Kemitraan adalah suatu departemen yang bergerak di bidang wirausaha, pendanaan, dan menyediakan keperluan logistik untuk Mahasiswa Jurusan TIK dan luar lingkup Jurusan TIK. Departemen Bisnis Dan Kemitraan harus menjadi wadah mahasiswa dalam berwirausaha dengan mengadakan kegiatan yang bersifat pembelajaran dan peningkatan Kewirausahaan bagi Mahasiswa TIK.'],
            ['Departemen Sosial Politik', 'Departemen Sosial Politik merupakan departemen yang dibentuk dengan tugas melakukan identifikasi dan bertanggung jawab terhadap berbagai isu-isu sosial dan politik yang berkembang baik dalam ruang lingkup internal Jurusan Teknik Informatika dan Komputer (TIK) kampus Politeknik Negeri Jakarta, maupun eksternal kampus Politeknik Negeri Jakarta.'],
            ['Biro Kesekretariatan', 'Biro Kestari adalah biro yang bertugas untuk mengurus seluruh kegiatan administrasi yang ada di HIMATIK.'],
            ['Biro Kreatif', 'Biro Kreatif HIMATIK adalah biro yang dibentuk untuk menangani segala permasalahan yang berkaitan dengan design publikasi dari internal HIMATIK, serta membantu merencanakan ide kreatif untuk suatu kegiatan atau program kerja HIMATIK.'],
            ['Departemen Kerohanian', 'Departemen Kerohanian merupakan departemen yang bertanggungjawab untuk memberikan ide, gagasan dan kemampuannya dalam bidang kerohanian berupa karya - karya dan agenda yang semuanya bertujuan untuk meningkatkan kualitas keimanan atau pengetahuan yang berhubungan dengan keagamaan setiap mahasiswa Jurusan Teknik Informatika dan Komputer. Selain itu, Departemen kerohanian juga bertanggungjawab dalam mengkoordinir kegiatan perayaan hari besar agama serta informasi mengenai pengetahuan agama.'],
            ['Departemen Sosial Mahasiswa', 'Departemen Sosial Mahasiswa (SOSMA) merupakan bagian dari Himpunan Mahasiswa Teknik Informatika dan Komputer (HIMATIK) yang bergerak di bidang advokasi dan semua kegiatan sosial kemahasiswaan Jurusan TIK.'],
            ['Biro Penelitian dan Pengembangan', 'Organ internal yang bertugas mengumpulkan, mengelola, dan menganalisis data untuk pengembangan HIMATIK. Berfungsi dalam pengembangan SDM serta menciptakan lingkungan kerja kekeluargaan dan rasa memiliki di HIMATIK PNJ.']
        ])->map(fn($row) => Departmentsbiro::create([
                'name' => $row[0],
                'slug' => Str::slug($row[0]),
                'description' => $row[1],
                'personal_aspect_weight' => 60.00,
                'organizational_aspect_weight' => 40.00,
                'core_factor_weight' => 60.00,
                'secondary_factor_weight' => 40.00,
                'is_active' => true,
            ]));

        $departmentPrograms = [
            'Departemen Pendidikan dan Teknologi' => ['ITechno Cup'],
            'Departemen Sosial Mahasiswa' => ['SAUM "Satu Aksi Untuk Masyarakat"'],
            'Departemen Kesehatan Mahasiswa' => ['TIKGAMES'],
        ];

        $departmentAgendas = [
            'Biro Kreatif' => [
                'Pengambilan Foto Fungsionaris HIMATIK Yugartha',
                'Pembuatan design feeds Fungsionaris HIMATIK Yugartha',
                'Pembuatan Lanyard dan ID Card HIMATIK Yugartha',
                'Produksi Company Profile',
            ],
            'Departemen Kerohanian' => [
                'Keroh Berbagi',
                'Amal Mahasiswa',
                'Positif dan Postline',
                'Pray My Habit',
                'Buka PR',
            ],
            'Departemen Bisnis dan Kemitraan' => [
                'Bismit Inventory',
                'Barnaby',
                'Bismit Info',
                'SANDI (Sayembara Design)',
            ],
            'Departemen Pendidikan dan Teknologi' => [
                'GULTIK (Gudang Ilmu TIK)',
                'APRESMAPRES (Apresiasi Mahasiswa Berprestasi)',
                'LOGISTIK (Log Informasi TIK)',
                'KSM Meet Up',
            ],
            'Biro Bendahara Umum' => [
                'Laporan Transparansi',
                'Pembukuan Kas',
                'Laporan Keuangan Departmen/Biro',
                'Pencatatan Buku Besar',
                'LPJ Keuangan Akhir',
            ],
            'Departemen Sosial Mahasiswa' => [
                'Apresiatik',
                'Dialog Jurusan',
                'Wematik',
                'SOSMATALK',
                'Lentik',
            ],
            'Departemen Komunikasi dan Informasi' => [
                'Motor Vario (Motivation Word and Various Information)',
                'Podcast',
                'Cantik (Cerita Anak TIK)',
                'FKJ (Forum Komunikasi Jurusan)',
                'WEMATIK (Welcoming Mahasiswa Baru TIK)',
            ],
            'Biro Penelitian dan Pengembangan' => [
                'Staff of The Month',
                'Laporan Organisasi dan Kegiatan HIMATIK',
                'Studi Banding',
                'Training of Trainers',
                'Pelatihan Manajemen Organisasi',
                'Forum Departemen dan biro',
            ],
            'Biro Kesekretariatan' => [
                'Bedah Sekret',
                'Bedah Loker',
                'Open Source',
            ],
            'Departemen Sosial Politik' => [
                'Dialog Jurusan',
                'Sempol (Semua Paham Politik)',
                'Makasi (Manajemen Massa Aksi)',
                'Roadshow',
            ],
            'Departemen Kesehatan Mahasiswa' => [
                'SariroTIK',
                'SehArt',
                'SporTIK',
                'TIK Football League (TFL)',
            ],
        ];

        foreach ($departments as $department) {
            foreach ($departmentPrograms[$department->name] ?? [] as $index => $programName) {
                DepartmentWorkProgram::create([
                    'department_id' => $department->id,
                    'name' => $programName,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }

            foreach ($departmentAgendas[$department->name] ?? [] as $index => $agendaTitle) {
                DepartmentAgenda::create([
                    'department_id' => $department->id,
                    'title' => $agendaTitle,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        $defaultCriteria = collect(SpkCriteriaDefaults::criteria())->map(fn($row, $index) => DefaultEvaluationCriteria::create([
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

        $openRecruitments = collect([
            'staff' => OpenRecruitment::create([
                'candidate_type' => 'staff',
                'starts_at' => now()->subDay()->setTime(8, 0),
                'ends_at' => now()->addDays(14)->setTime(23, 59),
                'status' => 'open',
            ]),
            'bph' => OpenRecruitment::create([
                'candidate_type' => 'bph',
                'starts_at' => now()->subDay()->setTime(8, 0),
                'ends_at' => now()->addDays(10)->setTime(23, 59),
                'status' => 'open',
            ]),
        ]);

        foreach ($openRecruitments->keys() as $type) {
            foreach ($departments as $department) {
                OpenRecruitmentQuota::create([
                    'candidate_type' => $type,
                    'department_id' => $department->id,
                    'quota' => $type === 'staff' ? 5 : 2,
                ]);
            }
        }

        $now = Carbon::now()->addDays(2)->setTime(9, 0);
        foreach ($departments as $index => $department) {
            $schedule = InterviewSchedule::create([
                'department_id' => $department->id,
                'date' => $now->copy()->toDateString(),
                'start_time' => $now->copy()->addHours($index % 5)->toTimeString(),
                'end_time' => $now->copy()->addHours(($index % 5) + 1)->toTimeString(),
                'is_blocked' => false,
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
                'nickname' => strtok($name, ' '),
                'nim' => $nim,
                'prodi' => str_starts_with($kelas, 'TMJ') ? 'Teknik Multimedia dan Jaringan' : (str_starts_with($kelas, 'TMD') ? 'Teknik Multimedia dan Digital' : 'Teknik Informatika'),
                'kelas' => $kelas,
                'phone' => '0812345678' . $index,
                'address' => 'Alamat placeholder ' . $name,
            ]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => 'staff',
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
