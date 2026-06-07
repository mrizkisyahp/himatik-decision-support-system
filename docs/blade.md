# Blade Documentation

Last reviewed: 2026-06-07

Source of truth:

- `web/routes/web.php`
- `php artisan route:list`
- `web/app/Http/Controllers/Web/*`
- `web/resources/views/**/*.blade.php`
- Current migrations/models

This document covers Blade-rendered web pages only: page purpose, route/view mapping, access role, variables passed to views, status, and known limitations. API endpoints are documented separately in `docs/api.md`.

## Shared Layouts

| View | Purpose | Used by | Status | Notes |
| --- | --- | --- | --- | --- |
| `resources/views/admin/layout.blade.php` | Admin shell with sidebar navigation. | Admin pages | Functional | Groups Dashboard, Recruitment, Decision Support, Master Data, Logout. |
| `resources/views/interviewer/layout.blade.php` | Interviewer shell/sidebar. | Interviewer pages | Functional | Uses interviewer role context and department-oriented pages. |
| `resources/views/candidate/layout.blade.php` | Candidate layout. | Candidate pages | Functional | Used by candidate dashboard/flow pages. |

## Public Pages

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Landing | `GET /` | `landing` | `LandingWebController@index` | `resources/views/landing.blade.php` | Public | `$departments`, `$openRecruitmentCards` | Public HIMATIK profile, departments/biro showcase, open recruitment CTA, and portal entry. | Functional | Uses active departments only. Landing recruitment cards come from `OpenRecruitmentService::openPublicCards()`; closed/not-current cards are not shown. |
| Public announcements | `GET /announcements` | `public.announcements` | `PublicAnnouncementController@showAcceptedList` | `resources/views/public/announcements.blade.php` | Public | `$announcements`, `$isPublished` | Public accepted/rejected announcement board. | Functional | Public page exposes final outcome only, not score breakdowns. |
| Blade docs UI | `GET /docs/blade` | `docs.blade` | `BladeDocsController@index` | `resources/views/docs/blade.blade.php` | Public | `$groups` | Runtime web-page documentation UI. | Functional | Metadata is maintained in `BladeDocsController`. |
| Scribe API docs UI | `GET /docs` | `scribe` | Scribe package route | `resources/views/scribe/index.blade.php` | Public | Package-generated | API documentation UI. | Generated | API contract is documented in `docs/api.md`. |
| Swagger UI | `GET /api/documentation` | `l5-swagger.default.api` | `L5Swagger\Http\SwaggerController@api` | `resources/views/vendor/l5-swagger/index.blade.php` | Public | Package-generated | Swagger UI. | Generated | Package route/view, not recruitment UI. |

## Guest Auth Pages

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Login | `GET /login` | `login` | `AuthWebController@showLoginForm` | `resources/views/auth/login.blade.php` | Guest | none | Login page for all roles. | Functional | `POST /login` authenticates and redirects by role. |
| Account registration | `GET /register` | `user.register.view` | `CandidateWebController@showUserRegisterForm` | `resources/views/auth/register.blade.php` | Guest | `$candidateType` | Candidate account registration. | Functional | Creates `users` account and sends OTP on POST. Does not create a `candidates` row. |

## Authenticated Common Pages

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Profile settings | `GET /profile` | `profile.edit` | `ProfileWebController@edit` | `resources/views/profile/edit.blade.php` | Authenticated user | `$user`, `$layout` | Account profile/password settings. | Functional | Layout is selected from user role. |
| Document download | `GET /documents/download/{candidate}/{field}` | `documents.download` | `CandidateWebController@downloadDocument` | none | Authenticated admin/interviewer/owner candidate | none | Streams candidate document/signature files. | Functional | Checks `public` disk first, then `local` disk fallback for compatibility. |

## Candidate Pages

Current web candidate flow:

`account register -> OTP verify -> identity form -> candidate dashboard -> apply -> preferences -> experience -> skills/facilities -> documents -> signatures -> schedule`

