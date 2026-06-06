# Project Context

Last updated: 2026-06-05

This document captures the current implemented state of the HIMATIK PNJ Web-Based Decision Support System for recruitment.

## Project Goal

The system supports recruitment for HIMATIK PNJ by covering:

```text
account registration -> email OTP verification -> landing -> candidate profile registration -> schedule selection -> interview evaluation -> Profile Matching calculation -> announcement
```

The core DSS method is Profile Matching. Public announcements must only show final outcome data, while detailed scores remain private.

## Current Architecture

- Backend: Laravel.
- Web UI: Blade admin/candidate/auth/landing pages.
- API: Laravel API routes, with Sanctum available.
- Main roles:
  - `admin`
  - `interviewer`
  - `candidate`

## Registration Flow

The agreed flow is:

```text
register account -> verify email OTP -> landing -> candidate registration -> schedule selection
```

Implemented behavior:

- Account registration creates a `users` row only.
- Candidate profile is not created at account registration time.
- OTP verification uses `email_verification_otps`.
- Candidate profile submission creates the `candidates` row and related repeatable profile records.
- Candidate department choices are stored through `candidate_departmentsbiro`.
- Candidate schedule selection happens after profile completion.

## Candidate Registration Data

Candidate profile includes:

- Full name from `users.name`.
- Email from `users.email`.
- `candidate_type`.
- `nim`, exactly 10 digits.
- `nickname`.
- `prodi`.
- `kelas`.
- `phone`.
- `address`.
- `photo_path`.
- First and second department/biro choices through `candidate_departmentsbiro`.
- `department_choice_reason`.
- `weakness_description`.
- `contribution_plan`.
- `instagram_proof_path`.
- `youtube_proof_path`.
- `political_statement_path`.
- `candidate_signature_path`.
- `parent_signature_path`.

Repeatable sections are normalized:

- `candidate_educations`
- `candidate_organizations`
- `candidate_committees`
- `candidate_skills`
- `candidate_facilities`

The old candidate upload columns were removed from the intended schema:

- `recruitment_form_path`
- `statement_letter_path`
- `social_media_proof_path`

## Database Schema Status

The detailed schema reference is in:

```text
database_schema_design.md
```

Implemented schema changes include:

- `departmentsbiro` kept as the canonical department/biro table.
- `departmentsbiro` now includes:
  - `slug`
  - `is_active`
  - `personal_aspect_weight`
  - `organizational_aspect_weight`
  - `core_factor_weight`
  - `secondary_factor_weight`
- Candidate choices moved from candidate columns to `candidate_departmentsbiro`.
- Interview schedule ownership changed:
  - `interview_schedules` belongs to one department.
  - `candidate_interview_schedules` stores the candidate booking.
  - `interviewer_schedule` assigns interviewers to schedule rows.
- Criteria support:
  - `default_evaluation_criteria`
  - `evaluation_criteria`
- SPK support:
  - `spk_gap_weights`
  - `spk_results`
  - `spk_calculation_logs`
- OTP support:
  - `email_verification_otps`

Important note: current data is treated as seed/demo data. The clean way to apply schema/data changes is:

```bash
php artisan migrate:fresh --seed
```

## Departments/Biro

The active seeded departments/biro are 10 entries:

```text
Biro Bendahara Umum
Biro Kesekretariatan
Biro Kreatif
Departemen Bisnis dan Kemitraan
Departemen Kerohanian
Departemen Kesehatan Mahasiswa
Departemen Komunikasi dan Informasi
Departemen Pendidikan dan Teknologi
Departemen Sosial Mahasiswa
Departemen Sosial Politik
```

`Litbang` was intentionally removed.

Inactive departments:

- Admin may see inactive departments.
- Candidate/public/interviewer-facing flows should hide inactive departments.

## Criteria Model

Criteria are dynamic per department.

Default criteria are stored in `default_evaluation_criteria`.
Department-specific criteria are stored in `evaluation_criteria`.

Each criterion supports:

- `department_id`
- `default_criteria_id`
- `code`
- `name`
- `description`
- `type`: `core` or `secondary`
- `aspect`: `personal` or `organizational`
- `target_score`
- `catatan`
- `is_active`
- `sort_order`

Admins can CRUD criteria per department.

If a new criterion is added to one department only:

- It only affects that department.
- Profile Matching dynamically includes it if `is_active = true`.
- Rankings should be compared within the same department, because departments can have different criteria.

## Final K1-K7 Criteria

The final criteria order is:

```text
K1 Kepercayaan Diri dan Komunikasi      personal        core
K2 Problem Solve (Pemecahan Masalah)    personal        core
K3 Manajemen Waktu                      personal        secondary
K4 Konsistensi Jawaban                  personal        secondary
K5 Komitmen                             organizational  core
K6 Pengalaman Organisasi/Kepanitiaan    organizational  secondary
K7 Pengetahuan Organisasi               organizational  secondary
```

