Project Context Report
No files were edited. This is based on scanning the current Laravel project under web.
1. Project Stack
Laravel: 13.11.2
PHP: ^8.3
Auth: Laravel session auth for web, Sanctum token auth for API.
Frontend: Blade + Vite + Tailwind CSS 4 + Alpine.js.
Docs tooling: Scribe and L5 Swagger are installed.
Main structure:Controllers: web/app/Http/Controllers
Models: web/app/Models
Services: web/app/Services
Views: web/resources/views
Routes: web/routes/web.php, web/routes/api.php

2. Routes
Public web routes:
Method	URI	Name	Controller
GET	/	landing	LandingWebController@index
GET	/announcements	public.announcements	PublicAnnouncementController@showAcceptedList
GET	/docs/blade	docs.blade	BladeDocsController@index
GET	/docs	scribe	Scribe docs

Guest web routes:
Method	URI	Name
GET/POST	/login	login, login.post
GET/POST	/register	user.register.view, user.register.post

Authenticated web routes:
Group	Middleware	Main Routes
Profile/logout	auth	/logout, /profile, /profile/password
Candidate	auth, role:candidate	OTP, candidate profile, dashboard, apply, preferences, experience, skills, documents, schedule
Interviewer	auth, role:interviewer	dashboard, pendaftaran, schedules, profile matching, grade, criteria CRUD
Admin	auth, role:admin	dashboard, pendaftaran, open recruitment, schedules, pengumuman, profile matching, default criteria, departments, accounts

Public API routes:
Method	URI	Controller
GET	/api/landing	LandingApiController@index
GET	/api/departments	CandidateApiController@getDepartments
POST	/api/register	CandidateApiController@register
POST	/api/login	AuthApiController@login
GET	/api/announcements	PublicAnnouncementApiController@getAcceptedList

Protected API routes:
Middleware: auth:sanctum
Candidate APIs also use role:candidate
Interviewer APIs use role:interviewer
Admin APIs use role:admin
Admin/interviewer decision API has /api/interviewer/decide/{candidate} outside a role group but still inside auth:sanctum.
3. Roles And Access Control
Roles:
admin
interviewer
candidate
Implementation:
Stored in users.role.
Middleware alias: role -> App\Http\Middleware\EnsureUserHasRole.
Middleware checks exact role equality: Auth::user()->role !== $role.
JSON/API requests receive 403 JSON response.
Web requests abort with 403.
Public pages:
Landing
Public announcements
Login/register pages for guests
Docs pages
Protected pages:
Candidate dashboard/profile flow requires auth + role:candidate.
Admin pages require auth + role:admin.
Interviewer pages require auth + role:interviewer.
4. Existing Models And Tables
Area	Model	Table	Important Fields / Relationships
Account	User	users	name, email, password, role, department_id, candidate identity fields. Has one Candidate, has one OTP, belongs to department.
Candidate	Candidate	candidates	user_id, candidate_type, essay/file fields, status. Belongs to user, has choices, schedule, educations, organizations, committees, skills, facilities, evaluations, announcement, SPK results.
Department/Biro	Departmentsbiro	departmentsbiro	name, slug, description, weights, contact_person, is_active. Has criteria, choices, schedules, SPK results, agendas, work programs, quotas.
Department agenda	DepartmentAgenda	department_agendas	Agenda profile content per department.
Work program	DepartmentWorkProgram	department_work_programs	Program kerja per department.
Candidate choices	CandidateDepartmentChoice	candidate_departmentsbiro	candidate_id, departmentsbiro_id, choice_order.
Default criteria	DefaultEvaluationCriteria	default_evaluation_criteria	Default SPK criteria template.
Department criteria	EvaluationCriteria	evaluation_criteria	Department-specific criteria, target score, factor type, aspect.
Scores	Evaluation	evaluations	One score per candidate + department + criteria, last interviewer_id, version.
SPK result	SpkResult	spk_results	Final score, aspect/factor scores, rank, JSON calculation details.
Gap weights	SpkGapWeight	spk_gap_weights	Gap to mapped weight.
SPK logs	SpkCalculationLog	spk_calculation_logs	Calculation run history.
Schedule	InterviewSchedule	interview_schedules	department_id, date, start_time, end_time, is_blocked.
Candidate booking	CandidateInterviewSchedule	candidate_interview_schedules	One selected schedule per candidate.
Announcement	Announcement	announcements	candidate_id, assigned_department_id, status, is_published.
Open recruitment	OpenRecruitment	open_recruitments	candidate_type, starts_at, ends_at, status, interview details.
Quota	OpenRecruitmentQuota	open_recruitment_quotas	candidate_type, department_id, quota.
Quota log	OpenRecruitmentQuotaLog	open_recruitment_quota_logs	Old/new quota audit.
Extension	OpenRecruitmentExtension	open_recruitment_extensions	Old/new dates, reason, admin user.
OTP	EmailVerificationOtp	email_verification_otps	Hashed code, attempts, expiry, consumed timestamp.

