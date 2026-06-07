# API Documentation

Last reviewed: 2026-06-07

Base path: `/api`

Source of truth:

- `web/routes/api.php`
- `php artisan route:list`
- `web/app/Http/Controllers/Api/*`
- `web/app/Support/CandidateProfileRules.php`
- Current project context scan

This document covers API endpoints only: method, endpoint, controller/action, middleware, request, validation, response, related models/services, status, and known inconsistencies. Blade/web pages are documented in `docs/blade.md`.

## Auth And Middleware

- Public API routes do not require authentication.
- Protected API routes use `auth:sanctum`.
- Role middleware is `role:candidate`, `role:interviewer`, or `role:admin`.
- Role middleware checks exact `users.role` value through `EnsureUserHasRole`.
- Roles: `admin`, `interviewer`, `candidate`.
- Sanctum token auth is used by API login/register flows.

## Public API Routes

| Method | Endpoint | Controller | Middleware | Request / Validation | Response | Related Models/Services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/landing` | `LandingApiController@index` | `api` | none | Public landing payload. Shape unclear from current scan. | `Departmentsbiro`, `OpenRecruitmentService` | Fully implemented | Expected to expose active departments and current public recruitment cards. |
| GET | `/api/departments` | `CandidateApiController@getDepartments` | `api` | none | `{ success: true, data: [...] }`; fields: `id`, `name`, `slug`, `description` | `Departmentsbiro` | Fully implemented | Filters active departments only. |
| POST | `/api/register` | `CandidateApiController@register` | `api` | `email`, `nama`, `password`, `password_confirmation`; validates unique email and confirmed min-8 password | `201` with `success`, `message`, Sanctum `token`, `user`, `next_step: verify_email` | `User`, `CandidateOtpService` | Fully implemented | Creates user account only; no `candidates` row. |
| POST | `/api/login` | `AuthApiController@login` | `api` | `email`, `password` | Auth token and role/next-step metadata. Exact shape unclear from current scan. | `User` | Fully implemented | Login redirects/next-step logic differs by role. |
| GET | `/api/announcements` | `PublicAnnouncementApiController@getAcceptedList` | `api` | none | Public accepted announcements payload. Exact shape unclear from current scan. | `Announcement`, `Candidate`, `Departmentsbiro` | Fully implemented | Public output should not expose score details. |

## Protected Common API Routes

| Method | Endpoint | Controller | Middleware | Request / Validation | Response | Related Models/Services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/me` | `CandidateApiController@me` | `api`, `auth:sanctum` | none | `success`, `user`, `candidate`, `schedule`, `announcement`, `dss_results`, `next_step` | `User`, `Candidate`, `Announcement`, `ProfileMatchingService` | Fully implemented | Loads candidate choices, repeatable sections, schedule, SPK results. May calculate DSS details when final result is published/evaluated. |
| POST | `/api/logout` | `AuthApiController@logout` | `api`, `auth:sanctum` | none | Success logout message | `User` | Fully implemented | Revokes current access token. |
| POST | `/api/email/verify-otp` | `AuthApiController@verifyOtp` | `api`, `auth:sanctum` | `otp`: `required|digits:6` | Success message and next-step metadata; invalid/expired returns error | `CandidateOtpService`, `EmailVerificationOtp`, `User` | Fully implemented | Candidate role enforced inside controller. |
| POST | `/api/email/resend-otp` | `AuthApiController@resendOtp` | `api`, `auth:sanctum` | none | Success message or already-verified/error response | `CandidateOtpService`, `EmailVerificationOtp`, `User` | Fully implemented | Candidate role enforced inside controller. |

## Protected Candidate API Routes

| Method | Endpoint | Controller | Middleware | Request / Validation | Response | Related Models/Services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| POST | `/api/candidate/profile` | `CandidateApiController@storeProfile` | `api`, `auth:sanctum`, `role:candidate` | Full multipart profile from `CandidateProfileRules`: `candidate_type`, identity fields, choices, essays, required uploads, repeatable arrays | `201` with created `candidate` and `next_step: schedule_selection`; errors for unverified, duplicate profile, closed oprec | `CandidateProfileService`, `CandidateProfileRules`, `OpenRecruitmentService`, `Candidate` | Partially implemented / inconsistent | Known issue: validation uses `unique:candidates,nim`, but current schema stores `nim` on `users`. `CandidateProfileService` also passes identity fields into `Candidate::create()`, while current `candidates` table does not include those fields. |
| GET | `/api/schedules` | `CandidateApiController@getAvailableSchedules` | `api`, `auth:sanctum`, `role:candidate` | none | `success`, `data`, `current_booked_slot_id` | `Candidate`, `InterviewSchedule`, `CandidateInterviewSchedule` | Partially implemented / inconsistent | Known issue: current code references old schedule fields: `is_active`, `scheduled_at`, `session_name`, `location`. Current migration schema uses `is_blocked`, `date`, `start_time`, `end_time`. |
| POST | `/api/schedules/book` | `CandidateApiController@bookSchedule` | `api`, `auth:sanctum`, `role:candidate` | `schedule_id`: `required|exists:interview_schedules,id` | Success booking payload or candidate/wrong-department/booked errors | `Candidate`, `InterviewSchedule`, `CandidateInterviewSchedule` | Partially implemented / inconsistent | Known issue: code queries `InterviewSchedule::where('is_active', true)`, but current schedule schema uses `is_blocked`. |