Identity fields are currently stored on `users`, not `candidates`: `name`, `nickname`, `nim`, `prodi`, `kelas`, `phone`, `address`.

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Email OTP | `GET /verify-email` | `candidate.otp.view` | `CandidateWebController@showOtpForm` | `resources/views/auth/verify-otp.blade.php` | Auth candidate | none | Verify account email using OTP. | Functional | `POST /verify-email`; resend via `POST /verify-email/resend`. |
| Identity form | `GET /register-candidate` | `candidate.register.view` | `CandidateWebController@showCandidateRegisterForm` | `resources/views/candidate/register.blade.php` | Auth candidate | `$departments`, `$candidateType` | First candidate identity/contact form. | Functional | Saves identity/contact to `users`; redirects to dashboard. |
| Candidate dashboard | `GET /candidate/dashboard` | `candidate.dashboard` | `CandidateWebController@showDashboard` | `resources/views/candidate/dashboardcandidate.blade.php` | Auth candidate | `$candidate`, `$announcement`, `$openRecruitments` | Candidate home/status page and recruitment entry. | Functional | Requires verified email and completed `users.nim`. |
| Apply start | `GET /candidate/apply/{openRecruitment}` | `candidate.apply.start` | `CandidateWebController@showApplyStartPage` | `resources/views/candidate/start.blade.php` | Auth candidate, `check.oprec.active` | `$oprec` | Starts Staff/BPH application path. | Functional | Uses selected `OpenRecruitment`. |
| Preferences | `GET /candidate/preferences` | `candidate.preferences.view` | `CandidateWebController@showPreferencesForm` | `resources/views/candidate/preferences.blade.php` | Auth candidate, `check.oprec.active` | `$departments`, `$oprec`, `$candidate` | Department choices and essay answers. | Functional | Creates candidate row if missing and stores choices in `candidate_departmentsbiro`. |
| Experience | `GET /candidate/experience` | `candidate.experience.view` | `CandidateWebController@showExperienceForm` | `resources/views/candidate/experience.blade.php` | Auth candidate, `check.oprec.active` | `$candidate` | Education, organization, and committee records. | Functional | Add/delete routes exist for each repeatable section. |
| Skills/facilities | `GET /candidate/skills-facilities` | `candidate.skills.view` | `CandidateWebController@showSkillsFacilitiesForm` | `resources/views/candidate/skills_facilities.blade.php` | Auth candidate, `check.oprec.active` | `$candidate` | Skills and owned facilities. | Functional | Skill/facility add/delete routes exist. |
| Documents | `GET /candidate/documents` | `candidate.documents.view` | `CandidateWebController@showDocumentsForm` | `resources/views/candidate/documents.blade.php` | Auth candidate, `check.oprec.active` | `$candidate` | Upload photo, Instagram proof, YouTube proof, and political statement. | Functional | Web uploads store on `public` disk in `candidate_documents`. |
| Signatures | `GET /candidate/signatures` | `candidate.signatures.view` | `CandidateWebController@showSignaturesForm` | `resources/views/candidate/signatures.blade.php` | Auth candidate, `check.oprec.active` | `$candidate` | Upload candidate and parent signatures. | Functional | Web uploads store on `public` disk in `candidate_signatures`. |
| Schedule | `GET /schedule` | `candidate.schedule.view` | `CandidateWebController@showScheduleForm` | `resources/views/candidate/schedule.blade.php` | Auth candidate | `$candidate`, `$announcement`, `$dssResults`, `$currentBookedSlotId`, `$openRecruitment`, `$dates`, `$timeSlots`, `$schedules`, `$firstChoiceDepartmentId` | Candidate first-choice department schedule selection/status. | Functional | Current schedule schema: `date`, `start_time`, `end_time`, `is_blocked`. |
| Interview detail | `GET /candidate/interview-detail` | `candidate.interview.detail` | `CandidateWebController@showInterviewDetail` | `resources/views/candidate/interview-detail.blade.php` | Auth candidate | `$candidate`, `$schedule` | Candidate interview schedule detail. | Functional | Uses selected candidate interview schedule. |
| Registration form preview | `GET /candidate/registration-form` | `candidate.registration.form` | `CandidateWebController@showRegistrationForm` | `resources/views/candidate/registration-form.blade.php` | Auth candidate | `$candidate` | Candidate registration profile preview. | Functional | Redirects to dashboard if candidate row is missing. |
| Registration attachments preview | `GET /candidate/registration-attachments` | `candidate.registration.attachments` | `CandidateWebController@showRegistrationAttachments` | `resources/views/candidate/registration-attachments.blade.php` | Auth candidate | `$candidate` | Candidate uploaded attachment preview. | Functional | Redirects to dashboard if candidate row is missing. |

