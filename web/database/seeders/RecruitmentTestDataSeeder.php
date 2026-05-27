<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RecruitmentTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admins & Interviewers
        $admin = User::create([
            'name' => 'Admin Himatik',
            'email' => 'admin@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        $interviewer1 = User::create([
            'name' => 'Falih',
            'email' => 'diktek@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer2 = User::create([
            'name' => 'Owen',
            'email' => 'kominfo@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer3 = User::create([
            'name' => 'Alif',
            'email' => 'sosma@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer4 = User::create([
            'name' => 'Daffa',
            'email' => 'kreatif@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer5 = User::create([
            'name' => 'Dandy',
            'email' => 'kestari@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer6 = User::create([
            'name' => 'Doni',
            'email' => 'sospol@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer7 = User::create([
            'name' => 'Hardimas',
            'email' => 'bismit@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer8 = User::create([
            'name' => 'Wafana',
            'email' => 'kabirlitbang@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer9 = User::create([
            'name' => 'Rania',
            'email' => 'bendum@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer10 = User::create([
            'name' => 'Zaldi',
            'email' => 'kesma@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer11 = User::create([
            'name' => 'Rizki',
            'email' => 'keroh@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer12 = User::create([
            'name' => 'Dhafin',
            'email' => 'litbangkeroh@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer13 = User::create([
            'name' => 'Adelio',
            'email' => 'litbangkreatif@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer14 = User::create([
            'name' => 'Boy',
            'email' => 'litbangkominfo@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer15 = User::create([
            'name' => 'Farrel',
            'email' => 'litbangdiktek@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer16 = User::create([
            'name' => 'Abdillah',
            'email' => 'litbangsosma@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer17 = User::create([
            'name' => 'Iqbal',
            'email' => 'litbangbendum@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer18 = User::create([
            'name' => 'Monaning',
            'email' => 'litbangkestari@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer19 = User::create([
            'name' => 'Forza',
            'email' => 'litbangsospol@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer20 = User::create([
            'name' => 'Dea',
            'email' => 'litbangkesma@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        $interviewer21 = User::create([
            'name' => 'Farrel',
            'email' => 'litbangbismit@himatik.org',
            'password' => Hash::make('password123'),
            'role' => 'interviewer',
        ]);
        // 2. Seed Departments
        $diktek = Departmentsbiro::create([
            'name' => 'Departemen Pendidikan dan Teknologi',
            'description' => 'Departemen ini berfokus pada kegiatan-kegiatan berkaitan dengan keilmuan dan utilisasi teknologi dalam lingkup jurusan TIK. Serta berperan penting dalam menjalankan visi misi untuk meningkatkan kualitas mahasiswa TIK baik secara akademik maupun non akademik',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $litbang = Departmentsbiro::create([
            'name' => 'Biro Penelitian dan Pengembangan',
            'description' => 'Organ internal yang bertugas mengumpulkan, mengelola, dan menganalisis data untuk pengembangan HIMATIK. Berfungsi dalam pengembangan SDM serta menciptakan lingkungan kerja kekeluargaan dan rasa memiliki di HIMATIK PNJ.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $kesma = Departmentsbiro::create([
            'name' => 'Departemen Kesehatan Mahasiswa',
            'description' => 'Departemen Kesehatan Mahasiswa atau Kesma di Jurusan Teknik Informatika dan Komputer (TIK) Kampus Politeknik Negeri Jakarta bertugas mengawasi dan bertanggung jawab atas kebersihan lingkungan serta kesehatan civitas internal di Jurusan TIK. Departemen Kesma aktif dalam menciptakan lingkungan internal TIK yang bersih dan sehat, serta menyelenggarakan kegiatan promosi kesehatan melalui konten digital.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $bendum = Departmentsbiro::create([
            'name' => 'Biro Bendahara Umum',
            'description' => 'Biro Bendahara Umum merupakan biro yang bergerak dalam bidang keuangan. Biro Bendahara Umum bertugas untuk membuat pembukuan dan mengatur keuangan dalam HIMATIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $kominfo = Departmentsbiro::create([
            'name' => 'Departemen Komunikasi dan Informasi',
            'description' => 'Departemen Komunikasi dan Informasi, disingkat KOMINFO merupakan departemen yang memiliki banyak ranah terkait komunikasi dan informasi. Departemen Komunikasi dan Informasi bergerak dalam menjalin hubungan informasi yang baik dan bersinergi di dalam maupun di luar Jurusan TIK. Bertanggung jawab dalam mengkoordinir penyebaran serta menjaga kelancaran arus informasi kemahasiswaan kepada civitas akademika dalam rangka menunjang kegiatan HIMATIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $bismit = Departmentsbiro::create([
            'name' => 'Departemen Bisnis dan Kemitraan',
            'description' => 'Departemen Bisnis dan Kemitraan adalah suatu departemen yang bergerak di bidang wirausaha, pendanaan, dan menyediakan keperluan logistik untuk Mahasiswa Jurusan TIK dan luar lingkup Jurusan TIK. Departemen Bisnis Dan Kemitraan harus menjadi wadah mahasiswa dalam berwirausaha dengan mengadakan kegiatan yang bersifat pembelajaran dan peningkatan Kewirausahaan bagi Mahasiswa TIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $sospol = Departmentsbiro::create([
            'name' => 'Departemen Sosial Politik',
            'description' => 'Departemen Sosial Politik merupakan departemen yang dibentuk dengan tugas melakukan identifikasi dan bertanggung jawab terhadap berbagai isu-isu sosial dan politik yang berkembang baik dalam ruang lingkup internal Jurusan Teknik Informatika dan Komputer (TIK) kampus Politeknik Negeri Jakarta, maupun eksternal kampus Politeknik Negeri Jakarta.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $kestari = Departmentsbiro::create([
            'name' => 'Biro Kesekretariatan',
            'description' => 'Biro Kestari adalah biro yang bertugas untuk mengurus seluruh kegiatan administrasi yang ada di HIMATIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $kreatif = Departmentsbiro::create([
            'name' => 'Biro Kreatif',
            'description' => 'Biro Kreatif HIMATIK adalah biro yang dibentuk untuk menangani segala permasalahan yang berkaitan dengan design publikasi dari internal HIMATIK, serta membantu merencanakan ide kreatif untuk suatu kegiatan atau program kerja HIMATIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $keroh = Departmentsbiro::create([
            'name' => 'Departemen Kerohanian',
            'description' => 'Departemen Kerohanian merupakan departemen yang bertanggungjawab untuk memberikan ide, gagasan dan kemampuannya dalam bidang kerohanian berupa karya - karya dan agenda yang semuanya bertujuan untuk meningkatkan kualitas keimanan atau pengetahuan yang berhubungan dengan keagamaan setiap mahasiswa Jurusan Teknik Informatika dan Komputer. Selain itu, Departemen kerohanian juga bertanggungjawab dalam mengkoordinir kegiatan perayaan hari besar agama serta informasi mengenai pengetahuan agama.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);
        $sosma = Departmentsbiro::create([
            'name' => 'Departemen Sosial Mahasiswa',
            'description' => 'Departemen Sosial Mahasiswa (SOSMA) merupakan bagian dari Himpunan Mahasiswa Teknik Informatika dan Komputer (HIMATIK) yang bergerak di bidang advokasi dan semua kegiatan sosial kemahasiswaan Jurusan TIK.',
            'core_factor_weight' => 0.60,
            'secondary_factor_weight' => 0.40,
        ]);

        // 3. Define the exact 8 default criteria to be applied to all departments
        $defaultCriteria = [
            ['name' => 'Pengetahuan Organisasi', 'type' => 'core', 'target_score' => 4],
            ['name' => 'Pengalaman Organisasi/Kepanitiaan', 'type' => 'core', 'target_score' => 4],
            ['name' => 'Manajemen Waktu', 'type' => 'core', 'target_score' => 3],
            ['name' => 'Kepercayaan diri + Komunikasi', 'type' => 'secondary', 'target_score' => 3],
            ['name' => 'Problem Solve', 'type' => 'core', 'target_score' => 4],
            ['name' => 'Konsistensi Jawaban', 'type' => 'secondary', 'target_score' => 4],
            ['name' => 'Komitmen', 'type' => 'secondary', 'target_score' => 4],
            ['name' => 'Kesibukan', 'type' => 'secondary', 'target_score' => 3],
        ];

        // Group all 11 departments into an array
        $departments = [$diktek, $litbang, $kesma, $bendum, $kominfo, $bismit, $sospol, $kestari, $kreatif, $keroh, $sosma];
        // Seed the 8 criteria dynamically for every single department!
        foreach ($departments as $dept) {
            foreach ($defaultCriteria as $c) {
                $dept->evaluationCriteria()->create($c);
            }
        }

        // 4. Seed Candidates & User Accounts
        // 4. Raw TSV Data of all candidates pasted
        $tsvData = <<<TSV
01/02/2026 22:49:05	rafifmughni01@gmail.com	Muhammad Rafif Mughni	2507411075	Teknik Informatika TI - 2B	081291624858	Biro Kreatif	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=15fU8PItqiM29PqV2vVNZjd6UsbsAOh9a	https://drive.google.com/open?id=15gT0grz1b0P4Oh-cJVwnvotvvTJ9bGhv	https://drive.google.com/open?id=1N2D8jmTM0bZrrxGd1NXLEF6x2-EFAfBn	https://drive.google.com/open?id=1-iBOFp7eHfxJBl2aiW8aXl9pIjctfBC0
29/01/2026 10:22:30	syariflazuardi02@gmail.com	Syarif Lazuardi	2507411081	TI 1B	087885173536	Departemen Sosial Politik	Departemen Sosial Politik	https://drive.google.com/open?id=1BTUH9Ioxs4y_uCciNjHWzZ4D0BmxOj_s	https://drive.google.com/open?id=1GApCsh8-NSmuo5Tfl9tEXxXFPCslgjPX	https://drive.google.com/open?id=19wWD1Oia-s6OS51SnEBLooWbyy1i25r4	https://drive.google.com/open?id=1509UksxvpTjBd0zW6a1THFKzWpVasCPo
28/01/2026 23:50:49	naylajulita1@gmail.com	Nayla Julita	26507411091	TI 1C	085213612620	Departemen Kesehatan Mahasiswa	Departemen Pendidikan dan Teknologi	https://drive.google.com/open?id=1J1CeQejEhLraqZUAbC8RMAJbE4c7OkJ3	https://drive.google.com/open?id=1XC6BJhhzqRQR2lLbyUQd5oPw3kVzm1pd	https://drive.google.com/open?id=1hXLof2j4lLccmRa3WqlTcm4o-Dc8fbgA	https://drive.google.com/open?id=1FOHYVXjyH9F5azoO-jHc6v-q0ncbwj5X
24/01/2026 11:22:45	sakha00119@gmail.com	Sakha Ibadil Kirom	2507411064	TI 2A	081383811090	Departemen Pendidikan dan Teknologi	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1ta3mswC7jOSdbMIqzcqWtEU0x3iEyOF3	https://drive.google.com/open?id=1_a8_NK8rhZdBgRjnD51gWcujlpQu7yfA	https://drive.google.com/open?id=1wEcFoYnSImdF_0cCpixMRfBm8yKHFKuM	https://drive.google.com/open?id=1p61c3DVHloUvwNPDSVqOWiibmKrTReMY
28/01/2026 20:21:42	aryo.bagas.sasongko.tik25@stu.pnj.ac.id	Aryo Bagas Sasongko	2507411002	TI 2A	089654316536	Departemen Pendidikan dan Teknologi	Departemen Pendidikan dan Teknologi	https://drive.google.com/open?id=1CbdBbw787Ph4iCcMuXZ6NF12ZrbZ6WyP	https://drive.google.com/open?id=1K4ChubXGkxVTt2YpbP-hBQl3tbqcyz0m	https://drive.google.com/open?id=1V9DcPwzFWQj0uek-82ahyXRssUozxiRM	https://drive.google.com/open?id=1VK4OMSPX5Cjx9I1C1mC_6_B2DMN4o-N5
29/01/2026 17:53:10	syifa.nur.jauzah.tik25@stu.pnj.ac.id	Syifa Nur Jauzah	2507411041	TI 2A	089514456843	Departemen Pendidikan dan Teknologi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1lJvg7sSnkzVh8C_EvSRSzY0_4ndo1yib	https://drive.google.com/open?id=19j_C8EGuIIKmIF83Vz65JxUT3ktSHaUg	https://drive.google.com/open?id=1TeD2ZiR30ay1ZCJtSf-eNfhMCeTGtLfH	https://drive.google.com/open?id=1hCDa-bPe6R0fxuHiCqGF2mYH1mVZ8L_b
29/01/2026 14:32:25	muhammad.jeffri.tik25@stu.pnj.ac.id	Muhammad Jeffri	2507411001	TI 2A	085748516854	Departemen Sosial Politik	Departemen Sosial Politik	https://drive.google.com/open?id=19jq3R4EdzP4c5KmF0Y9AVRtcWpX1ehY3	https://drive.google.com/open?id=1KTC0ks1z57YUZ_-F9OF3eHUXMstTrEHm	https://drive.google.com/open?id=1S1gRy37vFMsTpyD8-RAC6Gfupa8RY1Au	https://drive.google.com/open?id=10qDG0q_Y1t677eQ9U8HLTr-VP8fjC1C_
26/01/2026 18:33:14	syaputraferdian0@gmail.com	Ferdian Syaputra	2507411070	TI 2A 	087852408322	Departemen Komunikasi dan Informasi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1wmLSJxbs2dXrd7lvlag_ReG8Oad5YRSd	https://drive.google.com/open?id=1QA3DtfdF6kq4H-yYh8SeTxWWVRhZTFc6	https://drive.google.com/open?id=1IzKq9GWtGFwm7i7ODsL90UJuQkBxFzMp	https://drive.google.com/open?id=1HDRXi319hIMinvP20X1GdOGN-PaVIe0x
08/02/2026 22:22:15	arjuna.widyadhana.tik25@stu.pnj.ac.id	Arjuna Widyadhana	2507411045	TI 2B	08131094495	Departemen Bisnis dan Kemitraan	Departemen Bisnis dan Kemitraan	https://drive.google.com/open?id=19Fi4xa65he40Uh0t74ryLaIt2a82_YLa	https://drive.google.com/open?id=1HRbm_rO0oyVYPa2LLmdrOezMVa9cynJL	https://drive.google.com/open?id=16WE1yAf4Fsw9eISJQylrQt4zgFKEnPv1	https://drive.google.com/open?id=1e6GM-U5ZegdjeutbbPu9aZ0TN0MfPcoX
29/01/2026 13:47:43	dilosyfarrell@gmail.com	Farrell Dilosy	2507411073	TI 2B	088294523788	Departemen Sosial Politik	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=12-UzR8tpFEkaLKFVKUEsYgzVl13wuf0_	https://drive.google.com/open?id=1Kluu25ZhvsnTjG20dsopNGT8H4dUcNcv	https://drive.google.com/open?id=16wX0YCojXkqhh9LLWgRzTNlR9ZzHLYgc	https://drive.google.com/open?id=1OkZLLy8mrnCODOe_-fmDNZ8sFjXT761r
27/01/2026 23:08:56	mynameisfatah347@gmail.com	Ahmad Fattah	2507411025	TI 2C	081286207949	Biro Kesekretariatan	Biro Kesekretariatan	https://drive.google.com/open?id=1vcHEglGvi_OKpjWdFlAS66DcMirN3U2y	https://drive.google.com/open?id=1ihvtTnKSHHfQPFqJYS-7vwiaH0aYtqe8	https://drive.google.com/open?id=1gX5YiPXL8hMkmxdnmxip8tUcvVozT4q_	https://drive.google.com/open?id=13tLDiAnBdU5PSSKIr4K50TC4cXwKvEg6
09/02/2026 22:08:10	dhafinazka101106@gmail.com	Dhafin Azka Permana	2507411084	TI 2C	081288487989	Departemen Kesehatan Mahasiswa	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1ryKtRt0KvW4OygbzSY8blXGmieUH6m2m	https://drive.google.com/open?id=1KGNx6Mnz-wfaAuPeCS7WKcDxeMLHw5LW	https://drive.google.com/open?id=1OTfqWOZz4xFF4sJ8jtWlsTlhQez3tbN-	https://drive.google.com/open?id=1ILR5d9lS8dh_5r6GZqw0tXdWCzhW0Nxn
27/01/2026 23:41:02	deni121006@gmail.com	Deni Dwi Prasetya	2507411026	TI 2C	081389039497	Departemen Pendidikan dan Teknologi	Departemen Pendidikan dan Teknologi	https://drive.google.com/open?id=15tS3iWEbtgfVOA-jmVZs4w_fEKsb_XaW	https://drive.google.com/open?id=1pmbeHmM4EEPEXmI8ZCTsGGHhnRS8uD9m	https://drive.google.com/open?id=12oep2XFTaQx--cFBCTjPPT3uqaa-S63O	https://drive.google.com/open?id=14jAmATiFJ50e-q-V5xChdfQVM4YHyp4b
26/01/2026 20:36:28	andrian.dwiputra.wibowo.tik25@stu.pnj.ac.id	Andrian Dwiputra Wibowo	2507411003	TI-2A	085218944097	Departemen Komunikasi dan Informasi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1J5mOi0iZsZyidTHrY4Is_ThkhskwAZpd	https://drive.google.com/open?id=1lJXbgYcBugwuXFXulLAnEH4f0Rgz0pw8	https://drive.google.com/open?id=15gQ8dlW_C7Qpnb1ZZMDZHfS9SwGpoE5a	https://drive.google.com/open?id=1qP7Q03Z1S3e1LmEB0v2d5o4Twqj2p3Sv
28/01/2026 11:48:07	rafialdo.putra.affandi.tik25@stu.pnj.ac.id	Rafialdo Putra Affandi	2507431002	TMD 1A	085959022700	Biro Kreatif	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1ysyQkCtVBEFYl7sKcPtf_5jliyOV0oHJ	https://drive.google.com/open?id=177oWJW9sFpDHOBj4X4W7zzzb0GQ7zT2C	https://drive.google.com/open?id=1Zfkjaluy2fd8N4wuHUnignxcsS8FHES8	https://drive.google.com/open?id=1uIwC7x0gWMUCFxqiFWIJEuqFFMCcRr_0
29/01/2026 06:20:31	rayhan.adi.saputra.tik25@stu.pnj.ac.id	Rayhan Adi Saputra	2507431036	TMD 1A	085880547655	Biro Kreatif	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1FqQgCebdbtwz8YZ1p20lOUa-4tkdveoi	https://drive.google.com/open?id=14AQhLfhyv3pzupluHYvvzOX4NfbFdM2L	https://drive.google.com/open?id=1ei4ra9wMtwlggGve_o0f0s_t9ijbFpmj	https://drive.google.com/open?id=1YQ4iIkjz-Yavbv7ygPzEe_H_-ETFVggS
29/01/2026 11:43:07	rafialdoaffandi@gmail.com	Rafialdo Putra Affandi	2507431002	TMD 1A	085959022700	Biro Kreatif	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1KUWExa1DjNQeP9RYROhEa5o4NzBE3DA5	https://drive.google.com/open?id=1a5ydvOVceWQeAiolWqDdgpYy1hdHT2Qp	https://drive.google.com/open?id=1KXrGK7_mdaL_vKfuViiZbQVYr6jWaqgx	https://drive.google.com/open?id=1tN8ULu33kSYDDzLuvFCshC5qT8HtP3IG
27/01/2026 20:20:06	aline.lakhiza.indraswara.tik25@stu.pnj.ac.id	Aline Lakhiza Indraswara	2507431011	TMD 1B	089654748184	Departemen Kesehatan Mahasiswa	Biro Kreatif	https://drive.google.com/open?id=1_FTZSZ0-B9RveGnLQ321O4g0mgLosWVE	https://drive.google.com/open?id=1yRzaan-BdCFG5k18qPzOBa1vsVTWxfI-	https://drive.google.com/open?id=1PpOe5nL2UOCOh4r-zhSrEr3XRQhN1g8b	https://drive.google.com/open?id=1JAkT6L3ojHvUkGRpZoZEZZQc5N5voS10
24/01/2026 00:12:55	kevin.ekarevano.tik25@stu.pnj.ac.id	Kevin Ekarevano	2507431005	TMD 2A	081211545130	Biro Kreatif	Biro Kreatif	https://drive.google.com/open?id=1_wYt8H1p764Exdmnt8J0k7PPFroh0p7b	https://drive.google.com/open?id=16EfJ9qRe4ROrx_BbHPU2EGkZDbln2NE4	https://drive.google.com/open?id=1KCpHN4azmgsYjpFL2ntjTUBby8kducc9	https://drive.google.com/open?id=1ItBGWjIvWC1f0g9IzJLuOVNI8E5_eDzR
26/01/2026 18:27:46	abid.faturrahman.alfath.tik25@stu.pnj.ac.id	Abid Faturrahman Al-Fath	2507431040	TMD 2A	087847565771	Biro Kreatif	Biro Kreatif	https://drive.google.com/open?id=1lMrQ7epufVdfx5ooFPSbq5kkiEHhbYJp	https://drive.google.com/open?id=1Hf3Miiw-1zWB6HEU3lId9OxYiyX3PRVm	https://drive.google.com/open?id=1GnZDjjhCDD4ML3iKV71a2_9mJnd7J4YZ	https://drive.google.com/open?id=1laxl3OirBRNigBZaMwqVV4OH-0GwvvfK
26/01/2026 21:57:02	millkymayo@gmail.com	Nizar Rizki Ardiansyah	2507431029	TMD 2A	089663330277	Biro Kreatif	Biro Kreatif	https://drive.google.com/open?id=13FIjULqkru7KE2V76Otp-N2SSkbYXMDg	https://drive.google.com/open?id=1DrTS7uEwR2UdaQobHOBgqp5kZQDJ3nGD	https://drive.google.com/open?id=1KRvevm_hJs_RqquOK3AgAEXezpSu8dAU	https://drive.google.com/open?id=1OaTdAIBrTdlClhDBC53KUvwWIx4o0Q9W
27/01/2026 13:16:41	millkymayo@gmail.com	Nizar Rizki Ardiansyah	2507431029	TMD 2A	089663330277	Biro Kreatif	Biro Kreatif	https://drive.google.com/open?id=1Dcx4Ha7bgNikXPiCfc0SE5IN_E-Y2cZR	https://drive.google.com/open?id=1TLDdt56PxZEFYyuVjNHNflu-6oSkZ_8Z	https://drive.google.com/open?id=13PAzIDHtLIojMtuJxpVICpQ1TTd39LPF	https://drive.google.com/open?id=1bKwIW8iR8rZN49gZpKdxXs7cN6kWBgdd
28/01/2026 20:03:17	tobardy30@gmail.com	Akbar Dyastoro	2507431030	TMD 2A	089603794161	Biro Kreatif	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=1-9wKJCtHgH28c4WL3UK3hqBFmfnII_Gm	https://drive.google.com/open?id=1_2AMYAo5LHGoInYF9LSr5ZiTvajVV6Te	https://drive.google.com/open?id=1rL7q8TxR4eJHA1TrpdPoj-7G_zea220N	https://drive.google.com/open?id=1d9w0swq7nb_ZzJW5mw46bmNjK6LiOZED
08/02/2026 22:25:29	bayhaki953@gmail.com	Ahmad Baihaqi	2507431028	TMD 2A	085817921655	Departemen Kesehatan Mahasiswa	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=15F9bMbai67dI_oCTaGN3ZpKq5_RNMhqp	https://drive.google.com/open?id=1jtCtmXv6lKRhW1w9miW5i7e7gNg4pdNJ	https://drive.google.com/open?id=18b0QDDlJ2U1XACMzRVcwQQ7aRaFR1vbN	https://drive.google.com/open?id=1t91t-foVkDq4WuJFCu0vKwxlt-jGMHce
26/01/2026 13:56:54	muhammad.akbar.hawary.tik25@stu.pnj.ac.id	Muhammad Akbar Hawary	2507431076	TMD 2A	081313961917	Departemen Sosial Mahasiswa	Departemen Kerohanian	https://drive.google.com/open?id=1o_Ze1CWzVlD7gmulJ0TqnTqU2wRQu18e	https://drive.google.com/open?id=1LBeJ_7H0w29Djf4cM4RkDzazq1uu0rHv	https://drive.google.com/open?id=1y5ONjKXBmuZ57pHeWv6MqNKxHrFafA6e	https://drive.google.com/open?id=18GDBYZm8sCzE5SSAsYtwa21ySd0PZS3Q
29/01/2026 00:10:40	muhammad.luthfi.setiawan.tik25@stu.pnj.id	Muhammad Luthfi Setiawan	2507434018	TMD 2A CCIT	087836889257	Departemen Sosial Politik	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1aGYkWSFzTsgmeVKWz0EkBIMmUSpmm4eT	https://drive.google.com/open?id=15O874N8g7tmSBEjEbeDwX7aN9UQRYtzi	https://drive.google.com/open?id=1LXxp-kBODhJx6EjryZ1pTugkDfSoNvpw	https://drive.google.com/open?id=1kqbfyI-6oMaMQpCoPpKICg7PZR0D51Zo
29/01/2026 23:08:10	satrio.eko.saputra.tik25@stu.pnj.ac.id	SATRIO EKO SAPUTRA	2507431082	TMD 2B	089618379746	Departemen Bisnis dan Kemitraan	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1OmQipdWjiT2W9AQW18mUbR4By7VmPLa2	https://drive.google.com/open?id=1lfWYRIeMf5FQGGDHpi0pTfNn2irCxYEx	https://drive.google.com/open?id=1N9hAPoWG4Kez8eEfe9U_ngjFVqc-avVG	https://drive.google.com/open?id=1d58qS_fRtXtW0jnMisUxANcbED9ehKIm
28/01/2026 15:56:26	nabilaaprily74@gmail.com	Nabila apriliyani	2507431010	TMD 2B	081292576334	Departemen Pendidikan dan Teknologi	Biro Kreatif	https://drive.google.com/open?id=1yGpGDeUdu6ec4edMlEF6dIO3nBwjV_eM	https://drive.google.com/open?id=1ocyOWmmzoUXcDJMdArenSjveCMdDiu2j	https://drive.google.com/open?id=1dFs7tSLUQmHaj1VBA2OoofJ0W78eg9SH	https://drive.google.com/open?id=1z3PgN81ij1y_9qERxNImQaCljOrZZSon
29/01/2026 22:26:37	azzahrarrr9@gmail.com	Azzahra Ramadhani	2507431080	TMD 2B	089614222784	Departemen Sosial Mahasiswa	Departemen Pendidikan dan Teknologi	https://drive.google.com/open?id=1oUeclL2mEIEtaH305Z9KPxUpEOEYAy3H	https://drive.google.com/open?id=1NhKS02ThShKpwNnJEPea96d0jGL8pv2z	https://drive.google.com/open?id=1UzOBODOb_Un2iacYMtIwuN1uP6GWJMf6	https://drive.google.com/open?id=19UtFGU1TJd844NLt9zhcF7Npu8fQePPq
29/01/2026 21:25:57	sekar.lintang.tik25@stu.pnj.ac.id	Sekar Lintang	2507431088	TMD 2C	081382036001	Biro Bendahara Umum	Departemen Bisnis dan Kemitraan	https://drive.google.com/open?id=1Ylp5kIVGPuTiQKjcRCe2eFfSNAwxnjuf	https://drive.google.com/open?id=1mRDJe06nxanAY5Advqa0GShVER0pEO6j	https://drive.google.com/open?id=1n7Fu_EOOUQn-wq09hLJk1f5HuTmXrn4p	https://drive.google.com/open?id=1aw7UaImsc0rU4Ovzq__-r_hxeu08HN3a
27/01/2026 20:05:05	naailah.risqullah.tik25@stu.pnj.ac.id	Naa'ilah Risqullah	2507431061	TMD 2C	081549440405	Biro Kesekretariatan	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=1drN7CMKf9dUKmcIoKs57hDeTDkb-Mro6	https://drive.google.com/open?id=1RR2di8-u7xw0_APy_5vACHF8wV-tJDqF	https://drive.google.com/open?id=1fqJkctTZPkFJAI35qPQj_ckKGxQjhISk	https://drive.google.com/open?id=1gcGiedwJXxIZJyNox9kSRVTxNZ9DOtZy
29/01/2026 22:32:16	citraaaa148@gmail.com	Citra Ramadhani	2507431023	TMD 2C	082114702675	Biro Kreatif	Departemen Bisnis dan Kemitraan	https://drive.google.com/open?id=1Z64NPGSIduWg-E-MkLp_TZWfNf84a_8y	https://drive.google.com/open?id=1UR_ktDEnsgMAKDBT_ak2fzR3VOHzxId1	https://drive.google.com/open?id=1pipPgeAJMf4rDJT55VCY-ASSHx2UI2KU	https://drive.google.com/open?id=1LCivKQnmNEBi-ZuazrAoJC4LyrRv0DBw
29/01/2026 23:32:12	muhammad.alif.rakasya.tik25@stu.pnj.ac.id	Muhammad Alif Rakasya	2507431025	TMD 2C	081292979772	Biro Kreatif	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=19dz-n2g-M9uqhfX4HXk-aAC85aL0dBiC	https://drive.google.com/open?id=1D9iNv2bJsecOzf3oJbmZKXorJizvdFT-	https://drive.google.com/open?id=1d608MFuj2PHvt_Rw6ecVM3O-uR1lLlYS	https://drive.google.com/open?id=1j0eRE1tJjCBrrV9VSZBbrrQMgqD9AYjH
28/01/2026 13:54:44	dwi.aliyah.sp@gmail.com	Dwi Aliyah Sumari Putri 	2507431024	TMD 2C	081295870992	Departemen Komunikasi dan Informasi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1tcamSnfins0UljqLTJCyRZqKWLIr2pG7	https://drive.google.com/open?id=1CD6PEmMJk6AzDO6RJiPAfMmDv07vHUGZ	https://drive.google.com/open?id=1TpWydWRp48SHQucKnBmzmtcYiMHGndvq	https://drive.google.com/open?id=1eut2Uou1ym_j4TKNycplRnl9gjCf6hiO
29/01/2026 13:27:36	ridhovarya1@gmail.com	Ridhova Arya Falah	2507431065	TMD 2C	083805265008	Departemen Komunikasi dan Informasi	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=19ELeZOW00Qtyelj7bOzj1XMCoN9M8Oug	https://drive.google.com/open?id=1U9GWEQmas5jtbtkf_-mvXeoanNLaJjXL	https://drive.google.com/open?id=16rNECjCFQVEQKgqJvjgCDLShrWI9JB6F	https://drive.google.com/open?id=15n4VT31aQbIs419LA-sZiJu5mU872Xfh
29/01/2026 21:44:02	karina.chika.wulandari.tik25@stu.pnj.ac.id	Karina Chika Wulandari	2507431020	TMD 2C	087898292960	Departemen Komunikasi dan Informasi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1FiaM0oSGN12peJPF8VCCeBGgocAA2flH	https://drive.google.com/open?id=1dQlmwLyT1_BLTFDZEdmG9XYtYKTuHLBb	https://drive.google.com/open?id=1N7OP6926Gzfzkl7DkWRBqDDe4xMZxb7f	https://drive.google.com/open?id=1rM8W6ru5AKuFBm2kHx4VkbMxnnTE_kNW
29/01/2026 18:36:26	putri.anisa.la.ananila.tik25@stu.pnj.ac.id	Putri Anisa La Ananila	2507431067	TMD 2C	081221715429	Departemen Pendidikan dan Teknologi	Biro Kreatif	https://drive.google.com/open?id=1P7w55UIfCrFbpX8YbnKb7WNSFNbG_Oqm	https://drive.google.com/open?id=1L4FCDAmrBDPuMogYEcWSFuKTQQxGp742	https://drive.google.com/open?id=1oLxrFgXqj8qhemxBdGh0wEGn2PwVI1e6	https://drive.google.com/open?id=1eemtlfyFNh-UhvZNXYdw4SLRyfuXGhME
28/01/2026 20:02:25	sofizahra2607@gmail.com	Shafia Rahmania	2507431081	TMD 2C	088905822145	Departemen Sosial Mahasiswa	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=1pf7tRfh2adPqgrA9hfVg8wH7M_X86gAB	https://drive.google.com/open?id=1t665K6HXjYEAaZ9Fh49E8geUjpT8pkMd	https://drive.google.com/open?id=15ffaRRkUbj_iZ0ATZoMm-izkMP9h0c9i	https://drive.google.com/open?id=1fmoz_zCdlNa06tU7LfynpbZTC3U0ZqHQ
28/01/2026 19:08:06	bbgasn5p@gmail.com	Bagas Novianto Pangaribuan	2507431060	TMD 2C	082123727370	Departemen Sosial Politik	Departemen Kerohanian	https://drive.google.com/open?id=1E_Vz8kRXDkhHqVmGNjtYC4IDLNHxGsVK	https://drive.google.com/open?id=1e5A04XyHBRdScAG4r4WIhAQkxeMVRHXz	https://drive.google.com/open?id=1xdym7bsR8fQc5cODE4quyG2lb40koP8z	https://drive.google.com/open?id=11TJUuSZw0VO0IhRrT13etS-VI2ESu5oU
01/02/2026 21:40:02	nazwasfadilah17@gmail.com	Nazwa Siti Fadilah	2507434016	TMD CCIT 1A	081290157459	Departemen Pendidikan dan Teknologi	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1PGc__9cJF7ksj822jevnIIf9GGguCEVU	https://drive.google.com/open?id=12eFDSE_9PCaf7Gy7JHwQYv8JnefibC-3	https://drive.google.com/open?id=1js8vhaNnxcSEvgs54xpz8uiz2IOMRlKb	https://drive.google.com/open?id=1STlGf7yzuzFWDD_a6T2Tjg6FFlX3RnDL
01/02/2026 19:44:30	nnaf7220@gmail.com	Nafisah Khansa	2507434004	TMD-CCIT 1A	081296728320	Departemen Sosial Mahasiswa	Departemen Sosial Politik	https://drive.google.com/open?id=1lEs8FBFn6FoKOEiTT15L7gimTwn4oDAK	https://drive.google.com/open?id=1p_7xajk6ssL8YhliBl3k1VAMuDYi38ki	https://drive.google.com/open?id=1XL-n-zxbK1S0ZG83MrCNXvSBKA-IHUKn	https://drive.google.com/open?id=1Yu7fLOc0CDy51o1IqIQB8yiqkTLPDks2
01/02/2026 22:23:24	rakhahafidz2011@gmail.com	Rakha Hafidz Trista	2507421005	TMJ 1A	081219370596	Departemen Kerohanian	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=1h7gi_w5WtSO3-xJm57UvX89gMq-JfhuE	https://drive.google.com/open?id=1HAnCj9-C7A6zmml_FjtHfFNk3ASqDDGu	https://drive.google.com/open?id=19i977bRUaxfD993RN8H80GvUREJbbyjQ	https://drive.google.com/open?id=1snyyjnz1soGeimcaslVk9bL6u0YMN--G
01/02/2026 22:38:01	najlaanrs@gmail.com	Najla Nur Shafiyah	2507421041	TMJ 1A	088264292711	Departemen Pendidikan dan Teknologi	Departemen Komunikasi dan Informasi	https://drive.google.com/open?id=1kMtQ-sZe_s5_DFLDoEvBZEN1oTVCpBd6	https://drive.google.com/open?id=1c0M4ytn5H_Gjfi2_YM0jhBpnpS8sNZsB	https://drive.google.com/open?id=1zvDMIbw2vGNssMUwX_zopl1oC_84_2ma	https://drive.google.com/open?id=1OM9qzD7svHMyciAOP9uy13MVwLgbyEys
01/02/2026 13:51:33	faza.zidan.rashif.mu.affi.tik25@stu.pnj.ac.id	Faza Zidan Rashif Mu'affi	2507421074	TMJ 1A	085171080831	Departemen Sosial Politik	Departemen Kerohanian	https://drive.google.com/open?id=1zNaXuC5ANTjoTHSM3e-6_p2BvMWqxaWf	https://drive.google.com/open?id=15a9Ugw9rC2WhP6905vo-jNM6FLzddLy9	https://drive.google.com/open?id=131lj4dNDvBSi_UkZ43zuPsq25SFry6y9	https://drive.google.com/open?id=1qyOf8ZYkJh5xFVVrcYBUuRgIrbIeTpy0
27/01/2026 20:35:01	rasendriya.rizki.indraprakoso.tik25@stu.pnj.ac.id	Rasendriya Rizki Indraprakoso	2507421057	TMJ 1C	081818655159	Departemen Pendidikan dan Teknologi	Departemen Pendidikan dan Teknologi	https://drive.google.com/open?id=1JJKtSNs7EfVXTwz0CkKJ6SJ3e_eoTbgO	https://drive.google.com/open?id=12UtF_Rb1hZ8x0DkBZ5RgGxw2jsohgXyS	https://drive.google.com/open?id=1_XJMuHgYjvsC3XNr_ALlYyviYw3lo8YL	https://drive.google.com/open?id=1-7_H5PZQWOcI894kOrhw4D2NU4MW1Kxu
29/01/2026 00:20:39	muhammadhamdanibrain@gmail.com	Muhammad Hamdani	2507421023	TMJ 1C	089529524333	Departemen Sosial Mahasiswa	Biro Kreatif	https://drive.google.com/open?id=1IRbFXfscKZgyfkDW_0Wnomv0pbf88xyw	https://drive.google.com/open?id=1UJZzAkesKh55eUM0cclqudWALYgjJJm0	https://drive.google.com/open?id=1km2-lu6ftFsuAx93IGik0OJ4d-JO5DpZ	https://drive.google.com/open?id=1PghCb0Et2XLQIIduIQGy2qO7wD4omsFA
09/02/2026 21:14:16	dinda.aulia.tik25@stu.pnj.ac.id	Dinda Aulia	2507421003	TMJ 2A	081284609659	Biro Kesekretariatan	Departemen Kerohanian	https://drive.google.com/open?id=1Os8Y7Yq8frKvjYlErfmF7eN0GuJg4K4s	https://drive.google.com/open?id=1Trk6hPJh2Epmn44AfCgPKaFfEr8-0gO4	https://drive.google.com/open?id=1JfESy2_1MHbIFvaHeO7BY7A_kHktsA3b	https://drive.google.com/open?id=1q93dsLV2O76Qb7Q_Qew6fqzsPPHI7s-9
29/01/2026 21:35:27	rizky.awaliyah.marroh.tik25@stu.pnj.ac.id	Rizky Awaliyah Marroh	2507421033	TMJ 2A	088297293010	Departemen Kerohanian	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1ZqCHxBETVmgYcNcl6fDriV2iWxn1viCt	https://drive.google.com/open?id=1sCZbxVFWKcJXUSpn0Z_sfgtJ23JhrRhk	https://drive.google.com/open?id=1ZbvAz5jsay0hkaPPZFs0P1U8yYiWVH6g	https://drive.google.com/open?id=1E2BxV8m47iem0XGkKSgaFJR0kjwNqMzE
28/01/2026 06:36:14	farrel.audriel.tik25@stu.pnj.ac.id	Farrel Audriel	2507421004	TMJ 2A	087728393402	Departemen Pendidikan dan Teknologi	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1wqUxI_aunJioBsiH5mnjMSzHnwZNXPSE	https://drive.google.com/open?id=1gNa_gh3MRp6rQY12phQ7bIdPcR34lJpv	https://drive.google.com/open?id=1Rcu97shuZsHTKSR1BkrT1yG-Q05ryXvP	https://drive.google.com/open?id=1j2LEqxEhKGvp7N-Fz4t_l-ql_r16kkC2
29/01/2026 17:16:47	aqilla.zahraa1997@gmail.com	Aqilla Zahra	2507421075	TMJ 2A	081315807241	Departemen Sosial Mahasiswa	Biro Kreatif	https://drive.google.com/open?id=1nX5i_jHhPO3GsNWw9IHWRB91vBWL3-tp	https://drive.google.com/open?id=128qN5YUKUpNpoDprfjIWtQPZOXYOg2-g	https://drive.google.com/open?id=14ITTqIT7k0SuRZgWzDDvO3X2vwVLEg4C	https://drive.google.com/open?id=1pklrWHn2WF6aiegmOAo-k5P7OU-TQ6VE
28/01/2026 16:13:34	iwantnoodles1706@gmail.com	Mikayla Asha Pahlevi	2507421054	TMJ 2B	087817088864	Departemen Sosial Politik	Biro Kreatif	https://drive.google.com/open?id=13O8JfrNrV6oKYaygq0iBk5ZX0tRnZc2K	https://drive.google.com/open?id=196_4-ooSshMWCBRRqTq3dmKFjOraKvDq	https://drive.google.com/open?id=1zZNR4u7sTMEwiEIiDGsgQXJMXyO3Dy-_	https://drive.google.com/open?id=17W60zmPf177oobJ52VjzmRK7-W9G5Bom
29/01/2026 22:14:41	khalifrashad48@gmail.com	Khalif Aysel Rashad	2507421060	TMJ 2C	085694749969	Biro Kreatif	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=165bc4K_pbvjui6T9ffti-kH6pb7HVNAM	https://drive.google.com/open?id=1Rev95D6RU_J-Aba-F-JniQ00s_dLA7H9	https://drive.google.com/open?id=1EoUl7gwcSXPkVfFhROcW6ItJqH6G2C2A	https://drive.google.com/open?id=1et3pkYfdtiPdiJE1R_4JVg3pe2xn6pgr
29/01/2026 23:10:06	aftalkinsyah@gmail.com	Muhammad Ramdan Aftalkinsyah	2507421020	TMJ 2C	0895-1877-7791	Departemen Bisnis dan Kemitraan	Departemen Sosial Mahasiswa	https://drive.google.com/open?id=1yACEHojbUVuKA1u1siDJltQxJGfpV6md	https://drive.google.com/open?id=1aLDSFDsYfsYV3Goc1L_90zMk3YyK__la	https://drive.google.com/open?id=174P5qkncAdXsJWmVbkoS3_IQtFc3v12j	https://drive.google.com/open?id=1AU47kr8YY68kFRLitx7YhARjK79wYgLD
30/01/2026 17:14:23	zahwa.ahmada.tik25@stu.pnj.ac.id	Zahwa Ahmada	2507421025	TMJ 2C	085717326613	Departemen Kesehatan Mahasiswa	Departemen Kerohanian	https://drive.google.com/open?id=1h6rfurCF-yUOoHG75qBB8_3d3UEAnlfu	https://drive.google.com/open?id=1jD0RgNxEuGy01jntPFEEZUjFRtx6BS3D	https://drive.google.com/open?id=1xY8RCQHcnNTDznjSG4ZQ60p-MrLAMONw	https://drive.google.com/open?id=1GQ1e5cHAXmTRpAzdpAKYFgRrPCBBIUUd
29/01/2026 22:28:01	muhammadrasyadf@gmail.com	Muhammad Rasyad Fauzan	2507421083	TMJ 2C	081261852000	Departemen Pendidikan dan Teknologi	Departemen Kerohanian	https://drive.google.com/open?id=1iW6xGVZfvAcKKql_6h_Gii3vH6s9dIaL	https://drive.google.com/open?id=16DYKOsvyKda0BZWfRJ1FPDHHZEy1CzK8	https://drive.google.com/open?id=1Shd7k10cpqHOsNOYGvslagi7qDSw2kd-	https://drive.google.com/open?id=13_5AflOG-eETIbofVgPCSgZbWy4xdRFg
28/01/2026 15:59:44	ravva542006@gmail.com	Ravva Syaehhira	2507431033	TMS 1A	089510706757	Departemen Kesehatan Mahasiswa	Departemen Kesehatan Mahasiswa	https://drive.google.com/open?id=1blAaTrWtfZCbso1pg4AFE3jXQrCTS3O4	https://drive.google.com/open?id=1g5gXiKrANzvhWEcbbFKLyeEd1eUfrIpu	https://drive.google.com/open?id=1pUBiJXxmtfWPrw6oq39qkvpgrgfLFV2h	https://drive.google.com/open?id=1BmBiAUk0DSOCCCZPxJmX1XY0o74eKvtt
TSV;

        // Parse the TSV dynamically and insert candidates
        $rows = explode("\n", trim($tsvData));
        $candidates = [];
        $processedNims = [];
        foreach ($rows as $row) {
            $cols = explode("\t", trim($row));

            // Skip invalid or incomplete rows
            if (count($cols) < 8) {
                continue;
            }
            $email = trim($cols[1]);
            $name = trim($cols[2]);
            $nim = trim($cols[3]);
            $rawProdiKelas = trim($cols[4]);
            $phone = trim($cols[5]);
            $choice1Name = trim($cols[6]);
            $choice2Name = trim($cols[7]);
            // Deduplicate (skip if NIM has already been processed)
            if (in_array($nim, $processedNims)) {
                continue;
            }
            $processedNims[] = $nim;
            // Secure file paths (from drive links or falls back to standard mock paths)
            $formPath = count($cols) > 8 ? trim($cols[8]) : 'recruitment_forms/' . $nim . '.pdf';
            $photoPath = count($cols) > 9 ? trim($cols[9]) : 'photos/' . $nim . '.jpg';
            $letterPath = count($cols) > 10 ? trim($cols[10]) : 'statement_letters/' . $nim . '.pdf';
            $proofPath = count($cols) > 11 ? trim($cols[11]) : 'social_media_proofs/' . $nim . '.jpg';
            // Clean up Prodi enum mapping
            $prodiLower = strtolower($rawProdiKelas);
            if (str_contains($prodiLower, 'tmd') || str_contains($prodiLower, 'multimedia dan digital')) {
                $prodi = 'Teknik Multimedia dan Digital';
            } elseif (str_contains($prodiLower, 'tmj') || str_contains($prodiLower, 'multimedia dan jaringan')) {
                $prodi = 'Teknik Multimedia dan Jaringan';
            } else {
                $prodi = 'Teknik Informatika';
            }
            // Extract Class (e.g., TMD 2A, TI - 2B)
            $kelasMatches = [];
            preg_match('/(TI|TMD|TMJ|TMS|TI-CCIT)\s*[-]*\s*[1-2][A-C\s]*\b/i', $rawProdiKelas, $kelasMatches);
            $kelas = count($kelasMatches) > 0 ? trim($kelasMatches[0]) : 'TI-2A';
            // Query choice departments from database dynamically
            $firstChoice = Departmentsbiro::where('name', $choice1Name)->first();
            $secondChoice = Departmentsbiro::where('name', $choice2Name)->first();
            // Skip if first or second choice departments do not exist in DB
            if (!$firstChoice || !$secondChoice) {
                continue;
            }
            // Create User Account
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'), // Default password
                'role' => 'candidate',
            ]);
            // Create Candidate Profile linked to User
            $candidate = Candidate::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'prodi' => $prodi,
                'kelas' => $kelas,
                'phone' => $phone,
                'first_choice_id' => $firstChoice->id,
                'second_choice_id' => $secondChoice->id,
                'recruitment_form_path' => $formPath,
                'photo_path' => $photoPath,
                'statement_letter_path' => $letterPath,
                'social_media_proof_path' => $proofPath,
                'status' => 'registered'
            ]);
            $candidates[] = $candidate;
            // Generate initial Announcement (Pending)
            Announcement::create([
                'candidate_id' => $candidate->id,
                'status' => 'pending',
                'is_published' => false,
            ]);
        }
        // We will build schedules, assign panels (Kadept + Litbang of their Choice 1), and book them
        $now = Carbon::now();
        foreach ($candidates as $index => $candidate) {
            $firstChoiceDept = $candidate->firstChoice;
            // Map department names to the correct panel users we seeded above
            $panelMap = [
                'Departemen Pendidikan dan Teknologi' => [$interviewer1, $interviewer15], // Falih & Farrel
                'Biro Penelitian dan Pengembangan' => [$interviewer8, $interviewer8], // Wafana (self-panel)
                'Departemen Kesehatan Mahasiswa' => [$interviewer10, $interviewer20], // Zaldi & Dea
                'Biro Bendahara Umum' => [$interviewer9, $interviewer17], // Rania & Iqbal
                'Departemen Komunikasi dan Informasi' => [$interviewer2, $interviewer14], // Owen & Boy
                'Departemen Bisnis dan Kemitraan' => [$interviewer7, $interviewer21], // Hardimas & Farrel Bismit
                'Departemen Sosial Politik' => [$interviewer6, $interviewer19], // Doni & Forza
                'Biro Kesekretariatan' => [$interviewer5, $interviewer18], // Dandy & Monaning
                'Biro Kreatif' => [$interviewer4, $interviewer13], // Daffa & Adelio
                'Departemen Kerohanian' => [$interviewer11, $interviewer12], // Rizki & Dhafin
                'Departemen Sosial Mahasiswa' => [$interviewer3, $interviewer16], // Alif & Abdillah
            ];
            $panel = $panelMap[$firstChoiceDept->name] ?? [$interviewer1, $interviewer15];
            // Create Slot
            $schedule = InterviewSchedule::create([
                'session_name' => 'Session ' . ($index + 1) . ' - ' . $firstChoiceDept->name,
                'scheduled_at' => $now->copy()->addDays(2)->setTime(9 + ($index % 8), 0),
                'location' => 'Workspace / Room A101',
                'candidate_id' => $candidate->id,
            ]);
            // Attach Panel Interviewers
            $schedule->interviewers()->attach([$panel[0]->id, $panel[1]->id]);
            // Update candidate status to 'scheduled'
            $candidate->update(['status' => 'scheduled']);
        }
        // This instantly triggers live DSS Profile Matching calculations for your Admin Rankings!
        for ($i = 0; $i < min(5, count($candidates)); $i++) {
            $candidate = $candidates[$i];
            $dept = $candidate->firstChoice;
            $criteriaList = $dept->evaluationCriteria;
            // Generate mock scores (4s and 5s for the first few candidates to look highly compatible)
            foreach ($criteriaList as $criteria) {
                Evaluation::create([
                    'candidate_id' => $candidate->id,
                    'department_id' => $dept->id,
                    'criteria_id' => $criteria->id,
                    'score' => rand(3, 5), // Consensus score
                    'interviewer_id' => $interviewer1->id, // Inputted on behalf of panel
                ]);
            }
            // Update candidate status to evaluated
            $candidate->update(['status' => 'evaluated']);
        }
    }
}