This is implemented in:

```text
web/app/Support/SpkCriteriaDefaults.php
web/database/seeders/RecruitmentTestDataSeeder.php
```

## Department-Specific Target Scores

Target scores are department-specific and are implemented in `SpkCriteriaDefaults`.

```text
Department/Biro                         K1 K2 K3 K4 K5 K6 K7
Biro Kesekretariatan                    3  3  5  5  4  3  4
Biro Bendahara Umum                     3  4  4  5  5  3  3
Biro Kreatif                            4  5  4  3  4  3  2
Departemen Pendidikan dan Teknologi     4  5  4  4  4  3  4
Departemen Komunikasi dan Informasi     5  4  4  4  4  3  3
Departemen Bisnis dan Kemitraan         5  4  4  3  4  4  3
Departemen Sosial Politik               5  5  3  4  4  4  4
Departemen Sosial Mahasiswa             4  4  3  3  5  4  3
Departemen Kesehatan Mahasiswa          4  4  4  3  5  4  3
Departemen Kerohanian                   4  3  4  5  5  3  3
```

This target matrix is used by:

- Seeder when creating department criteria.
- Web admin criteria reset.
- API admin criteria reset.

## Evaluation Flow

Evaluation scores are stored in `evaluations`.

Current behavior:

- One shared score exists per:

```text
candidate_id + department_id + criteria_id
```

- Multiple interviewers may access the same form.
- Scores are not averaged across interviewers.
- `interviewer_id` stores the last updater.
- `version` supports optimistic locking for stale-update handling.
- Real-time score visibility is intended as frontend/broadcast behavior, not a separate database table.

## Profile Matching Calculation

Implemented in:

```text
web/app/Services/ProfileMatchingService.php
```

Calculation steps:

```text
gap = actual_score - target_score
mapped_weight = spk_gap_weights[gap]
```

Default gap mapping:

```text
 0  => 5.0
 1  => 4.5
-1  => 4.0
 2  => 3.5
-2  => 3.0
 3  => 2.5
-3  => 2.0
 4  => 1.5
-4  => 1.0
```

Criteria are grouped by `aspect` and `type`:

```text
personal_core
personal_secondary
organizational_core
organizational_secondary
```

The current formula is:

```text
personal_score =
  (core_factor_weight * personal_core)
  + (secondary_factor_weight * personal_secondary)

organizational_score =
  (core_factor_weight * organizational_core)
  + (secondary_factor_weight * organizational_secondary)

final_score =
  (personal_aspect_weight * personal_score)
  + (organizational_aspect_weight * organizational_score)
```

Weights are stored as percentages, so the service divides them by 100.

Default weights:

```text
Personal aspect = 60%
Organizational aspect = 40%
Core factor = 60%
Secondary factor = 40%
```

Results:

- `spk_results` is overwritten for the same candidate and department.
- `spk_calculation_logs` stores calculation history.
- Scores are rounded to 4 decimals.

## Admin Web Pages

Admin pages use:

```text
web/resources/views/layouts/admin.blade.php
```

Implemented admin pages include:

- Dashboard.
- Profile Matching testing page.
- Criteria CRUD page.
- Interviewer management page.
- Schedule management page.
- Rankings page.

## Admin Testing Page

Files:

```text
web/app/Http/Controllers/Web/AdminWebController.php
web/resources/views/admin/testing.blade.php
```

Current behavior:

- Select department/biro.
- Add/edit/delete demo candidates.
- Candidate choices are saved to `candidate_departmentsbiro`.
- Input shared evaluation scores.
- Show Profile Matching ranking.
- Pagination currently uses:

```php
$candidates = $candidatesQuery->paginate(5);
```

UI adjustments completed:

- Score cards use responsive grid placement.
- On large desktop, criteria cards display as stable rows, e.g. 7 criteria as `4 + 3`.
- Score cards show aspect (`P` or `O`) and factor (`CF` or `SF`).
- Ranking labels were updated to `Personal` and `Organizational`, not old `NCF` and `NSF`.

## Rankings Page

File:

```text
web/resources/views/admin/rankings.blade.php
```

Completed updates:

- Department tabs support dark mode.
- Candidate avatar supports dark mode.
- Decision modal supports dark mode.
- Table now displays `Personal`, `Organizational`, and total score with 4 decimals.

## Criteria Page

File:

```text
web/resources/views/admin/criteria.blade.php
```

Completed updates:

- Criteria CRUD supports code, type, aspect, target score, notes, active flag, and sort order.
- Criteria reset uses `SpkCriteriaDefaults::targetScoreFor(...)`, so department-specific target scores are preserved.
- Criteria code badge uses `.criteria-code-badge`.
- Criteria badges/chips support dark mode.

## Interviewer Page

File:

```text
web/resources/views/admin/interviewers.blade.php
```

Completed updates:

- Interviewer list card supports dark mode.
- Add/edit interviewer modals support dark mode.
- Modal footer, modal close button, card, and avatar colors were fixed for dark mode.