5. Current Feature Status
Feature	Status	Notes
Landing page	Fully implemented	Uses real active departments, agendas, work programs, open recruitment public cards.
Login	Fully implemented	Web session auth, redirects by role.
Register account	Fully implemented	Candidate account creation + OTP send.
Email OTP	Fully implemented	Web + API OTP service exists.
Candidate registration	Partially implemented	Web flow is multi-page identity -> dashboard -> apply/preferences/experience/documents/schedule. API full-profile flow appears inconsistent with current DB.
Admin dashboard	Fully implemented	Uses real DB counts and widgets.
Admin pendaftaran	Partially implemented	Real page/query exists, but some filters search candidates.nim/prodi even current schema stores those on users.
Account CRUD	Fully implemented	Admin account CRUD with department assignment for interviewers.
Department/Biro CRUD	Fully implemented	Includes weights, active flag, contact person, agenda, work program.
Default Criteria CRUD	Fully implemented	Admin CRUD exists.
Profile Matching	Partially implemented	Service calculates and stores results; admin/interviewer pages exist. Some recalculation occurs during page loads/ranking.
Open Recruitment Staff	Fully implemented	Admin can create/update/open/close/extend.
Open Recruitment BPH	Fully implemented	Same as staff.
Quota Staff/BPH	Fully implemented as data management	Quotas/logs exist; enforcement during acceptance is not implemented.
Interview Session	Partially implemented	Admin schedule matrix/generation exists, candidate booking exists. Some API schedule methods still use old columns.
Pengumuman	Fully implemented	Admin manage/publish; public announcement page exists.

6. Admin Dashboard
Route:
GET /admin/dashboard
Name: admin.dashboard
Middleware: auth, role:admin
Controller: AdminWebController@dashboard
Blade: web/resources/views/admin/dashboard.blade.php
Data passed:
$stats: candidate/user/department/default criteria counts.
$candidateSummary: total, staff, bph, registered, scheduled, evaluated, completed.
$recentCandidates: latest 5 candidates.
$firstChoiceInterest: top 5 first-choice departments.
$secondChoiceInterest: top 5 second-choice departments.
$departmentInterest: top 8 combined interest data, passed but not clearly central in current view.
$readiness: default criteria, departments, candidates, evaluation count, SPK result count.
$interviewProgress: sessions, scheduled candidates, completed/pending interviews.
$announcementStatus: total/published/unpublished/latest update.
$openRecruitment: availability and message from OpenRecruitmentService.
$quickActions: still passed by controller, but current dashboard view does not show Quick Actions based on the scan.
$todaySchedules: today’s booked interview schedules.
$topCandidates: first 3 candidates with evaluations; comment says “Mock for now” in interviewer controller, but admin dashboard query uses real candidate/evaluation data.
Real-data widgets:
Top stats
Candidate summary/donut
Interview progress
Open recruitment status
Department choice interest
Readiness counts
Recent candidates
Today schedules
Top candidates with evaluations
Unavailable/missing states:
No candidate choice data
No registered candidates
No schedules today
No evaluated candidates
Open recruitment not configured/open
7. Profile Matching/SPK
Service:
web/app/Services/ProfileMatchingService.php
Inputs:
Candidate
Departmentsbiro
Optional calculatedBy user id
Active evaluation_criteria for that department
Existing evaluations
spk_gap_weights
Department weights:personal_aspect_weight
organizational_aspect_weight
core_factor_weight
secondary_factor_weight

Calculation:
For each active criterion:actual score from evaluations, default 0
gap = actual - target
mapped weight from spk_gap_weights, fallback 1.0

Groups by aspect: personal, organizational
Groups by factor: core, secondary
Averages each group
Applies factor weights and aspect weights
Produces final score rounded to 4 decimals
Outputs:
SpkResult::updateOrCreate() per candidate + department.
Stores score fields and calculation_details JSON.
Creates SpkCalculationLog with trigger/status/count/duration.
getDepartmentRankings() recalculates all candidates who chose the department, sorts, and updates rank_position.
Dependent tables:
candidates
departmentsbiro
candidate_departmentsbiro
evaluation_criteria
evaluations
spk_gap_weights
spk_results
spk_calculation_logs
8. Important Assumptions To Avoid
Do not assume candidate identity fields are on candidates; current migrations/models place them on users.
Do not assume API candidate profile submission is aligned with current DB. It validates unique:candidates,nim and CandidateProfileService passes identity fields into Candidate::create(), while the current candidates table does not have those columns.
Do not assume API schedule endpoints are aligned with current schedule schema. Some API code still references old fields like is_active, scheduled_at, session_name, and location.
Do not assume interviewer_schedule exists in final schema; latest migration drops it.
Do not assume quotas are tied to an open recruitment row; current quota table is keyed by candidate_type + department_id.
Do not assume inactive departments are public; landing/API department list filters active departments.
Do not assume Profile Matching is read-only; ranking calls currently calculate and write spk_results and logs.
Do not assume all redirects are valid: CandidateWebController::redirectCandidateUser() references interviewer.schedule, but the route name is interviewer.schedules.
Do not assume admin pendaftaran search is fully schema-safe; parts of the query reference candidate columns that are currently user columns.