# API Documentation

Last reviewed: 2026-06-08

Base path: `/api`

Source of truth:

- `web/routes/api.php`
- `php artisan route:list`
- `web/app/Http/Controllers/Api/*`
- `web/app/Support/CandidateProfileRules.php`
- Current migrations/models

This document covers API endpoints only: method, endpoint, controller/action, middleware, request/validation, response shape, related models/services, status, and known limitations. Blade pages are documented separately in `docs/blade.md`.

## Auth And Middleware

- Public API routes have no auth middleware.
- Protected routes use `auth:sanctum`.
- Role middleware is `role:candidate`, `role:interviewer`, or `role:admin`.
- Roles are stored in `users.role`: `admin`, `interviewer`, `candidate`.
- Candidate identity fields currently live on `users`: `name`, `nickname`, `nim`, `prodi`, `kelas`, `phone`, `address`.
- Candidate application/profile fields live on `candidates` and child tables.
- Google OAuth fields live on `users`: `google_id`, `auth_provider`, `avatar_url`.

## Public API Routes

| Method | Endpoint | Controller/action | Middleware | Request / Validation | Response shape | Related models/services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/landing` | `LandingApiController@index` | none | none | `success`, `departments`, `open_recruitment_cards` | `Departmentsbiro`, `DepartmentAgenda`, `DepartmentWorkProgram`, `OpenRecruitmentService` | Functional | Departments are active-only and include safe public department profile data. |
| GET | `/api/departments` | `CandidateApiController@getDepartments` | none | none | `success`, `data[]` with `id`, `name`, `slug`, `description` | `Departmentsbiro` | Functional | Active departments only. |
| POST | `/api/register` | `CandidateApiController@register` | none | `email`: required email unique users; `nama`: required string; `password`: required min 8 confirmed; `password_confirmation`: required | `201 success`, Sanctum `token`, `user`, `next_step: verify_email` | `User`, `CandidateOtpService`, `EmailVerificationOtp` | Functional | Account-only registration. Does not create a candidate profile row. |
| POST | `/api/login` | `AuthApiController@login` | none | `email`: required email; `password`: required string | `success`, `token`, `user`, `candidate`, `next_step` | `User`, `Candidate` | Functional | `next_step` is role-aware; non-candidates get `dashboard`. |
| POST | `/api/auth/google` | `GoogleAuthController@login` | none | `id_token`: required Google ID token from mobile client | `success`, Sanctum `token`, `user`, `candidate`, `next_step` | `User`, `GoogleAuthService`, Google tokeninfo API | Functional | Verifies token server-side and requires Google `email_verified`. Creates/links `users` only; does not create a `candidates` row. |
| GET | `/api/announcements` | `PublicAnnouncementApiController@getAcceptedList` | none | none | Public announcement payload | `Announcement`, `Candidate`, `Departmentsbiro` | Functional | Public output does not expose score details. |

## Protected Common API Routes

| Method | Endpoint | Controller/action | Middleware | Request / Validation | Response shape | Related models/services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/me` | `CandidateApiController@me` | `auth:sanctum` | none | `success`, `user`, `candidate`, `schedule`, `announcement`, `dss_results`, `next_step` | `User`, `Candidate`, `Announcement`, `ProfileMatchingService` | Functional | Loads choices, repeatable sections, schedule, SPK results. If announcement is published/evaluated, DSS details may be calculated. |
| POST | `/api/logout` | `AuthApiController@logout` | `auth:sanctum` | none | `success`, `message` | `User` | Functional | Revokes current token. |
| POST | `/api/email/verify-otp` | `AuthApiController@verifyOtp` | `auth:sanctum` | `otp`: required digits:6 | `success`, `message`, `redirect_to: landing`, `next_step` | `CandidateOtpService`, `EmailVerificationOtp`, `User` | Functional | Controller only allows candidate users. |
| POST | `/api/email/resend-otp` | `AuthApiController@resendOtp` | `auth:sanctum` | none | `success`, `message` or already-verified error | `CandidateOtpService`, `EmailVerificationOtp`, `User` | Functional | Controller only allows unverified candidate users. |

## Protected Candidate API Routes

