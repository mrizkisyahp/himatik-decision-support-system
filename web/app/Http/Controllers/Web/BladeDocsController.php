<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class BladeDocsController extends Controller
{
    public function index()
    {
        $groups = [
            'Public' => [
                $this->route(
                    id: 'landing',
                    uri: '/',
                    name: 'landing',
                    middleware: ['public'],
                    view: 'landing.blade.php',
                    controller: 'Web\LandingWebController',
                    action: 'index',
                    description: 'Public HIMATIK profile, Department/Biro showcase, open recruitment CTA, and portal entry.',
                    variables: [
                        ['$departments', 'Collection<Departmentsbiro>', 'Active public departments/biros with agendas and work programs.'],
                        ['$openRecruitmentCards', 'Collection<array>', 'Currently open BPH/Staff recruitment cards from OpenRecruitmentService.'],
                    ],
                    models: ['Departmentsbiro', 'DepartmentAgenda', 'DepartmentWorkProgram', 'OpenRecruitment']
                ),
                $this->route(
                    id: 'public-announcements',
                    uri: '/announcements',
                    name: 'public.announcements',
                    middleware: ['public'],
                    view: 'public/announcements.blade.php',
                    controller: 'Web\PublicAnnouncementController',
                    action: 'showAcceptedList',
                    description: 'Public accepted candidate announcement page. It only exposes basic final outcome data.',
                    variables: [
                        ['$announcements', 'Collection<Announcement>', 'Published accepted announcements with candidate and assigned department.'],
                        ['$isPublished', 'bool', 'Whether the public announcement board is currently published.'],
                    ],
                    models: ['Announcement', 'Candidate', 'Departmentsbiro']
                ),
            ],
            'Guest Only' => [
                $this->route(
                    id: 'login',
                    uri: '/login',
                    name: 'login',
                    middleware: ['guest'],
                    view: 'auth/login.blade.php',
                    controller: 'Web\AuthWebController',
                    action: 'showLoginForm',
                    description: 'Minimal login page for all roles. Role redirects are handled after authentication.',
                    models: ['User'],
                    postFields: [
                        ['email', 'string', 'required|email', 'Registered email.'],
                        ['password', 'string', 'required|string', 'Account password.'],
                    ],
                    postRoute: 'POST /login -> AuthWebController@login'
                ),
                $this->route(
                    id: 'account-register',
                    uri: '/register',
                    name: 'user.register.view',
                    middleware: ['guest'],
                    view: 'auth/register.blade.php',
                    controller: 'Web\CandidateWebController',
                    action: 'showUserRegisterForm',
                    description: 'Candidate account registration step. Creates user account and sends OTP, but does not create a candidate profile yet.',
                    variables: [
                        ['$candidateType', 'string|null', 'Optional candidate type query value carried from landing CTA.'],
                    ],
                    models: ['User', 'EmailVerificationOtp'],
                    postFields: [
                        ['nama', 'string', 'required|string|max:255', 'Full name.'],
                        ['email', 'string', 'required|email|unique:users,email', 'Candidate email.'],
                        ['password', 'string', 'required|string|min:8|confirmed', 'Password.'],
                        ['candidate_type', 'string|null', 'nullable|in:staff,bph', 'Optional type forwarded to later candidate profile step.'],
                    ],
                    postRoute: 'POST /register -> CandidateWebController@registerUser'
                ),
            ],
            'Candidate (auth)' => [
                $this->route(
                    id: 'candidate-otp',
                    uri: '/verify-email',
                    name: 'candidate.otp.view',
                    middleware: ['auth', 'role:candidate'],
                    view: 'auth/verify-otp.blade.php',
                    controller: 'Web\CandidateWebController',
                    action: 'showOtpForm',
                    description: 'OTP verification page after account registration.',
                    models: ['User', 'EmailVerificationOtp'],
                    postFields: [
                        ['otp', 'string', 'required|digits:6', 'Email OTP code.'],
                    ],
                    postRoute: 'POST /verify-email -> CandidateWebController@verifyOtp',
                    alsoPosts: [
                        ['Resend OTP', 'POST /verify-email/resend -> CandidateWebController@resendOtp', []],
                    ]
                ),
                $this->route(
                    id: 'candidate-profile',
                    uri: '/register-candidate',
                    name: 'candidate.register.view',
                    middleware: ['auth', 'role:candidate'],
                    view: 'candidate/register.blade.php',
                    controller: 'Web\CandidateWebController',
                    action: 'showCandidateRegisterForm',
                    description: 'Verified candidate profile registration form with department choices, files, essays, and repeatable history sections.',
                    variables: [
                        ['$departments', 'Collection<Departmentsbiro>', 'Active departments/biros for first and second choice.'],
                        ['$candidateType', 'string|null', 'Candidate type from query/session.'],
                    ],
                    models: ['Candidate', 'Departmentsbiro', 'CandidateEducation', 'CandidateOrganization', 'CandidateCommittee', 'CandidateSkill', 'CandidateFacility'],
                    postFields: [
                        ['candidate_type', 'string', 'required|in:staff,bph', 'Registration type.'],
                        ['nim', 'string', 'required|digits:10|unique:candidates,nim', 'Student ID.'],
                        ['prodi', 'string', 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital', 'Study program.'],
                        ['first_choice_id', 'int', 'required|active departmentsbiro', 'First department choice.'],
                        ['second_choice_id', 'int|null', 'nullable|different:first_choice_id|active departmentsbiro', 'Second department choice.'],
                        ['photo/files/signatures', 'files', 'required with mime/size rules', 'Required registration uploads.'],
                        ['educations/organizations/committees/skills/facilities', 'arrays', 'nullable arrays with nested rules', 'Repeatable profile sections.'],
                    ],
                    postRoute: 'POST /register-candidate -> CandidateWebController@registerCandidate',
                    services: ['CandidateProfileService', 'OpenRecruitmentService']
                ),
                $this->route(
                    id: 'candidate-dashboard',
                    uri: '/candidate/dashboard',
                    name: 'candidate.dashboard',
                    middleware: ['auth', 'role:candidate'],
                    view: 'candidate/dashboardcandidate.blade.php',
                    controller: 'Web\CandidateWebController',
                    action: 'showDashboard',
                    description: 'Candidate dashboard/status page after profile registration.',
                    variables: [
                        ['$candidate', 'Candidate', 'Authenticated candidate profile.'],
                        ['$announcement', 'Announcement|null', 'Final announcement if available.'],
                    ],
                    models: ['Candidate', 'Announcement']
                ),
                $this->route(
                    id: 'candidate-schedule',
                    uri: '/schedule',
                    name: 'candidate.schedule.view',
                    middleware: ['auth', 'role:candidate'],
                    view: 'candidate/schedule.blade.php',
                    controller: 'Web\CandidateWebController',
                    action: 'showScheduleForm',
                    description: 'Candidate first-choice department interview schedule selection and status page.',
                    variables: [
                        ['$candidate', 'Candidate', 'Authenticated candidate profile.'],
                        ['$availableSlots', 'Collection<InterviewSchedule>', 'Active unbooked first-choice department slots plus current booking.'],
                        ['$announcement', 'Announcement|null', 'Final decision if available.'],
                        ['$dssResults', 'array|null', 'DSS detail if published/evaluated.'],
                        ['$currentBookedSlotId', 'int|null', 'Current booked schedule ID.'],
                        ['$openRecruitment', 'OpenRecruitment|null', 'Current candidate-type recruitment period.'],
                    ],
                    models: ['Candidate', 'InterviewSchedule', 'CandidateInterviewSchedule', 'Announcement'],
                    postFields: [
                        ['schedule_id', 'int', 'required|exists:interview_schedules,id', 'Chosen slot.'],
                    ],
                    postRoute: 'POST /schedule/book -> CandidateWebController@bookSchedule'
                ),
            ],
            'Interviewer (auth)' => [
                $this->route(
                    id: 'interviewer-dashboard',
                    uri: '/interviewer/dashboard',
                    name: 'interviewer.dashboard',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/dashboard.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'dashboard',
                    description: 'Interviewer overview for assigned department activity.',
                    variables: [
                        ['$todaySchedules', 'Collection<InterviewSchedule>', 'Today schedule rows for interviewer department.'],
                        ['$topCandidates', 'Collection<Candidate>', 'Department candidates preview.'],
                        ['$department', 'Departmentsbiro|null', 'Interviewer assigned department.'],
                    ],
                    models: ['InterviewSchedule', 'Candidate', 'Departmentsbiro']
                ),
                $this->route(
                    id: 'interviewer-registrations',
                    uri: '/interviewer/pendaftaran',
                    name: 'interviewer.registrations',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/registrations.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'registrations',
                    description: 'Candidates connected to the interviewer assigned department.',
                    variables: [
                        ['$candidates', 'Collection<Candidate>', 'Candidates whose choices include the interviewer department.'],
                    ],
                    models: ['Candidate', 'Departmentsbiro']
                ),
                $this->route(
                    id: 'interviewer-schedules',
                    uri: '/interviewer/schedules',
                    name: 'interviewer.schedules',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/schedules.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'schedules',
                    description: 'Interviewer schedule matrix for assigned department.',
                    variables: [
                        ['$departments', 'Collection<Departmentsbiro>', 'Departments visible to interviewer flow.'],
                        ['$schedules', 'Collection<InterviewSchedule>', 'Schedule rows for active department/date grid.'],
                        ['$dates', 'Collection', 'Generated date columns.'],
                        ['$timeSlots', 'Collection', 'Generated time rows.'],
                    ],
                    models: ['InterviewSchedule', 'Departmentsbiro']
                ),
                $this->route(
                    id: 'interviewer-profile-matching',
                    uri: '/interviewer/profile-matching',
                    name: 'interviewer.profile-matching',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/profile-matching.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'profileMatching',
                    description: 'Interviewer view of profile matching candidates for assigned department.',
                    variables: [
                        ['$candidates', 'Collection<Candidate>', 'Department candidates.'],
                        ['$department', 'Departmentsbiro|null', 'Assigned department.'],
                    ],
                    models: ['Candidate', 'Departmentsbiro']
                ),
                $this->route(
                    id: 'interviewer-grade',
                    uri: '/interviewer/grade/{candidate}/{department}',
                    name: 'interviewer.grade.view',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/grade.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'showGradingForm',
                    description: 'Shared candidate scoring form. One score row is shared across multiple interviewers.',
                    variables: [
                        ['$candidate', 'Candidate', 'Candidate being graded.'],
                        ['$department', 'Departmentsbiro', 'Department grading context.'],
                        ['$criteria', 'Collection<EvaluationCriteria>', 'Active department criteria.'],
                        ['$existingScores', 'Collection', 'Scores keyed by criterion.'],
                    ],
                    models: ['Candidate', 'Departmentsbiro', 'EvaluationCriteria', 'Evaluation'],
                    postFields: [
                        ['scores.*', 'int', 'required|integer|min:1|max:5', 'Score per criterion.'],
                    ],
                    postRoute: 'POST /interviewer/grade/{candidate}/{department} -> InterviewerWebController@submitScores'
                ),
                $this->route(
                    id: 'interviewer-criteria',
                    uri: '/interviewer/criteria',
                    name: 'interviewer.criteria',
                    middleware: ['auth', 'role:interviewer'],
                    view: 'interviewer/criteria.blade.php',
                    controller: 'Web\InterviewerWebController',
                    action: 'criteria',
                    description: 'Interviewer criteria CRUD for assigned department.',
                    variables: [
                        ['$department', 'Departmentsbiro', 'Assigned department.'],
                        ['$criteria', 'Collection<EvaluationCriteria>', 'Current department criteria.'],
                        ['$isDirty', 'bool', 'Whether criteria differ from defaults.'],
                    ],
                    models: ['Departmentsbiro', 'EvaluationCriteria', 'DefaultEvaluationCriteria']
                ),
            ],
            'Admin (auth)' => [
                $this->route('admin-dashboard', '/admin/dashboard', 'admin.dashboard', ['auth', 'role:admin'], 'admin/dashboard.blade.php', 'Web\AdminWebController', 'dashboard', 'Compact admin overview using real database counts.', [
                    ['$stats', 'array', 'Total candidate/account/department/default criteria counts.'],
                    ['$candidateSummary', 'array', 'Registration status and type counts.'],
                    ['$recentCandidates', 'Collection<Candidate>', 'Latest five candidates.'],
                    ['$readiness', 'array', 'Profile Matching readiness counters.'],
                ], ['Candidate', 'User', 'Departmentsbiro', 'Evaluation', 'SpkResult']),
                $this->route('admin-registrations', '/admin/pendaftaran', 'admin.registrations', ['auth', 'role:admin'], 'admin/registrations.blade.php', 'Web\AdminWebController', 'registrations', 'Candidate registration management table with filters.', [
                    ['$candidates', 'LengthAwarePaginator<Candidate>', 'Filtered candidate rows.'],
                    ['$departments', 'Collection<Departmentsbiro>', 'Filter options.'],
                    ['$registrationSummary', 'array', 'Candidate/document/schedule summary counts.'],
                    ['$statuses', 'Collection<string>', 'Status filter options.'],
                ], ['Candidate', 'Departmentsbiro']),
                $this->route('admin-open-recruitment', '/admin/open-recruitment', 'admin.open-recruitment', ['auth', 'role:admin'], 'admin/open-recruitment.blade.php', 'Web\AdminWebController', 'openRecruitment', 'Create/open/close/extend recruitment periods and manage independent Staff/BPH department quotas.', [
                    ['$openRecruitments', 'Collection<OpenRecruitment>', 'Current staff/bph period rows keyed by candidate type.'],
                    ['$departments', 'Collection<Departmentsbiro>', 'Active departments for quota matrix.'],
                    ['$quotasByType', 'Collection<OpenRecruitmentQuota>', 'Quota rows keyed by candidate type and department.'],
                    ['$quotaLogs', 'Collection<OpenRecruitmentQuotaLog>', 'Recent quota audit logs.'],
                ], ['OpenRecruitment', 'OpenRecruitmentQuota', 'OpenRecruitmentExtension', 'OpenRecruitmentQuotaLog']),
                $this->route('admin-schedules', '/admin/schedules', 'admin.schedules', ['auth', 'role:admin'], 'admin/schedules.blade.php', 'Web\AdminWebController', 'listSchedules', 'Department schedule matrix and slot generation controls.', [
                    ['$departments', 'Collection<Departmentsbiro>', 'Department selector.'],
                    ['$activeDepartmentId', 'int|null', 'Selected department.'],
                    ['$schedules', 'Collection<InterviewSchedule>', 'Schedule rows.'],
                    ['$dates', 'Collection', 'Date columns.'],
                    ['$timeSlots', 'Collection', 'Time rows.'],
                ], ['InterviewSchedule', 'Departmentsbiro']),
                $this->route('admin-announcements', '/admin/pengumuman', 'admin.announcements', ['auth', 'role:admin'], 'admin/announcements.blade.php', 'Web\AdminWebController', 'announcements', 'Final announcement management and publish toggle.', [
                    ['$announcements', 'Collection<Announcement>', 'Filtered final decisions.'],
                    ['$isPublished', 'bool', 'Global announcement visibility.'],
                    ['$departments', 'Collection<Departmentsbiro>', 'Department filter/options.'],
                ], ['Announcement', 'Candidate', 'Departmentsbiro']),
                $this->route('admin-profile-matching', '/admin/profile-matching', 'admin.profile-matching', ['auth', 'role:admin'], 'admin/profile-matching.blade.php', 'Web\AdminWebController', 'profileMatching', 'Admin Profile Matching scoring and ranking workspace.', [
                    ['$departments', 'Collection<Departmentsbiro>', 'Department selector.'],
                    ['$criteria', 'Collection<EvaluationCriteria>', 'Active selected department criteria.'],
                    ['$rankings', 'array', 'Profile Matching rankings.'],
                    ['$candidates', 'LengthAwarePaginator<Candidate>|Collection', 'Candidates to score.'],
                ], ['Candidate', 'Departmentsbiro', 'EvaluationCriteria', 'Evaluation', 'SpkResult'], ['ProfileMatchingService']),
                $this->route('admin-default-criteria', '/admin/default-criteria', 'admin.default-criteria', ['auth', 'role:admin'], 'admin/default-criteria.blade.php', 'Web\AdminWebController', 'defaultCriteria', 'CRUD for default criteria used when departments reset criteria.', [
                    ['$criteria', 'Collection<DefaultEvaluationCriteria>', 'Default criteria rows.'],
                ], ['DefaultEvaluationCriteria']),
                $this->route('admin-departments', '/admin/departemen-biro', 'admin.departments', ['auth', 'role:admin'], 'admin/departments.blade.php', 'Web\AdminWebController', 'departments', 'Department/Biro master data list.', [
                    ['$departments', 'Collection<Departmentsbiro>', 'All department/biro rows.'],
                ], ['Departmentsbiro']),
                $this->route('admin-department-detail', '/admin/departments/{department}', 'admin.departments.manage', ['auth', 'role:admin'], 'admin/department-detail.blade.php', 'Web\AdminWebController', 'manageDepartment', 'Department detail page for agenda and work program management.', [
                    ['$department', 'Departmentsbiro', 'Department with agendas and work programs.'],
                ], ['Departmentsbiro', 'DepartmentAgenda', 'DepartmentWorkProgram']),
                $this->route('admin-accounts', '/admin/accounts', 'admin.accounts', ['auth', 'role:admin'], 'admin/accounts.blade.php', 'Web\AdminAccountController', 'index', 'Account management for admin, interviewer, and candidate users.', [
                    ['$users', 'LengthAwarePaginator<User>', 'Filtered users.'],
                    ['$departments', 'Collection<Departmentsbiro>', 'Department assignment options for interviewers.'],
                    ['$currentRole', 'string|null', 'Active role filter.'],
                ], ['User', 'Departmentsbiro']),
            ],
        ];

        return view('docs.blade', compact('groups'));
    }

    private function route(
        string $id,
        string $uri = '',
        string $name = '',
        array $middleware = [],
        string $view = '',
        string $controller = '',
        string $action = '',
        string $description = '',
        array $variables = [],
        array $models = [],
        array $services = [],
        array $postFields = [],
        ?string $postRoute = null,
        array $alsoPosts = []
    ): array {
        return [
            'id' => $id,
            'method' => 'GET',
            'uri' => $uri,
            'name' => $name,
            'middleware' => $middleware,
            'view' => $view,
            'controller' => $controller,
            'action' => $action,
            'description' => $description,
            'variables' => array_map(fn($row) => [
                'name' => $row[0],
                'type' => $row[1],
                'description' => $row[2],
            ], $variables),
            'models' => $models,
            'services' => $services,
            'post_fields' => array_map(fn($row) => [
                'name' => $row[0],
                'type' => $row[1],
                'rules' => $row[2],
                'description' => $row[3],
            ], $postFields),
            'post_route' => $postRoute,
            'also_posts' => array_map(fn($row) => [
                'label' => $row[0],
                'post_route' => $row[1],
                'post_fields' => array_map(fn($field) => [
                    'name' => $field[0],
                    'type' => $field[1],
                    'rules' => $field[2],
                    'description' => $field[3],
                ], $row[2]),
            ], $alsoPosts),
        ];
    }
}