## Interviewer Pages

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Dashboard | `GET /interviewer/dashboard` | `interviewer.dashboard` | `InterviewerWebController@dashboard` | `resources/views/interviewer/dashboard.blade.php` | Auth interviewer | `$todaySchedules`, `$topCandidates`, `$department` | Interviewer overview for assigned department. | Partial | `topCandidates` is a latest-candidates preview, not final SPK ranking. |
| Pendaftaran | `GET /interviewer/pendaftaran` | `interviewer.registrations` | `InterviewerWebController@registrations` | `resources/views/interviewer/registrations.blade.php` | Auth interviewer | `$candidates` | Candidate registration list. | Partial | Current controller loads latest candidates broadly; department scoping is unclear. |
| Schedules | `GET /interviewer/schedules` | `interviewer.schedules` | `InterviewerWebController@schedules` | `resources/views/interviewer/schedules.blade.php` | Auth interviewer | `$departments`, `$activeDepartmentId`, `$schedules`, `$dates`, `$timeSlots`, `$department` | Schedule matrix for interviewer department. | Functional | Uses current schedule fields and block toggle. |
| Profile Matching | `GET /interviewer/profile-matching` | `interviewer.profile-matching` | `InterviewerWebController@profileMatching` | `resources/views/interviewer/profile-matching.blade.php` | Auth interviewer | `$department`, `$selectedDepartment`, `$criteria`, `$rankings`, `$candidates`, `$existingScores`, `$error`, `$search` | Interviewer scoring/ranking workspace. | Functional | Profile Matching ranking calls may write `spk_results` and logs. |
| Grade | `GET /interviewer/grade/{candidate}/{department}` | `interviewer.grade.view` | `InterviewerWebController@showGradingForm` | `resources/views/interviewer/grade.blade.php` | Auth interviewer | `$candidate`, `$department`, `$criteria`, `$existingScores`, `$announcement`, `$departments` | Shared evaluation score form. | Functional | One score row per candidate/department/criteria; `interviewer_id` is last updater. |
| Criteria | `GET /interviewer/criteria` | `interviewer.criteria` | `InterviewerWebController@criteria` | `resources/views/interviewer/criteria.blade.php` | Auth interviewer | `$department`, `$criteria`, `$isDirty` | Department criteria CRUD for interviewer department. | Functional | Supports reset to defaults and department weight updates. |

## Admin Pages