| Method | Endpoint | Controller/action | Middleware | Request / Validation | Response shape | Related models/services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| POST | `/api/candidate/profile` | `CandidateApiController@storeProfile` | `auth:sanctum`, `role:candidate` | Multipart full profile using `CandidateProfileRules::rules($userId)` | `201 success`, `candidate`, `next_step: schedule_selection` | `CandidateProfileService`, `CandidateProfileRules`, `OpenRecruitmentService`, `Candidate` | Functional | Requires verified email, no existing candidate profile, and currently-open candidate type. Identity fields update `users`; profile fields create `candidates` and child rows. API file uploads are stored by `CandidateProfileService` on the `local` disk. |
| GET | `/api/schedules` | `CandidateApiController@getAvailableSchedules` | `auth:sanctum`, `role:candidate` | none | `success`, `data[]`, `current_booked_slot_id` | `Candidate`, `InterviewSchedule`, `CandidateInterviewSchedule` | Functional | Returns first-choice department slots that are not blocked and not booked by another candidate. Current fields: `id`, `department_id`, `date`, `start_time`, `end_time`, `is_blocked`. |
| POST | `/api/schedules/book` | `CandidateApiController@bookSchedule` | `auth:sanctum`, `role:candidate` | `schedule_id`: required exists `interview_schedules,id` | `success`, `message`, `booked_slot` | `Candidate`, `InterviewSchedule`, `CandidateInterviewSchedule` | Functional | Candidate can only book first-choice department slot. Uses `is_blocked = false`; updates candidate status to `scheduled`. |

Candidate profile validation highlights:

- `nim` validates as exactly 10 digits and unique in `users.nim`, not `candidates.nim`.
- `first_choice_id` and `second_choice_id` must reference active `departmentsbiro`.
- `second_choice_id` is nullable and must differ from `first_choice_id`.
- Required files: `photo`, `instagram_proof`, `youtube_proof`, `political_statement`, `candidate_signature`, `parent_signature`.
- Repeatable arrays: `educations`, `organizations`, `committees`, `skills`, `facilities`.

### Google OAuth API Notes

- Mobile clients should obtain a Google ID token, then call `POST /api/auth/google`.
- The backend verifies the token with Google and checks the configured `GOOGLE_CLIENT_ID` audience when present.
- Google OAuth skips local email OTP only when Google reports `email_verified = true`.
- Existing local users are linked by matching email; duplicate users are not created for the same email.
- New Google users are created as `role = candidate`, with `email_verified_at` set immediately.
- `next_step` follows existing values: `candidate_registration`, `schedule_selection`, `candidate_status`, or `dashboard`.

## Protected Interviewer API Routes

