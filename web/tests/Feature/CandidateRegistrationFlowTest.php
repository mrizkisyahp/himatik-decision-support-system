<?php

namespace Tests\Feature;

use App\Mail\CandidateOtpMail;
use App\Models\Departmentsbiro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CandidateRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_registration_creates_user_and_otp_but_not_candidate(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register', [
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('next_step', 'verify_email');

        $user = User::where('email', 'ahmad@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNull($user->candidate);
        $this->assertDatabaseHas('email_verification_otps', ['user_id' => $user->id]);
        Mail::assertSent(CandidateOtpMail::class);
    }

    public function test_candidate_can_verify_otp_after_registration(): void
    {
        Mail::fake();

        $this->postJson('/api/register', [
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertCreated();

        $user = User::where('email', 'ahmad@example.com')->firstOrFail();

        $otpCode = null;
        Mail::assertSent(CandidateOtpMail::class, function (CandidateOtpMail $mail) use (&$otpCode) {
            $otpCode = $mail->code;

            return true;
        });

        Sanctum::actingAs($user);

        $this->postJson('/api/email/verify-otp', [
            'otp' => $otpCode,
        ])->assertOk()
            ->assertJsonPath('redirect_to', 'landing')
            ->assertJsonPath('next_step', 'candidate_registration');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_unverified_candidate_cannot_submit_profile(): void
    {
        $departments = $this->createDepartments();
        $user = User::factory()->create([
            'role' => 'candidate',
            'email_verified_at' => null,
        ]);

        Sanctum::actingAs($user);

        $this->post('/api/candidate/profile', $this->validProfilePayload($departments), [
            'Accept' => 'application/json',
        ])->assertStatus(403);
    }

    public function test_verified_candidate_can_submit_profile_and_fetch_me(): void
    {
        $departments = $this->createDepartments();
        $user = User::factory()->create([
            'role' => 'candidate',
            'email_verified_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this->post('/api/candidate/profile', $this->validProfilePayload($departments), [
            'Accept' => 'application/json',
        ])->assertCreated()
            ->assertJsonPath('next_step', 'schedule_selection');

        $candidate = $user->fresh()->candidate;

        $this->assertDatabaseHas('candidates', [
            'user_id' => $user->id,
            'nim' => '2211501234',
        ]);
        $this->assertDatabaseHas('candidate_educations', [
            'candidate_id' => $candidate->id,
            'school_name' => 'SMK Negeri 1',
        ]);
        $this->assertDatabaseHas('candidate_skills', [
            'candidate_id' => $candidate->id,
            'skill_name' => 'Time Management',
        ]);
        $this->assertDatabaseHas('candidate_departmentsbiro', [
            'candidate_id' => $candidate->id,
            'departmentsbiro_id' => $departments[0]->id,
            'choice_order' => 1,
        ]);
        $this->assertDatabaseHas('candidate_departmentsbiro', [
            'candidate_id' => $candidate->id,
            'departmentsbiro_id' => $departments[1]->id,
            'choice_order' => 2,
        ]);

        $this->getJson('/api/me')->assertOk()
            ->assertJsonPath('user.email_verified', true)
            ->assertJsonPath('candidate.nim', '2211501234')
            ->assertJsonPath('next_step', 'schedule_selection');
    }

    private function createDepartments(): array
    {
        return [
            Departmentsbiro::create([
                'name' => 'Biro Kreatif',
                'slug' => 'biro-kreatif',
                'description' => 'Creative bureau',
                'personal_aspect_weight' => 60.00,
                'organizational_aspect_weight' => 40.00,
                'core_factor_weight' => 60.00,
                'secondary_factor_weight' => 40.00,
                'is_active' => true,
            ]),
            Departmentsbiro::create([
                'name' => 'Departemen Komunikasi dan Informasi',
                'slug' => 'departemen-komunikasi-dan-informasi',
                'description' => 'Communication department',
                'personal_aspect_weight' => 60.00,
                'organizational_aspect_weight' => 40.00,
                'core_factor_weight' => 60.00,
                'secondary_factor_weight' => 40.00,
                'is_active' => true,
            ]),
        ];
    }

    private function validProfilePayload(array $departments): array
    {
        return [
            'candidate_type' => 'staff',
            'nickname' => 'Ahmad',
            'nim' => '2211501234',
            'prodi' => 'Teknik Informatika',
            'kelas' => 'TI 4B',
            'phone' => '0858123456',
            'address' => 'Jl Contoh RT 01 RW 02 No 3',
            'first_choice_id' => $departments[0]->id,
            'second_choice_id' => $departments[1]->id,
            'department_choice_reason' => 'Saya ingin berkontribusi di dua bidang ini.',
            'weakness_description' => 'Kadang terlalu detail.',
            'contribution_plan' => 'Saya akan menyusun program kerja terukur.',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'instagram_proof' => UploadedFile::fake()->image('instagram.jpg'),
            'youtube_proof' => UploadedFile::fake()->image('youtube.jpg'),
            'political_statement' => UploadedFile::fake()->create('statement.pdf', 120, 'application/pdf'),
            'candidate_signature' => UploadedFile::fake()->image('candidate-signature.jpg'),
            'parent_signature' => UploadedFile::fake()->image('parent-signature.jpg'),
            'educations' => [
                [
                    'education_type' => 'formal',
                    'school_name' => 'SMK Negeri 1',
                    'start_year' => 2021,
                    'end_year' => 2024,
                    'city' => 'Depok',
                    'major' => 'TKJ',
                ],
            ],
            'organizations' => [
                [
                    'organization_name' => 'English Club',
                    'start_year' => 2022,
                    'end_year' => 2023,
                    'place_or_institution' => 'Sekolah',
                    'position' => 'Ketua',
                ],
            ],
            'committees' => [
                [
                    'committee_name' => 'CSFest',
                    'start_year' => 2024,
                    'end_year' => 2024,
                    'organizer' => 'HIMATIK',
                    'position' => 'Dokumentasi',
                ],
            ],
            'skills' => [
                [
                    'skill_type' => 'soft',
                    'skill_name' => 'Time Management',
                    'proficiency' => 'cakap',
                ],
            ],
            'facilities' => [
                [
                    'facility_name' => 'Laptop',
                ],
            ],
        ];
    }
}