## Protected Interviewer API Routes

| Method | Endpoint | Controller | Middleware | Request / Validation | Response | Related Models/Services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/interviewer/schedules` | `InterviewerApiController@getSchedules` | `api`, `auth:sanctum`, `role:interviewer` | none | Interviewer schedule payload. Exact shape unclear from current scan. | `InterviewSchedule`, `CandidateInterviewSchedule`, `User` | Partially implemented | Route requires interviewer role; controller may also check/allow admin internally, unclear from current scan. |
| GET | `/api/interviewer/grade/{candidate}/{department}` | `InterviewerApiController@getGradingDetails` | `api`, `auth:sanctum`, `role:interviewer` | Route models: `Candidate`, `Departmentsbiro` | Candidate, department, active criteria, existing scores | `Candidate`, `Departmentsbiro`, `EvaluationCriteria`, `Evaluation` | Fully implemented | Candidate must have chosen department; existing scores include version according to current scan. |
| POST | `/api/interviewer/grade/{candidate}/{department}` | `InterviewerApiController@submitScores` | `api`, `auth:sanctum`, `role:interviewer` | `scores`: `required|array`; `scores.*`: `required|integer|min:1|max:5` | Success/error save response | `Candidate`, `Departmentsbiro`, `EvaluationCriteria`, `Evaluation` | Fully implemented | Stores one shared score per candidate/department/criteria. `interviewer_id` is last updater; `version` increments. |
| POST | `/api/interviewer/decide/{candidate}` | `AdminApiController@decideCandidate` | `api`, `auth:sanctum` | `status`: `accepted/rejected`; `assigned_department_id` required if accepted | Decision response | `Candidate`, `Announcement`, `Departmentsbiro` | Fully implemented | This route is outside interviewer role group. Controller explicitly allows admin/interviewer, but route middleware itself is only `auth:sanctum`. |

## Protected Admin API Routes

| Method | Endpoint | Controller | Middleware | Request / Validation | Response | Related Models/Services | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/admin/stats` | `AdminApiController@getStats` | `api`, `auth:sanctum`, `role:admin` | none | Admin stats payload | `Candidate`, `Departmentsbiro` | Fully implemented | Controller also checks admin role internally. |
| GET | `/api/admin/rankings/{department}` | `AdminApiController@getRankings` | `api`, `auth:sanctum`, `role:admin` | Route model: `Departmentsbiro` | Department ranking payload | `Departmentsbiro`, `Announcement`, `ProfileMatchingService` | Fully implemented | Important: Profile Matching ranking calls are not read-only in the service; calculation/ranking can write `spk_results` and logs. |
| POST | `/api/admin/decide/{candidate}` | `AdminApiController@decideCandidate` | `api`, `auth:sanctum`, `role:admin` | `status`: `accepted/rejected`; `assigned_department_id` required if accepted | Decision response | `Candidate`, `Announcement`, `Departmentsbiro` | Fully implemented | Quota enforcement during accept/reject is not implemented in current scan. |
| POST | `/api/admin/publish` | `AdminApiController@publishAnnouncements` | `api`, `auth:sanctum`, `role:admin` | `is_published`: `required|boolean` | Publish/unpublish response | `Announcement` | Fully implemented | Updates announcement publication state. |
| POST | `/api/admin/departments` | `AdminApiController@storeDepartment` | `api`, `auth:sanctum`, `role:admin` | Department fields and weights. Exact validation unclear from current scan. | Created department response | `Departmentsbiro` | Fully implemented | Slug/active/weight behavior should follow controller validation. |
| PUT | `/api/admin/departments/{department}` | `AdminApiController@updateDepartment` | `api`, `auth:sanctum`, `role:admin` | Route model plus department fields | Updated department response | `Departmentsbiro` | Fully implemented | Exact validation unclear from current scan. |
| DELETE | `/api/admin/departments/{department}` | `AdminApiController@destroyDepartment` | `api`, `auth:sanctum`, `role:admin` | Route model | Delete response | `Departmentsbiro` | Fully implemented | Runtime may be affected by FK constraints. |
| GET | `/api/admin/schedules` | `AdminApiController@getAdminSchedules` | `api`, `auth:sanctum`, `role:admin` | none | Schedule list payload | `InterviewSchedule` | Partially implemented / inconsistent | Current API schedule controller may still reference old schedule relations/fields. |
| POST | `/api/admin/schedules` | `AdminApiController@storeSchedule` | `api`, `auth:sanctum`, `role:admin` | Schedule create fields. Current docs/controller may reference `session_name`, `scheduled_at`, `location`, `is_active`, interviewer assignment | Created schedule response | `InterviewSchedule`, `User` | Partially implemented / inconsistent | Current DB schema uses `date`, `start_time`, `end_time`, `is_blocked`; `interviewer_schedule` table is no longer in final schema. |
| PUT | `/api/admin/schedules/{schedule}` | `AdminApiController@updateSchedule` | `api`, `auth:sanctum`, `role:admin` | Route model plus schedule fields | Updated schedule response | `InterviewSchedule`, `User` | Partially implemented / inconsistent | Same old-field risk as create schedule. |
| DELETE | `/api/admin/schedules/{schedule}` | `AdminApiController@destroySchedule` | `api`, `auth:sanctum`, `role:admin` | Route model | Delete response | `InterviewSchedule` | Partially implemented / inconsistent | If controller detaches interviewers, that conflicts with final schema where `interviewer_schedule` is dropped. |
| GET | `/api/admin/criteria/{department}` | `AdminApiController@getCriteria` | `api`, `auth:sanctum`, `role:admin` | Route model: `Departmentsbiro` | Criteria payload | `Departmentsbiro`, `EvaluationCriteria` | Fully implemented | Department-specific criteria. |
| POST | `/api/admin/criteria/{department}` | `AdminApiController@storeCriterion` | `api`, `auth:sanctum`, `role:admin` | `name`, `type`, `aspect`, `target_score`, optional metadata | Created criterion response | `Departmentsbiro`, `EvaluationCriteria` | Fully implemented | Validation details unclear from current scan beyond route/controller purpose. |
| POST | `/api/admin/criteria/{department}/reset` | `AdminApiController@resetCriteria` | `api`, `auth:sanctum`, `role:admin` | Route model | Reset response | `DefaultEvaluationCriteria`, `EvaluationCriteria`, `SpkCriteriaDefaults` | Fully implemented | Deletes department criteria and recreates from active defaults. |
| PUT | `/api/admin/criteria/{criterion}` | `AdminApiController@updateCriterion` | `api`, `auth:sanctum`, `role:admin` | Route model plus criterion fields | Updated criterion response | `EvaluationCriteria` | Fully implemented | Department criterion update. |
| DELETE | `/api/admin/criteria/{criterion}` | `AdminApiController@destroyCriterion` | `api`, `auth:sanctum`, `role:admin` | Route model | Delete response | `EvaluationCriteria` | Fully implemented | Deletes criterion. |
| GET | `/api/admin/interviewers` | `AdminApiController@getInterviewers` | `api`, `auth:sanctum`, `role:admin` | none | Interviewer user list | `User` | Fully implemented | Admin interviewer management API. |
| POST | `/api/admin/interviewers` | `AdminApiController@storeInterviewer` | `api`, `auth:sanctum`, `role:admin` | `name`, `email`, `password`; exact department assignment support unclear | Created interviewer response | `User` | Fully implemented | Current web account CRUD supports `department_id`; API support unclear from current scan. |
| PUT | `/api/admin/interviewers/{user}` | `AdminApiController@updateInterviewer` | `api`, `auth:sanctum`, `role:admin` | Route model plus user fields | Updated interviewer response | `User` | Fully implemented | Exact validation unclear from current scan. |
| DELETE | `/api/admin/interviewers/{user}` | `AdminApiController@destroyInterviewer` | `api`, `auth:sanctum`, `role:admin` | Route model | Delete response | `User` | Fully implemented | Deletes interviewer user. |