| Method | Endpoint | Controller/action | Middleware | Request / Validation | Response shape | Related models/services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/interviewer/schedules` | `InterviewerApiController@getSchedules` | `auth:sanctum`, `role:interviewer` | none | `success`, `data[]` | `InterviewSchedule`, `User`, `CandidateInterviewSchedule` | Functional | Loads schedules by authenticated interviewer's `users.department_id`. Does not use the removed `interviewer_schedule` table. Current schedule fields: `date`, `start_time`, `end_time`, `is_blocked`. |
| GET | `/api/interviewer/grade/{candidate}/{department}` | `InterviewerApiController@getGradingDetails` | `auth:sanctum`, `role:interviewer` | Route models: `Candidate`, `Departmentsbiro` | `success`, `candidate`, `department`, `criteria`, `existing_scores` | `Candidate`, `Departmentsbiro`, `EvaluationCriteria`, `Evaluation` | Functional | Candidate must have chosen the department. Existing scores include `score` and `version`. |
| POST | `/api/interviewer/grade/{candidate}/{department}` | `InterviewerApiController@submitScores` | `auth:sanctum`, `role:interviewer` | `scores`: required array; `scores.*`: integer 1-5; `global_notes`: nullable string max 1000 | `success`, `message` | `Candidate`, `Departmentsbiro`, `EvaluationCriteria`, `Evaluation` | Functional | Saves one shared score per candidate/department/criteria. `interviewer_id` is last updater; `version` increments. |
| POST | `/api/interviewer/decide/{candidate}` | `AdminApiController@decideCandidate` | `auth:sanctum` | `status`: required accepted/rejected; `assigned_department_id`: required if accepted | `success`, `message`, `data` or quota error | `Candidate`, `Announcement`, `OpenRecruitment`, `OpenRecruitmentQuota` | Functional | Route itself is not inside `role:interviewer`, but controller only allows `admin` or `interviewer`. Quota check uses `candidate_type + department_id`. |

## Protected Admin API Routes

| Method | Endpoint | Controller/action | Middleware | Request / Validation | Response shape | Related models/services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/admin/stats` | `AdminApiController@getStats` | `auth:sanctum`, `role:admin` | none | `success`, `data.stats`, `data.departments` | `Candidate`, `Departmentsbiro` | Functional | Also checks admin role internally. |
| GET | `/api/admin/rankings/{department}` | `AdminApiController@getRankings` | `auth:sanctum`, `role:admin` | Route model: `Departmentsbiro` | `success`, `department`, `rankings`, `announcements` | `Departmentsbiro`, `Announcement`, `ProfileMatchingService` | Functional | Not read-only: ranking calls may calculate and write `spk_results` and `spk_calculation_logs`. |
| POST | `/api/admin/decide/{candidate}` | `AdminApiController@decideCandidate` | `auth:sanctum`, `role:admin` | `status`: required accepted/rejected; `assigned_department_id`: required if accepted | `success`, `message`, `data` or quota error | `Candidate`, `Announcement`, `OpenRecruitment`, `OpenRecruitmentQuota` | Functional | Quota check uses current schema: `candidate_type + department_id + quota`. Missing quota returns 422 instead of SQL failure. |
| POST | `/api/admin/publish` | `AdminApiController@publishAnnouncements` | `auth:sanctum`, `role:admin` | `is_published`: required boolean | `success`, `message` | `Announcement` | Functional | Updates all announcements' public visibility. |
| POST | `/api/admin/departments` | `AdminApiController@storeDepartment` | `auth:sanctum`, `role:admin` | `name`, `description`, `slug`, `personal_aspect_weight`, `organizational_aspect_weight`, `core_factor_weight`, `secondary_factor_weight`, `is_active` | `201 success`, `data` | `Departmentsbiro` | Functional | Weights are numeric 0-100. Slug defaults from name if omitted. |
| PUT | `/api/admin/departments/{department}` | `AdminApiController@updateDepartment` | `auth:sanctum`, `role:admin` | Same as store with unique exceptions | `success`, `data` | `Departmentsbiro` | Functional | Updates weights, slug, description, and active flag. |
| DELETE | `/api/admin/departments/{department}` | `AdminApiController@destroyDepartment` | `auth:sanctum`, `role:admin` | Route model | `success`, `message` | `Departmentsbiro` | Functional | Foreign key constraints may affect deletion depending on existing rows. |
| GET | `/api/admin/schedules` | `AdminApiController@getAdminSchedules` | `auth:sanctum`, `role:admin` | none | `success`, `data[]` | `InterviewSchedule` | Functional | Uses current schedule schema and loads department plus booking candidate. |
| POST | `/api/admin/schedules` | `AdminApiController@storeSchedule` | `auth:sanctum`, `role:admin` | `department_id`: required; `date`: required date; `start_time`: required H:i; `end_time`: required H:i after start; `is_blocked`: optional boolean | `201 success`, `data` | `InterviewSchedule`, `Departmentsbiro` | Functional | Creates department-owned schedule row. No interviewer pivot is used. |
| PUT | `/api/admin/schedules/{schedule}` | `AdminApiController@updateSchedule` | `auth:sanctum`, `role:admin` | Same as store | `success`, `data` | `InterviewSchedule`, `Departmentsbiro` | Functional | Updates current schedule fields only. |
| DELETE | `/api/admin/schedules/{schedule}` | `AdminApiController@destroySchedule` | `auth:sanctum`, `role:admin` | Route model | `success`, `message` | `InterviewSchedule` | Functional | Deletes schedule row. No interviewer detach call. |
| GET | `/api/admin/criteria/{department}` | `AdminApiController@getCriteria` | `auth:sanctum`, `role:admin` | Route model: `Departmentsbiro` | `success`, `data[]` | `EvaluationCriteria` | Functional | Criteria ordered by `sort_order`, then `id`. |
| POST | `/api/admin/criteria/{department}` | `AdminApiController@storeCriterion` | `auth:sanctum`, `role:admin` | `name`, `type`, `aspect`, `target_score`, optional `description`, `code`, `catatan`, `is_active`, `sort_order` | `201 success`, `data` | `EvaluationCriteria`, `Departmentsbiro` | Functional | Department-specific CRUD criterion. |
| POST | `/api/admin/criteria/{department}/reset` | `AdminApiController@resetCriteria` | `auth:sanctum`, `role:admin` | Route model: `Departmentsbiro` | `success`, `message` | `DefaultEvaluationCriteria`, `EvaluationCriteria`, `SpkCriteriaDefaults` | Functional | Deletes department criteria and recreates active defaults with department target scores. |
| PUT | `/api/admin/criteria/{criterion}` | `AdminApiController@updateCriterion` | `auth:sanctum`, `role:admin` | Same criterion fields as store | `success`, `data` | `EvaluationCriteria` | Functional | Updates department-specific criterion. |
| DELETE | `/api/admin/criteria/{criterion}` | `AdminApiController@destroyCriterion` | `auth:sanctum`, `role:admin` | Route model | `success`, `message` | `EvaluationCriteria` | Functional | Deletes criterion. |
| GET | `/api/admin/interviewers` | `AdminApiController@getInterviewers` | `auth:sanctum`, `role:admin` | none | `success`, `data[]` | `User` | Functional | Returns users with `role = interviewer`. |
| POST | `/api/admin/interviewers` | `AdminApiController@storeInterviewer` | `auth:sanctum`, `role:admin` | `name`, `email`, `password` | `201 success`, `data` | `User` | Functional | API creates interviewer account only; department assignment is not handled here. |
| PUT | `/api/admin/interviewers/{user}` | `AdminApiController@updateInterviewer` | `auth:sanctum`, `role:admin` | `name`, `email`, optional `password` | `success`, `data` | `User` | Functional | API updates basic account fields only. |
| DELETE | `/api/admin/interviewers/{user}` | `AdminApiController@destroyInterviewer` | `auth:sanctum`, `role:admin` | Route model | `success`, `message` | `User` | Functional | Deletes user row. |