| Page | Route | Name | Controller | View | Access | Variables | Purpose | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Dashboard | `GET /admin/dashboard` | `admin.dashboard` | `AdminWebController@dashboard` | `resources/views/admin/dashboard.blade.php` | Auth admin | `$stats`, `$candidateSummary`, `$recentCandidates`, `$firstChoiceInterest`, `$secondChoiceInterest`, `$departmentInterest`, `$readiness`, `$interviewProgress`, `$announcementStatus`, `$openRecruitment`, `$quickActions`, `$todaySchedules`, `$topCandidates` | Compact admin overview. | Functional | Uses real DB data. `$quickActions` is passed but current UI no longer uses a Quick Actions section. |
| Pendaftaran | `GET /admin/pendaftaran` | `admin.registrations` | `AdminWebController@registrations` | `resources/views/admin/registrations.blade.php` | Auth admin | `$candidates`, `$departments`, `$registrationSummary`, `$statuses` | Candidate registration management. | Partial | Current search/filter path may still reference candidate columns that now exist on `users`. |
| Open Recruitment | `GET /admin/open-recruitment` | `admin.open-recruitment` | `AdminWebController@openRecruitment` | `resources/views/admin/open-recruitment.blade.php` | Auth admin | `$openRecruitments`, `$departments`, `$quotasByType`, `$quotaLogs`, `$openRecruitmentService` | Create/open/close/extend Staff/BPH recruitment and manage quotas. | Functional | Quotas are keyed by `candidate_type + department_id`, not `open_recruitment_id`. |
| Sesi Interview | `GET /admin/schedules` | `admin.schedules` | `AdminWebController@listSchedules` | `resources/views/admin/schedules.blade.php` | Auth admin | `$departments`, `$activeDepartmentId`, `$schedules`, `$dates`, `$timeSlots` | Department schedule matrix/generation. | Functional | Current schedule schema uses `department_id`, `date`, `start_time`, `end_time`, `is_blocked`. |
| Pengumuman | `GET /admin/pengumuman` | `admin.announcements` | `AdminWebController@announcements` | `resources/views/admin/announcements.blade.php` | Auth admin | `$announcements`, `$isPublished`, `$departments`, `$search`, `$statusFilter` | Final decision board and publish toggle. | Functional | Public announcement hides score details. |
| Profile Matching | `GET /admin/profile-matching` | `admin.profile-matching` | `AdminWebController@profileMatching` | `resources/views/admin/profile-matching.blade.php` | Auth admin | `$departments`, `$selectedDepartment`, `$criteria`, `$rankings`, `$candidates`, `$existingScores`, `$error`, `$search` | Admin scoring/ranking workspace. | Functional | Ranking calls are not read-only; they may write `spk_results` and `spk_calculation_logs`. |
| Default Criteria | `GET /admin/default-criteria` | `admin.default-criteria` | `AdminWebController@defaultCriteria` | `resources/views/admin/default-criteria.blade.php` | Auth admin | `$criteria` | Default criteria CRUD. | Functional | Used when resetting department criteria. |
| Departemen & Biro | `GET /admin/departemen-biro` | `admin.departments` | `AdminWebController@departments` | `resources/views/admin/departments.blade.php` | Auth admin | `$departments` | Department/biro master data list. | Functional | Includes active flag, slug, weights, contact person, and description. |
| Department detail | `GET /admin/departments/{department}` | `admin.departments.manage` | `AdminWebController@manageDepartment` | `resources/views/admin/department-detail.blade.php` | Auth admin | `$department` | Agenda and work program management. | Functional | Loads `agendas` and `workPrograms`. |
| Department criteria | `GET /admin/departments/{department}/criteria` | `admin.criteria` | `AdminWebController@listCriteria` | `resources/views/admin/criteria.blade.php` | Auth admin | `$department`, `$criteria`, `$isDirty` | Department-specific criteria CRUD/reset. | Functional | Criteria are mutable per department. |
| Account | `GET /admin/accounts` | `admin.accounts` | `AdminAccountController@index` | `resources/views/admin/accounts.blade.php` | Auth admin | `$users`, `$departments`, `$currentRole`, `$title` | User/account management. | Functional | Supports admin, interviewer, candidate accounts; interviewer department assignment uses `users.department_id`. |

## Known Web Notes

- Candidate identity is split: account identity fields are on `users`, while application/profile fields are on `candidates` and child tables.
- Web document/signature uploads use the `public` disk; downloads check `public` first and `local` second.
- API full-profile submission uses `CandidateProfileService`, which stores API-uploaded files on the `local` disk.
- Profile Matching pages may calculate/write results while rendering rankings.
- Admin pendaftaran search may need future cleanup because identity search should use `users` fields.
- Interviewer pendaftaran department scoping is unclear from current controller scan.

## Non-Page Templates

| View | Purpose | Status |
| --- | --- | --- |
| `resources/views/emails/candidate-otp.blade.php` | Candidate OTP email body | Functional |