## Generated API Documentation Routes

These are generated documentation infrastructure routes, not recruitment business APIs.

| Method | Endpoint | Controller | Middleware | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| GET | `/api/documentation` | `L5Swagger\Http\SwaggerController@api` | `api` | Generated | Swagger UI page. |
| GET | `/api/docs` | `L5Swagger\Http\SwaggerController@docs` | `api` | Generated | Swagger/OpenAPI payload. |
| GET | `/api/docs/asset/{asset}` | `L5Swagger\Http\SwaggerAssetController@index` | `api` | Generated | Swagger static assets. |
| GET | `/api/oauth2-callback` | `L5Swagger\Http\SwaggerController@oauth2Callback` | `api` | Generated | OAuth2 helper callback. |

## Known API Inconsistencies

- Candidate identity fields are currently on `users`, not `candidates`.
- API candidate profile submission appears inconsistent with the current DB schema:
  - `CandidateProfileRules` validates `unique:candidates,nim`.
  - `CandidateProfileService` passes `nickname`, `nim`, `prodi`, `kelas`, `phone`, and `address` into `Candidate::create()`.
  - Current migration/model scan shows those identity fields live on `users`.
- Some API schedule methods still reference old schedule fields:
  - `is_active`
  - `scheduled_at`
  - `session_name`
  - `location`
- Current schedule schema uses:
  - `date`
  - `start_time`
  - `end_time`
  - `is_blocked`
- `interviewer_schedule` is not part of the final current schema because the latest schedule refactor migration drops it.
- Profile Matching ranking calls are not read-only. Service calls calculate and write `spk_results` and `spk_calculation_logs`.
- Quota enforcement during candidate accept/reject is not implemented in current API scan.
- Several response shapes are unclear from the current scan because full controller response bodies were not all expanded in the project context report.