## Current Schema Contracts

### Open Recruitment Quotas

`open_recruitment_quotas` is decoupled from `open_recruitments`.

- Key: `candidate_type + department_id`
- Fields: `candidate_type`, `department_id`, `quota`
- Staff and BPH quotas are managed independently per department/biro.
- Accept/reject quota checks use the candidate's `candidate_type` and assigned department.
- Missing quota config returns an explicit error.

### Schedule API

Current `interview_schedules` fields:

- `department_id`
- `date`
- `start_time`
- `end_time`
- `is_blocked`

Removed/legacy schedule fields are not part of active schedule API contracts:

- `session_name`
- `scheduled_at`
- `location`
- `is_active`

The old `interviewer_schedule` pivot is dropped by the current schedule refactor. Interviewer schedule API uses `users.department_id`.

### Profile Matching Side Effect

`ProfileMatchingService::calculateScore()` writes/updates `spk_results` and creates `spk_calculation_logs`. `getDepartmentRankings()` calls `calculateScore()` for candidates and updates rank positions. Therefore admin/interviewer ranking endpoints/pages are not purely read-only.

### Upload/Download Behavior

- Web document uploads store on the `public` disk.
- Web document downloads check `public` disk first, then `local` disk for compatibility.
- API profile submission uses `CandidateProfileService`, which stores API-uploaded files on the `local` disk.

## Generated API Documentation Routes

These are documentation infrastructure routes, not recruitment business endpoints.

| Method | Endpoint | Controller | Middleware | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| GET | `/api/documentation` | `L5Swagger\Http\SwaggerController@api` | `api` | Generated | Swagger UI page. |
| GET | `/api/docs` | `L5Swagger\Http\SwaggerController@docs` | `api` | Generated | Swagger/OpenAPI JSON. |
| GET | `/api/docs/asset/{asset}` | `L5Swagger\Http\SwaggerAssetController@index` | `api` | Generated | Swagger static assets. |
| GET | `/api/oauth2-callback` | `L5Swagger\Http\SwaggerController@oauth2Callback` | `api` | Generated | OAuth helper callback. |

## Known API Notes

- Candidate profile API is functional against the current service/model path, but it stores identity values on `users`, not `candidates`.
- API interviewer decision route is protected by `auth:sanctum` and internally permits admin/interviewer; it is not inside the `role:interviewer` middleware group.
- Admin interviewer API does not expose `department_id` assignment; web account CRUD does.
- Some response examples are summarized rather than enumerating every loaded relationship because controllers return Eloquent models with loaded relations.