## Admin Dark Mode

Dark mode is implemented for all admin pages that use `layouts.admin`.

File:

```text
web/resources/views/layouts/admin.blade.php
```

Implementation details:

- Body class:

```text
admin-theme-enabled
```

- Theme attribute:

```text
data-theme="dark"
```

- Toggle button id:

```text
admin-theme-toggle
```

- Local storage key:

```text
himatik-admin-theme
```

Dark mode fixes already applied for:

- Sidebar.
- Topbar.
- Footer.
- Admin cards.
- Stat cards.
- Tables.
- Buttons.
- Forms.
- Modals.
- Department tabs.
- Criteria chips.
- Criteria code badge.
- Interviewer cards.
- Ranking avatars.
- Admin testing department info strip.
- Score cards and score selects.

## API Changes

Important API routes include:

```text
POST /api/register
POST /api/email/verify-otp
POST /api/email/resend-otp
POST /api/candidate/profile
GET  /api/me
```

Registration API behavior:

- `POST /api/register` is account-only and sends OTP.
- Candidate profile is submitted separately.
- Candidate choices are stored in `candidate_departmentsbiro`.

Admin API includes:

- Department CRUD.
- Criteria CRUD.
- Criteria reset.
- Schedule CRUD.
- Interviewer CRUD.
- Rankings.
- Announcement decision/publish flows.

## Docs

Docs touched/updated:

- `database_schema_design.md`
- `BladeDocsController`
- Scribe output/cache files under `web/.scribe` and `web/resources/views/scribe`

The database schema design file is the schema reference. This `project-context.md` is the implementation/context snapshot.

## Seed Data

Seeder:

```text
web/database/seeders/RecruitmentTestDataSeeder.php
```

Seeder currently creates:

- Admin user.
- Interviewer users.
- 10 departments/biro.
- Default criteria.
- Department-specific evaluation criteria using the target score matrix.
- Gap weights.
- Interview schedules.
- Demo candidates and related profile data.
- Evaluation/demo announcement data as needed by the demo flow.

Known seeder fix:

- Candidate child models explicitly define table names:
  - `candidate_educations`
  - `candidate_organizations`
  - `candidate_committees`
  - `candidate_skills`
  - `candidate_facilities`

This fixed the previous `candidate_education` table name error.

## Important Files

Backend:

```text
web/app/Http/Controllers/Web/AdminWebController.php
web/app/Http/Controllers/Web/AuthWebController.php
web/app/Http/Controllers/Web/CandidateWebController.php
web/app/Http/Controllers/Api/AdminApiController.php
web/app/Http/Controllers/Api/AuthApiController.php
web/app/Http/Controllers/Api/CandidateApiController.php
web/app/Services/ProfileMatchingService.php
web/app/Services/CandidateOtpService.php
web/app/Services/CandidateProfileService.php
web/app/Support/SpkCriteriaDefaults.php
web/app/Support/CandidateProfileRules.php
```

Models:

```text
Candidate
Departmentsbiro
Evaluation
EvaluationCriteria
DefaultEvaluationCriteria
InterviewSchedule
CandidateDepartmentChoice
CandidateInterviewSchedule
SpkGapWeight
SpkResult
SpkCalculationLog
EmailVerificationOtp
CandidateEducation
CandidateOrganization
CandidateCommittee
CandidateSkill
CandidateFacility
```

Admin views:

```text
web/resources/views/layouts/admin.blade.php
web/resources/views/admin/dashboard.blade.php
web/resources/views/admin/testing.blade.php
web/resources/views/admin/criteria.blade.php
web/resources/views/admin/interviewers.blade.php
web/resources/views/admin/schedules.blade.php
web/resources/views/admin/rankings.blade.php
```

## Verification Already Performed

During implementation, route and syntax checks were run for affected areas, including:

```text
php -l web/app/Http/Controllers/Web/AdminWebController.php
php -l web/app/Http/Controllers/Api/AdminApiController.php
php -l web/database/seeders/RecruitmentTestDataSeeder.php
php -l web/app/Support/SpkCriteriaDefaults.php
php artisan route:list --path=admin
php artisan route:list --name=admin.testing
php artisan route:list --name=admin.criteria
php artisan route:list --name=admin.dashboard
php artisan route:list --path=admin/interviewers
php artisan route:list --path=admin/rankings
```

Full automated test execution was not confirmed in this snapshot.

## Current Follow-Up Notes

- If database data looks stale, run:

```bash
php artisan migrate:fresh --seed
```

- The admin testing page pagination size is controlled in `AdminWebController@testing`.
- Dark mode has broad admin coverage, but if a future page adds hardcoded `background:white`, `#fafafa`, or inline light colors, add a scoped override under `body.admin-theme-enabled[data-theme="dark"]`.
- Cross-department score comparison should be avoided when departments have custom criteria, because each department can have different scoring criteria and target scores.
