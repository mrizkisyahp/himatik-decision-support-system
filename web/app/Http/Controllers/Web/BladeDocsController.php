<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class BladeDocsController extends Controller
{
    public function index()
    {
        $groups = [

            // ─────────────────────────────────────────────────────────────
            'Public' => [
                [
                    'id'          => 'landing',
                    'method'      => 'GET',
                    'uri'         => '/',
                    'name'        => 'landing',
                    'middleware'  => ['public'],
                    'view'        => 'landing.blade.php',
                    'controller'  => 'Web\LandingWebController',
                    'action'      => 'index',
                    'description' => 'Public landing page for the HIMATIK Open Recruitment DSS. Displays recruitment status, open departments/biro, and candidate statistics.',
                    'variables'   => [
                        ['name' => '$departments', 'type' => 'Collection<Departmentsbiro>', 'description' => 'All departments — only name and description columns selected (no sensitive data)'],
                    ],
                    'models'      => ['Departmentsbiro'],
                    'post_fields' => [],
                ],
                [
                    'id'          => 'announcements',
                    'method'      => 'GET',
                    'uri'         => '/announcements',
                    'name'        => 'public.announcements',
                    'middleware'  => ['public'],
                    'view'        => 'public/announcements.blade.php',
                    'controller'  => 'Web\PublicAnnouncementController',
                    'action'      => 'showAcceptedList',
                    'description' => 'Public announcement board listing all accepted candidates once results are published by admin.',
                    'variables'   => [
                        ['name' => '$announcements', 'type' => 'Collection<Announcement>', 'description' => 'Accepted & published announcements eager-loaded with candidate.user and assignedDepartment'],
                    ],
                    'models'      => ['Announcement', 'Candidate', 'Departmentsbiro'],
                    'post_fields' => [],
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            'Guest Only' => [
                [
                    'id'          => 'login',
                    'method'      => 'GET',
                    'uri'         => '/login',
                    'name'        => 'login',
                    'middleware'  => ['guest'],
                    'view'        => 'auth/login.blade.php',
                    'controller'  => 'Web\AuthWebController',
                    'action'      => 'showLoginForm',
                    'description' => 'Login page for all user roles (candidate, interviewer, admin). Redirects to respective dashboard on success.',
                    'variables'   => [],
                    'models'      => ['User'],
                    'post_fields' => [
                        ['name' => 'email',    'type' => 'string',   'rules' => 'required|email',    'description' => 'Registered email address'],
                        ['name' => 'password', 'type' => 'string',   'rules' => 'required|string',   'description' => 'Account password'],
                    ],
                    'post_route'  => 'POST /login → AuthWebController@login',
                ],
                [
                    'id'          => 'register-stage1',
                    'method'      => 'GET',
                    'uri'         => '/register',
                    'name'        => 'user.register.view',
                    'middleware'  => ['guest'],
                    'view'        => 'auth/register.blade.php',
                    'controller'  => 'Web\CandidateWebController',
                    'action'      => 'showUserRegisterForm',
                    'description' => 'Stage 1 of 2: Create a user account. On success, user is auto-logged in and redirected to Stage 2.',
                    'variables'   => [],
                    'models'      => ['User'],
                    'post_fields' => [
                        ['name' => 'nama',                 'type' => 'string', 'rules' => 'required|max:255',                 'description' => 'Full name'],
                        ['name' => 'email',                'type' => 'string', 'rules' => 'required|email|unique:users',      'description' => 'Email address'],
                        ['name' => 'password',             'type' => 'string', 'rules' => 'required|min:8|confirmed',         'description' => 'Password'],
                        ['name' => 'password_confirmation','type' => 'string', 'rules' => 'required',                         'description' => 'Password confirmation'],
                    ],
                    'post_route'  => 'POST /register → CandidateWebController@registerUser',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            'Candidate (auth)' => [
                [
                    'id'          => 'register-stage2',
                    'method'      => 'GET',
                    'uri'         => '/register-candidate',
                    'name'        => 'candidate.register.view',
                    'middleware'  => ['auth', 'role:candidate'],
                    'view'        => 'candidate/register.blade.php',
                    'controller'  => 'Web\CandidateWebController',
                    'action'      => 'showCandidateRegisterForm',
                    'description' => 'Stage 2 of 2: Complete candidate profile. User selects registration type (Staff or BPH), fills academic details, selects biro preferences, and uploads required documents. Staff requires 4 documents; BPH requires 2.',
                    'variables'   => [
                        ['name' => '$departments', 'type' => 'Collection<Departmentsbiro>', 'description' => 'All biro/departments for first and second choice selection'],
                    ],
                    'models'      => ['Departmentsbiro', 'Candidate'],
                    'post_fields' => [
                        ['name' => 'candidate_type',    'type' => 'string', 'rules' => 'required|in:staff,bph',              'description' => 'Registration type: staff or bph'],
                        ['name' => 'nim',               'type' => 'string', 'rules' => 'required|unique:candidates',         'description' => 'Student ID number (NIM)'],
                        ['name' => 'prodi',             'type' => 'string', 'rules' => 'required|in:3 valid values',         'description' => 'Study program'],
                        ['name' => 'kelas',             'type' => 'string', 'rules' => 'required|max:50',                    'description' => 'Class name e.g. TI-4A'],
                        ['name' => 'phone',             'type' => 'string', 'rules' => 'required|max:20',                    'description' => 'WhatsApp phone number'],
                        ['name' => 'first_choice_id',   'type' => 'int',    'rules' => 'required|exists:departmentsbiro',    'description' => 'First choice department ID'],
                        ['name' => 'second_choice_id',  'type' => 'int',    'rules' => 'required|exists:departmentsbiro',    'description' => 'Second choice department ID'],
                        ['name' => 'recruitment_form',  'type' => 'file',   'rules' => 'required|pdf|max:2MB',               'description' => 'Staff: Formulir STAFF. BPH: Formulir BPH'],
                        ['name' => 'photo',             'type' => 'image',  'rules' => 'required|jpg/png|max:1MB',           'description' => 'Staff: Kemeja Putih 3x4 BG Biru. BPH: Jaket TIK 3x4 BG Biru'],
                        ['name' => 'statement_letter',  'type' => 'file',   'rules' => 'required if staff|pdf/jpg|max:2MB',  'description' => '⚠️ Staff only — Surat Pernyataan bukan extra kampus/parpol'],
                        ['name' => 'social_media_proof','type' => 'image',  'rules' => 'required if staff|jpg/png|max:2MB',  'description' => '⚠️ Staff only — SS Follow IG @himatikpnj & Subscribe YT @HIMATIKPNJ'],
                    ],
                    'post_route'  => 'POST /register-candidate → CandidateWebController@registerCandidate',
                ],
                [
                    'id'          => 'schedule',
                    'method'      => 'GET',
                    'uri'         => '/schedule',
                    'name'        => 'candidate.schedule.view',
                    'middleware'  => ['auth', 'role:candidate'],
                    'view'        => 'candidate/schedule.blade.php',
                    'controller'  => 'Web\CandidateWebController',
                    'action'      => 'showScheduleForm',
                    'description' => 'Candidate selects an available interview time slot. Also shows DSS results and final decision if the announcement is published after evaluation.',
                    'variables'   => [
                        ['name' => '$candidate',       'type' => 'Candidate',                   'description' => 'The authenticated candidate with relations (firstChoice, secondChoice)'],
                        ['name' => '$availableSlots',  'type' => 'Collection<InterviewSchedule>','description' => 'Unbooked slots + the candidate\'s currently booked slot'],
                        ['name' => '$announcement',    'type' => 'Announcement|null',            'description' => 'The candidate\'s final announcement (if it exists and is published)'],
                        ['name' => '$dssResults',      'type' => 'array|null',                   'description' => 'Profile Matching DSS score breakdown (only shown if evaluated and published)'],
                    ],
                    'models'      => ['Candidate', 'InterviewSchedule', 'Announcement', 'Departmentsbiro'],
                    'services'    => ['ProfileMatchingService'],
                    'post_fields' => [
                        ['name' => 'schedule_id', 'type' => 'int', 'rules' => 'required|exists:interview_schedules', 'description' => 'ID of the chosen interview schedule slot'],
                    ],
                    'post_route'  => 'POST /schedule/book → CandidateWebController@bookSchedule',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            'Interviewer (auth)' => [
                [
                    'id'          => 'interviewer-dashboard',
                    'method'      => 'GET',
                    'uri'         => '/interviewer/schedule',
                    'name'        => 'interviewer.schedule',
                    'middleware'  => ['auth', 'role:interviewer'],
                    'view'        => 'interviewer/dashboard.blade.php',
                    'controller'  => 'Web\InterviewerWebController',
                    'action'      => 'index',
                    'description' => 'Interviewer dashboard showing all interview schedule slots assigned to the logged-in interviewer, with candidate details.',
                    'variables'   => [
                        ['name' => '$schedules', 'type' => 'Collection<InterviewSchedule>', 'description' => 'Schedules assigned to this interviewer, with candidate user and department relations'],
                    ],
                    'models'      => ['InterviewSchedule', 'Candidate', 'User', 'Departmentsbiro'],
                    'post_fields' => [],
                ],
                [
                    'id'          => 'interviewer-grade',
                    'method'      => 'GET',
                    'uri'         => '/interviewer/grade/{candidate}/{department}',
                    'name'        => 'interviewer.grade.view',
                    'middleware'  => ['auth', 'role:interviewer'],
                    'view'        => 'interviewer/grade.blade.php',
                    'controller'  => 'Web\InterviewerWebController',
                    'action'      => 'showGradingForm',
                    'description' => 'Grading page for a specific candidate in a specific department. Shows two sections: (1) per-criteria 1–5 scoring form, (2) final Accept/Reject decision form with department assignment.',
                    'variables'   => [
                        ['name' => '$candidate',      'type' => 'Candidate',                   'description' => 'The candidate being graded'],
                        ['name' => '$department',     'type' => 'Departmentsbiro',              'description' => 'The department context for grading'],
                        ['name' => '$criteria',       'type' => 'Collection<EvaluationCriteria>','description' => 'All evaluation criteria for this department'],
                        ['name' => '$existingScores', 'type' => 'Collection (keyed by criteria_id)', 'description' => 'Previously saved scores for this candidate/department pair'],
                        ['name' => '$announcement',   'type' => 'Announcement|null',            'description' => 'Current accept/reject decision for this candidate'],
                        ['name' => '$departments',    'type' => 'Collection<Departmentsbiro>',  'description' => 'All departments for the assignment dropdown'],
                    ],
                    'models'      => ['Candidate', 'Departmentsbiro', 'EvaluationCriteria', 'Evaluation', 'Announcement'],
                    'post_fields' => [
                        ['name' => 'scores[{criteria_id}]', 'type' => 'int', 'rules' => 'required|integer|min:1|max:5', 'description' => 'Score per criterion (key = criteria ID, value = 1–5)'],
                    ],
                    'post_route'  => 'POST /interviewer/grade/{candidate}/{department} → InterviewerWebController@submitScores',
                    'also_posts'  => [
                        [
                            'label'       => 'Decision Form',
                            'post_route'  => 'POST /interviewer/decide/{candidate} → InterviewerWebController@decideCandidate',
                            'post_fields' => [
                                ['name' => 'status',                 'type' => 'string', 'rules' => 'required|in:accepted,rejected', 'description' => 'Final decision'],
                                ['name' => 'assigned_department_id', 'type' => 'int',    'rules' => 'required_if:status,accepted',   'description' => 'Department to assign if accepted'],
                            ],
                        ],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            'Admin (auth)' => [
                [
                    'id'          => 'admin-dashboard',
                    'method'      => 'GET',
                    'uri'         => '/admin/dashboard',
                    'name'        => 'admin.dashboard',
                    'middleware'  => ['auth', 'role:admin'],
                    'view'        => 'admin/dashboard.blade.php',
                    'controller'  => 'Web\AdminWebController',
                    'action'      => 'dashboard',
                    'description' => 'Admin overview showing recruitment statistics and full Departments/Biro CRUD (create, inline edit, delete). Also contains the announcement publish toggle.',
                    'variables'   => [
                        ['name' => '$stats',       'type' => 'array',                       'description' => 'Counts: total_candidates, total_registered, total_scheduled, total_evaluated'],
                        ['name' => '$departments', 'type' => 'Collection<Departmentsbiro>', 'description' => 'All departments with first_choice_candidates_count and second_choice_candidates_count'],
                    ],
                    'models'      => ['Candidate', 'Departmentsbiro'],
                    'post_fields' => [
                        ['name' => 'name',                    'type' => 'string', 'rules' => 'required|unique:departmentsbiro',  'description' => 'Department name'],
                        ['name' => 'description',             'type' => 'string', 'rules' => 'nullable',                         'description' => 'Description (optional)'],
                        ['name' => 'core_factor_weight',      'type' => 'float',  'rules' => 'required|0–1',                     'description' => 'Profile Matching core factor weight'],
                        ['name' => 'secondary_factor_weight', 'type' => 'float',  'rules' => 'required|0–1',                     'description' => 'Profile Matching secondary factor weight'],
                    ],
                    'post_route'  => 'POST /admin/departments → AdminWebController@storeDepartment',
                ],
                [
                    'id'          => 'admin-rankings',
                    'method'      => 'GET',
                    'uri'         => '/admin/rankings/{department}',
                    'name'        => 'admin.rankings',
                    'middleware'  => ['auth', 'role:admin'],
                    'view'        => 'admin/rankings.blade.php',
                    'controller'  => 'Web\AdminWebController',
                    'action'      => 'showRankings',
                    'description' => 'DSS Profile Matching rankings for a specific department. Shows each candidate\'s calculated score and allows admin to accept/reject with department assignment.',
                    'variables'   => [
                        ['name' => '$department',    'type' => 'Departmentsbiro',           'description' => 'The department being ranked'],
                        ['name' => '$rankings',      'type' => 'array',                     'description' => 'Sorted candidates with DSS scores from ProfileMatchingService'],
                        ['name' => '$announcements', 'type' => 'Collection<Announcement>',  'description' => 'Existing decisions keyed by candidate_id'],
                    ],
                    'models'      => ['Departmentsbiro', 'Candidate', 'Announcement'],
                    'services'    => ['ProfileMatchingService'],
                    'post_fields' => [
                        ['name' => 'status',                 'type' => 'string', 'rules' => 'required|in:accepted,rejected', 'description' => 'Decision for the candidate'],
                        ['name' => 'assigned_department_id', 'type' => 'int',    'rules' => 'required_if:status,accepted',   'description' => 'Department assignment if accepted'],
                    ],
                    'post_route'  => 'POST /admin/decide/{candidate} → AdminWebController@decideCandidate',
                ],
                [
                    'id'          => 'admin-criteria',
                    'method'      => 'GET',
                    'uri'         => '/admin/criteria/{department}',
                    'name'        => 'admin.criteria',
                    'middleware'  => ['auth', 'role:admin'],
                    'view'        => 'admin/criteria.blade.php',
                    'controller'  => 'Web\AdminWebController',
                    'action'      => 'listCriteria',
                    'description' => 'Manage evaluation criteria for a specific department. Each criterion defines a grading dimension (Core or Secondary factor) used in Profile Matching DSS calculations.',
                    'variables'   => [
                        ['name' => '$department', 'type' => 'Departmentsbiro',                'description' => 'The department whose criteria are being managed'],
                        ['name' => '$criteria',   'type' => 'Collection<EvaluationCriteria>', 'description' => 'All criteria for this department'],
                    ],
                    'models'      => ['Departmentsbiro', 'EvaluationCriteria'],
                    'post_fields' => [
                        ['name' => 'name',         'type' => 'string', 'rules' => 'required|max:255',      'description' => 'Criterion name e.g. Komunikasi'],
                        ['name' => 'type',         'type' => 'string', 'rules' => 'required|in:core,secondary', 'description' => 'Factor type for Profile Matching'],
                        ['name' => 'target_score', 'type' => 'int',    'rules' => 'required|1–5',          'description' => 'Ideal score for this criterion (1–5)'],
                        ['name' => 'description',  'type' => 'string', 'rules' => 'nullable',              'description' => 'Grading expectations description'],
                    ],
                    'post_route'  => 'POST /admin/criteria/{department} → AdminWebController@storeCriterion',
                ],
                [
                    'id'          => 'admin-schedules',
                    'method'      => 'GET',
                    'uri'         => '/admin/schedules',
                    'name'        => 'admin.schedules',
                    'middleware'  => ['auth', 'role:admin'],
                    'view'        => 'admin/schedules.blade.php',
                    'controller'  => 'Web\AdminWebController',
                    'action'      => 'listSchedules',
                    'description' => 'Full CRUD for interview schedule slots. Admin creates time slots, assigns interviewers (multi-select), and sees which candidates have booked each slot.',
                    'variables'   => [
                        ['name' => '$schedules',    'type' => 'Collection<InterviewSchedule>', 'description' => 'All slots with booked candidate and assigned interviewers'],
                        ['name' => '$interviewers', 'type' => 'Collection<User>',              'description' => 'All users with role=interviewer for assignment dropdown'],
                    ],
                    'models'      => ['InterviewSchedule', 'Candidate', 'User'],
                    'post_fields' => [
                        ['name' => 'session_name',      'type' => 'string',  'rules' => 'required|max:255',       'description' => 'Session name e.g. Sesi Pagi A'],
                        ['name' => 'scheduled_at',      'type' => 'datetime','rules' => 'required|date',           'description' => 'Date and time of the session'],
                        ['name' => 'location',          'type' => 'string',  'rules' => 'required|max:255',        'description' => 'Location e.g. Ruang Rapat HIMATIK'],
                        ['name' => 'interviewer_ids[]', 'type' => 'int[]',   'rules' => 'nullable|exists:users',   'description' => 'Array of interviewer user IDs to assign'],
                    ],
                    'post_route'  => 'POST /admin/schedules → AdminWebController@storeSchedule',
                ],
                [
                    'id'          => 'admin-interviewers',
                    'method'      => 'GET',
                    'uri'         => '/admin/interviewers',
                    'name'        => 'admin.interviewers',
                    'middleware'  => ['auth', 'role:admin'],
                    'view'        => 'admin/interviewers.blade.php',
                    'controller'  => 'Web\AdminWebController',
                    'action'      => 'listInterviewers',
                    'description' => 'Full CRUD for interviewer accounts. Admin creates, edits, and deletes users with role=interviewer. Password can be optionally reset on update.',
                    'variables'   => [
                        ['name' => '$interviewers', 'type' => 'Collection<User>', 'description' => 'All users with role=interviewer'],
                    ],
                    'models'      => ['User'],
                    'post_fields' => [
                        ['name' => 'name',     'type' => 'string', 'rules' => 'required|max:255',        'description' => 'Full name of the interviewer'],
                        ['name' => 'email',    'type' => 'string', 'rules' => 'required|email|unique',   'description' => 'Email address'],
                        ['name' => 'password', 'type' => 'string', 'rules' => 'required|min:8',          'description' => 'Account password (min 8 chars)'],
                    ],
                    'post_route'  => 'POST /admin/interviewers → AdminWebController@storeInterviewer',
                ],
            ],
        ];

        return view('docs.blade', compact('groups'));
    }
}
