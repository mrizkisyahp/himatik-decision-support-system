<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\CandidateDepartmentChoice;
use App\Models\CandidateInterviewSchedule;
use App\Models\Departmentsbiro;
use App\Models\DefaultEvaluationCriteria;
use App\Models\Evaluation;
use App\Models\EvaluationCriteria;
use App\Models\InterviewSchedule;
use App\Models\OpenRecruitment;
use App\Models\OpenRecruitmentQuota;
use App\Models\OpenRecruitmentQuotaLog;
use App\Models\SpkResult;
use App\Models\User;
use App\Services\OpenRecruitmentService;
use App\Services\ProfileMatchingService;
use App\Support\SpkCriteriaDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminWebController extends Controller
{
    protected $dss;

    public function __construct(ProfileMatchingService $dss)
    {
        $this->dss = $dss;
    }

    // ─────────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_candidates' => Candidate::count(),
            'total_users' => User::count(),
            'total_departments' => Departmentsbiro::count(),
            'total_default_criteria' => DefaultEvaluationCriteria::count(),
        ];

        $candidateSummary = [
            'total' => $stats['total_candidates'],
            'staff' => Candidate::where('candidate_type', 'staff')->count(),
            'bph' => Candidate::where('candidate_type', 'bph')->count(),
            'registered' => Candidate::where('status', 'registered')->count(),
            'scheduled' => Candidate::where('status', 'scheduled')->count(),
            'evaluated' => Candidate::where('status', 'evaluated')->count(),
            'completed' => Candidate::where('status', 'completed')->count(),
        ];

        $recentCandidates = Candidate::with(['user', 'departmentChoices.department'])
            ->latest()
            ->limit(5)
            ->get();

        $firstChoiceInterest = CandidateDepartmentChoice::with('department')
            ->selectRaw('departmentsbiro_id, COUNT(*) as total')
            ->where('choice_order', 1)
            ->groupBy('departmentsbiro_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $secondChoiceInterest = CandidateDepartmentChoice::with('department')
            ->selectRaw('departmentsbiro_id, COUNT(*) as total')
            ->where('choice_order', 2)
            ->groupBy('departmentsbiro_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $departmentInterest = Departmentsbiro::withCount([
            'firstChoiceCandidates',
            'secondChoiceCandidates',
        ])
            ->havingRaw('first_choice_candidates_count + second_choice_candidates_count > 0')
            ->orderByDesc('first_choice_candidates_count')
            ->orderByDesc('second_choice_candidates_count')
            ->limit(8)
            ->get();

        $readiness = [
            'default_criteria' => $stats['total_default_criteria'],
            'departments' => $stats['total_departments'],
            'candidates' => $stats['total_candidates'],
            'scores' => Evaluation::count(),
            'results' => SpkResult::count(),
        ];

        $interviewProgress = [
            'total_sessions' => InterviewSchedule::count(),
            'scheduled_candidates' => CandidateInterviewSchedule::count(),
            'completed_interviews' => Candidate::whereIn('status', ['evaluated', 'completed'])->count(),
            'pending_interviews' => Candidate::where('status', 'scheduled')->count(),
        ];

        $announcementStatus = [
            'total' => Announcement::count(),
            'published' => Announcement::where('is_published', true)->count(),
            'unpublished' => Announcement::where('is_published', false)->count(),
            'latest_update' => Announcement::latest('updated_at')->value('updated_at'),
        ];

        $openRecruitmentRows = app(OpenRecruitmentService::class)->currentByType();
        $openRecruitmentOpenCount = $openRecruitmentRows->filter(fn($row) => $row->isCurrentlyOpen())->count();
        $openRecruitment = [
            'available' => $openRecruitmentRows->isNotEmpty(),
            'message' => $openRecruitmentOpenCount > 0
                ? "{$openRecruitmentOpenCount} periode open recruitment sedang dibuka."
                : 'Tidak ada periode open recruitment yang sedang dibuka.',
        ];

        $quickActions = collect([
            ['label' => 'Lihat Pendaftaran', 'description' => 'Pantau data kandidat', 'route' => 'admin.registrations'],
            ['label' => 'Kelola Account', 'description' => 'Admin dan interviewer', 'route' => 'admin.accounts'],
            ['label' => 'Kelola Departemen/Biro', 'description' => 'Master data organisasi', 'route' => 'admin.departments'],
            ['label' => 'Kelola Default Criteria', 'description' => 'Kriteria dasar SPK', 'route' => 'admin.default-criteria'],
            ['label' => 'Open Recruitment', 'description' => 'Periode rekrutmen', 'route' => 'admin.open-recruitment'],
            ['label' => 'Sesi Interview', 'description' => 'Jadwal wawancara', 'route' => 'admin.schedules'],
            ['label' => 'Pengumuman', 'description' => 'Publikasi hasil', 'route' => 'admin.announcements'],
            ['label' => 'Profile Matching', 'description' => 'SPK dan ranking', 'route' => 'admin.profile-matching'],
        ])->filter(fn ($action) => Route::has($action['route']))->values();

        $todaySchedules = \App\Models\InterviewSchedule::whereDate('date', today())
            ->whereHas('booking')
            ->with(['booking.candidate.user', 'department'])
            ->orderBy('start_time')
            ->get();

        $topCandidates = \App\Models\Candidate::whereHas('evaluations')
            ->with('user')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'candidateSummary',
            'recentCandidates',
            'firstChoiceInterest',
            'secondChoiceInterest',
            'departmentInterest',
            'readiness',
            'interviewProgress',
            'announcementStatus',
            'openRecruitment',
            'quickActions',
            'todaySchedules',
            'topCandidates'
        ));
    }

    public function registrations(Request $request)
    {
        $departments = Departmentsbiro::orderBy('name')->get(['id', 'name']);

        $candidateQuery = Candidate::with([
            'user',
            'departmentChoices.department',
            'selectedInterviewSchedule.schedule.department',
            'educations',
            'organizations',
            'committees',
            'skills',
            'facilities',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $candidateQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('prodi', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('candidate_type')) {
            $candidateQuery->where('candidate_type', $request->candidate_type);
        }

        if ($request->filled('status')) {
            $candidateQuery->where('status', $request->status);
        }

        if ($request->filled('first_choice_id')) {
            $candidateQuery->whereHas('departmentChoices', function ($query) use ($request) {
                $query->where('choice_order', 1)
                    ->where('departmentsbiro_id', $request->first_choice_id);
            });
        }

        if ($request->filled('second_choice_id')) {
            $candidateQuery->whereHas('departmentChoices', function ($query) use ($request) {
                $query->where('choice_order', 2)
                    ->where('departmentsbiro_id', $request->second_choice_id);
            });
        }

        if ($request->schedule_status === 'scheduled') {
            $candidateQuery->whereHas('selectedInterviewSchedule');
        } elseif ($request->schedule_status === 'unscheduled') {
            $candidateQuery->whereDoesntHave('selectedInterviewSchedule');
        }

        if ($request->document_status === 'complete') {
            $candidateQuery->whereNotNull('photo_path')
                ->whereNotNull('instagram_proof_path')
                ->whereNotNull('youtube_proof_path')
                ->whereNotNull('political_statement_path')
                ->whereNotNull('candidate_signature_path')
                ->whereNotNull('parent_signature_path');
        } elseif ($request->document_status === 'incomplete') {
            $candidateQuery->where(function ($query) {
                $query->whereNull('photo_path')
                    ->orWhereNull('instagram_proof_path')
                    ->orWhereNull('youtube_proof_path')
                    ->orWhereNull('political_statement_path')
                    ->orWhereNull('candidate_signature_path')
                    ->orWhereNull('parent_signature_path');
            });
        }

        $candidates = $candidateQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $registrationSummary = [
            'total' => Candidate::count(),
            'staff' => Candidate::where('candidate_type', 'staff')->count(),
            'bph' => Candidate::where('candidate_type', 'bph')->count(),
            'scheduled' => Candidate::whereHas('selectedInterviewSchedule')->count(),
            'unscheduled' => Candidate::whereDoesntHave('selectedInterviewSchedule')->count(),
            'documents_complete' => Candidate::whereNotNull('photo_path')
                ->whereNotNull('instagram_proof_path')
                ->whereNotNull('youtube_proof_path')
                ->whereNotNull('political_statement_path')
                ->whereNotNull('candidate_signature_path')
                ->whereNotNull('parent_signature_path')
                ->count(),
            'documents_incomplete' => Candidate::where(function ($query) {
                $query->whereNull('photo_path')
                    ->orWhereNull('instagram_proof_path')
                    ->orWhereNull('youtube_proof_path')
                    ->orWhereNull('political_statement_path')
                    ->orWhereNull('candidate_signature_path')
                    ->orWhereNull('parent_signature_path');
            })->count(),
        ];

        $statuses = Candidate::query()
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status')
            ->filter()
            ->values();

        return view('admin.registrations', compact(
            'candidates',
            'departments',
            'registrationSummary',
            'statuses'
        ));
    }

    public function openRecruitment(OpenRecruitmentService $openRecruitmentService)
    {
        $openRecruitments = OpenRecruitment::with([
            'extensions.extender',
        ])
            ->orderByRaw("FIELD(candidate_type, 'staff', 'bph')")
            ->get()
            ->keyBy('candidate_type');

        $departments = Departmentsbiro::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $quotasByType = OpenRecruitmentQuota::query()
            ->get()
            ->groupBy('candidate_type')
            ->map(fn($rows) => $rows->keyBy('department_id'));

        $quotaLogs = OpenRecruitmentQuotaLog::with(['department', 'changer'])
            ->latest()
            ->limit(12)
            ->get();

        return view('admin.open-recruitment', compact(
            'openRecruitments',
            'departments',
            'quotasByType',
            'quotaLogs',
            'openRecruitmentService'
        ));
    }

    public function updateOpenRecruitment(Request $request, OpenRecruitment $openRecruitment)
    {
        $validated = $request->validate([
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'interview_location' => 'nullable|string',
            'interview_requirements' => 'nullable|string',
        ]);

        $openRecruitment->update([
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'interview_location' => $validated['interview_location'] ?? null,
            'interview_requirements' => $validated['interview_requirements'] ?? null,
        ]);

        return back()->with('success', 'Periode open recruitment berhasil diperbarui.');
    }

    public function updateOpenRecruitmentStatus(Request $request, OpenRecruitment $openRecruitment)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $openRecruitment->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'open'
            ? 'Open recruitment berhasil dibuka.'
            : 'Open recruitment berhasil ditutup.';

        return back()->with('success', $message);
    }

    public function storeOpenRecruitment(Request $request)
    {
        $validated = $request->validate([
            'candidate_type' => 'required|in:staff,bph|unique:open_recruitments,candidate_type',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => 'required|in:open,closed',
            'interview_location' => 'nullable|string',
            'interview_requirements' => 'nullable|string',
        ]);

        $openRecruitment = OpenRecruitment::create($validated);

        $message = $openRecruitment->status === 'open'
            ? 'Periode open recruitment berhasil dibuat dan dibuka.'
            : 'Periode open recruitment berhasil dibuat dalam status tertutup.';

        return back()->with('success', $message);
    }

    public function extendOpenRecruitment(Request $request, OpenRecruitment $openRecruitment)
    {
        $newStartsAt = $request->filled('new_starts_at')
            ? $request->date('new_starts_at')
            : $openRecruitment->starts_at;

        $request->merge(['effective_starts_at' => $newStartsAt?->toDateTimeString()]);

        $validated = $request->validate([
            'new_starts_at' => 'nullable|date',
            'effective_starts_at' => 'required|date',
            'new_ends_at' => 'required|date|after:effective_starts_at',
            'reason' => 'nullable|string',
        ]);

        $openRecruitment->extensions()->create([
            'old_starts_at' => $openRecruitment->starts_at,
            'old_ends_at' => $openRecruitment->ends_at,
            'new_starts_at' => $newStartsAt,
            'new_ends_at' => $validated['new_ends_at'],
            'reason' => $validated['reason'] ?? null,
            'extended_by' => auth()->id(),
        ]);

        $openRecruitment->update([
            'starts_at' => $newStartsAt,
            'ends_at' => $validated['new_ends_at'],
        ]);

        return back()->with('success', 'Periode open recruitment berhasil diperpanjang.');
    }

    public function updateOpenRecruitmentQuotas(Request $request)
    {
        $validated = $request->validate([
            'quotas' => 'required|array',
            'quotas.*' => 'array',
            'quotas.*.*' => 'required|integer|min:0',
        ]);

        $departmentIds = Departmentsbiro::where('is_active', true)->pluck('id')->map(fn($id) => (string) $id);
        $candidateTypes = collect(['staff', 'bph']);

        foreach ($validated['quotas'] as $candidateType => $departmentQuotas) {
            if (!$candidateTypes->contains((string) $candidateType)) {
                continue;
            }

            foreach ($departmentQuotas as $departmentId => $quotaValue) {
                if (!$departmentIds->contains((string) $departmentId)) {
                    continue;
                }

                $quota = OpenRecruitmentQuota::firstOrNew([
                    'candidate_type' => $candidateType,
                    'department_id' => $departmentId,
                ]);

                $oldQuota = $quota->exists ? $quota->quota : null;
                $newQuota = (int) $quotaValue;

                if ($oldQuota === $newQuota) {
                    continue;
                }

                $quota->quota = $newQuota;
                $quota->save();

                OpenRecruitmentQuotaLog::create([
                    'candidate_type' => $candidateType,
                    'department_id' => $departmentId,
                    'old_quota' => $oldQuota,
                    'new_quota' => $newQuota,
                    'changed_by' => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'Quota open recruitment berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────────
    // DEPARTMENTS CRUD
    // ─────────────────────────────────────────────────────────────────

    public function departments()
    {
        $departments = Departmentsbiro::orderBy('name')->get();
        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name',
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        Departmentsbiro::create([
            ...$request->only('name', 'description', 'contact_person', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Department '{$request->name}' created successfully!");
    }

    public function updateDepartment(Request $request, Departmentsbiro $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departmentsbiro,name,' . $department->id,
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'personal_aspect_weight' => 'required|numeric|min:0|max:100',
            'organizational_aspect_weight' => 'required|numeric|min:0|max:100',
            'core_factor_weight' => 'required|numeric|min:0|max:100',
            'secondary_factor_weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        if (floatval($request->personal_aspect_weight) + floatval($request->organizational_aspect_weight) !== 100.0) {
            return back()->withErrors(['weight' => 'Total Bobot Personal dan Bobot Organizational harus 100%'])->withInput();
        }

        if (floatval($request->core_factor_weight) + floatval($request->secondary_factor_weight) !== 100.0) {
            return back()->withErrors(['weight' => 'Total Bobot Core Factor dan Bobot Secondary Factor harus 100%'])->withInput();
        }

        $department->update([
            ...$request->only('name', 'description', 'contact_person', 'personal_aspect_weight', 'organizational_aspect_weight', 'core_factor_weight', 'secondary_factor_weight'),
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', $department->is_active),
        ]);

        return back()->with('success', "Department '{$department->name}' updated successfully!");
    }

    public function destroyDepartment(Departmentsbiro $department)
    {
        $department->delete();
        return back()->with('success', "Department deleted successfully.");
    }

    public function manageDepartment(Departmentsbiro $department)
    {
        $department->load(['agendas', 'workPrograms']);
        return view('admin.department-detail', compact('department'));
    }

    // ─────────────────────────────────────────────────────────────────
    // AGENDAS & WORK PROGRAMS
    // ─────────────────────────────────────────────────────────────────

    public function storeAgenda(Request $request, Departmentsbiro $department)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $department->agendas()->create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Agenda added successfully!');
    }

    public function updateAgenda(Request $request, Departmentsbiro $department, \App\Models\DepartmentAgenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $agenda->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', $agenda->is_active),
            'sort_order' => $request->sort_order ?? $agenda->sort_order,
        ]);

        return back()->with('success', 'Agenda updated successfully!');
    }

    public function destroyAgenda(Departmentsbiro $department, \App\Models\DepartmentAgenda $agenda)
    {
        $agenda->delete();
        return back()->with('success', 'Agenda deleted successfully.');
    }

    public function storeWorkProgram(Request $request, Departmentsbiro $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $department->workPrograms()->create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Work Program added successfully!');
    }

    public function updateWorkProgram(Request $request, Departmentsbiro $department, \App\Models\DepartmentWorkProgram $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $program->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', $program->is_active),
            'sort_order' => $request->sort_order ?? $program->sort_order,
        ]);

        return back()->with('success', 'Work Program updated successfully!');
    }

    public function destroyWorkProgram(Departmentsbiro $department, \App\Models\DepartmentWorkProgram $program)
    {
        $program->delete();
        return back()->with('success', 'Work Program deleted successfully.');
    }


    // ─────────────────────────────────────────────────────────────────
    // RANKINGS
    // ─────────────────────────────────────────────────────────────────

    // ─────────────────────────────────────────────────────────────────
    // EVALUATION CRITERIA CRUD
    // ─────────────────────────────────────────────────────────────────

    public function defaultCriteria()
    {
        $criteria = DefaultEvaluationCriteria::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.default-criteria', compact('criteria'));
    }

    public function storeDefaultCriterion(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'aspect' => 'required|in:personal,organizational',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
        ]);
        DefaultEvaluationCriteria::create([
            ...$request->only('name', 'code', 'type', 'aspect', 'target_score', 'description', 'catatan'),
            'is_active' => true,
        ]);
        return back()->with('success', 'Default criterion added successfully!');
    }

    public function updateDefaultCriterion(Request $request, DefaultEvaluationCriteria $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'aspect' => 'required|in:personal,organizational',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
        ]);
        $criterion->update($request->only('name', 'code', 'type', 'aspect', 'target_score', 'description', 'catatan'));
        return back()->with('success', "Default criterion '{$criterion->name}' updated successfully!");
    }

    public function destroyDefaultCriterion(DefaultEvaluationCriteria $criterion)
    {
        $criterion->delete();
        return back()->with('success', 'Default criterion deleted successfully.');
    }

    public function listCriteria(Departmentsbiro $department)
    {
        $criteria = $department->evaluationCriteria()->orderBy('sort_order')->orderBy('id')->get();
        $defaultCriteria = DefaultEvaluationCriteria::orderBy('sort_order')->orderBy('id')->get();
        
        // Check if dirty
        $isDirty = false;
        if ($criteria->count() !== $defaultCriteria->count()) {
            $isDirty = true;
        } else {
            foreach ($criteria as $index => $c) {
                $dc = $defaultCriteria[$index];
                if (
                    $c->code !== $dc->code ||
                    $c->name !== $dc->name ||
                    $c->type !== $dc->type ||
                    $c->aspect !== $dc->aspect ||
                    $c->target_score !== $dc->target_score
                ) {
                    $isDirty = true;
                    break;
                }
            }
        }

        return view('admin.criteria', compact('department', 'criteria', 'isDirty'));
    }

    public function storeCriterion(Request $request, Departmentsbiro $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'aspect' => 'required|in:personal,organizational',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $department->evaluationCriteria()->create([
            ...$request->only('name', 'code', 'type', 'aspect', 'target_score', 'description', 'catatan'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);
        return back()->with('success', 'Criterion added successfully!');
    }

    public function updateCriterion(Request $request, Departmentsbiro $department, EvaluationCriteria $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:core,secondary',
            'target_score' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $criterion->update([
            ...$request->only('name', 'code', 'type', 'target_score', 'description', 'catatan'),
            'is_active' => $request->boolean('is_active', $criterion->is_active),
            'sort_order' => $request->sort_order ?? $criterion->sort_order,
        ]);
        return back()->with('success', "Criterion '{$criterion->name}' updated successfully!");
    }

    public function destroyCriterion(Departmentsbiro $department, EvaluationCriteria $criterion)
    {
        $criterion->delete();
        return back()->with('success', 'Criterion deleted successfully.');
    }

    public function resetCriteria(Departmentsbiro $department)
    {
        // Hard delete all existing criteria
        $department->evaluationCriteria()->delete();
        
        // Copy from DefaultEvaluationCriteria
        DefaultEvaluationCriteria::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->each(function (DefaultEvaluationCriteria $default) use ($department) {
                $department->evaluationCriteria()->create([
                    'default_criteria_id' => $default->id,
                    'code' => $default->code,
                    'name' => $default->name,
                    'description' => $default->description,
                    'type' => $default->type,
                    'aspect' => $default->aspect,
                    'target_score' => $default->target_score,
                    'catatan' => $default->catatan,
                    'is_active' => true,
                    'sort_order' => $default->sort_order,
                ]);
            });

        return back()->with('success', 'Criteria reset to defaults successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // INTERVIEW SCHEDULES SPREADSHEET
    // ─────────────────────────────────────────────────────────────────

    public function listSchedules(Request $request)
    {
        $departments = Departmentsbiro::where('is_active', true)->orderBy('name')->get();
        
        // Active tab defaults to first department
        $activeDepartmentId = $request->query('department_id', $departments->first()?->id);

        $schedules = [];
        $dates = [];
        $timeSlots = [];
        
        if ($activeDepartmentId) {
            $schedulesRaw = InterviewSchedule::with(['booking.candidate.user', 'booking.candidate.evaluations' => function($q) use ($activeDepartmentId) {
                $q->where('department_id', $activeDepartmentId);
            }])
            ->where('department_id', $activeDepartmentId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
            
            // Build the matrix bounds
            $dates = $schedulesRaw->pluck('date')->unique()->values();
            $timeSlotsRaw = $schedulesRaw->map(function($s) {
                return $s->start_time . '|' . $s->end_time;
            })->unique()->values();

            // Parse back to array for the grid
            foreach ($timeSlotsRaw as $ts) {
                [$start, $end] = explode('|', $ts);
                $timeSlots[] = ['start_time' => $start, 'end_time' => $end];
            }
            
            // Sort time slots by start_time
            usort($timeSlots, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));

            // Map schedules into [date][time_slot_key] = $schedule
            foreach ($schedulesRaw as $sch) {
                $timeKey = $sch->start_time . '|' . $sch->end_time;
                // date format is Y-m-d since $sch->date is a date string in the DB (or parsed datetime depending on casts)
                $dateKey = is_string($sch->date) ? \Carbon\Carbon::parse($sch->date)->format('Y-m-d') : $sch->date->format('Y-m-d');
                $schedules[$dateKey][$timeKey] = $sch;
            }
        }

        return view('admin.schedules', compact('departments', 'activeDepartmentId', 'schedules', 'dates', 'timeSlots'));
    }

    public function generateSchedules(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|exists:departmentsbiro,id',
            'all_departments' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'time_slots' => 'required|string', // Format: "09:00-09:40, 09:50-10:30"
        ]);

        if (!$request->department_id && !$request->boolean('all_departments')) {
            return back()->withErrors(['department_id' => 'Pilih departemen atau centang Semua Departemen.']);
        }

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        
        // Parse time slots
        $slotStrings = array_filter(array_map('trim', explode(',', $request->time_slots)));
        $parsedSlots = [];
        foreach ($slotStrings as $slot) {
            $parts = explode('-', $slot);
            if (count($parts) === 2) {
                $parsedSlots[] = [
                    'start_time' => date('H:i:s', strtotime(trim($parts[0]))),
                    'end_time' => date('H:i:s', strtotime(trim($parts[1])))
                ];
            }
        }

        if (empty($parsedSlots)) {
            return back()->withErrors(['time_slots' => 'Format time slots tidak valid. Contoh: 09:00-09:40, 09:50-10:30']);
        }

        $targetDepartmentIds = [];
        if ($request->boolean('all_departments')) {
            $targetDepartmentIds = Departmentsbiro::where('is_active', true)->pluck('id')->toArray();
        } else {
            $targetDepartmentIds = [$request->department_id];
        }

        $inserts = [];
        
        foreach ($targetDepartmentIds as $deptId) {
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                foreach ($parsedSlots as $slot) {
                    // Check if already exists to prevent duplicate
                    $exists = InterviewSchedule::where('department_id', $deptId)
                        ->whereDate('date', $currentDate->format('Y-m-d'))
                        ->where('start_time', $slot['start_time'])
                        ->where('end_time', $slot['end_time'])
                        ->exists();
                        
                    if (!$exists) {
                        $inserts[] = [
                            'department_id' => $deptId,
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                            'is_blocked' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                $currentDate->addDay();
            }
        }

        if (!empty($inserts)) {
            InterviewSchedule::insert($inserts);
            return redirect()->route('admin.schedules', ['department_id' => $request->department_id ?? $targetDepartmentIds[0]])
                ->with('success', count($inserts) . ' slot jadwal berhasil digenerate!');
        }

        return redirect()->route('admin.schedules', ['department_id' => $request->department_id ?? $targetDepartmentIds[0]])
            ->with('info', 'Tidak ada slot baru yang ditambahkan (mungkin sudah ada).');
    }

    public function toggleScheduleBlock(Request $request, InterviewSchedule $schedule)
    {
        $schedule->is_blocked = !$schedule->is_blocked;
        $schedule->save();
        
        return response()->json([
            'success' => true,
            'is_blocked' => $schedule->is_blocked
        ]);
    }

    public function clearSchedules(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|exists:departmentsbiro,id',
            'all_departments' => 'nullable|boolean',
        ]);
        
        $query = InterviewSchedule::doesntHave('booking');

        if ($request->boolean('all_departments')) {
            // No additional filter, deletes across all departments
        } elseif ($request->department_id) {
            $query->where('department_id', $request->department_id);
        } else {
            return back()->withErrors(['department_id' => 'Pilih departemen atau centang Semua Departemen.']);
        }

        $deleted = $query->delete();
            
        return back()
            ->with('success', "Berhasil menghapus {$deleted} slot jadwal yang kosong.");
    }

    public function decideCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'assigned_department_id' => 'required_if:status,accepted|exists:departmentsbiro,id|nullable',
        ]);

        if ($request->status === 'accepted') {
            $departmentId = $request->assigned_department_id;
            
            // Get active open recruitment for this candidate type
            $openRecruitment = OpenRecruitment::where('candidate_type', $candidate->candidate_type)
                ->where('status', 'open')
                ->first();
                
            if ($openRecruitment) {
                $quotaRecord = OpenRecruitmentQuota::where('candidate_type', $candidate->candidate_type)
                    ->where('department_id', $departmentId)
                    ->first();

                if (!$quotaRecord) {
                    $deptName = Departmentsbiro::find($departmentId)?->name ?? 'departemen terpilih';
                    return back()->with('error', "Kuota penerimaan untuk {$deptName} pada tipe {$candidate->candidate_type} belum dikonfigurasi.");
                }

                $acceptedCount = Announcement::where('assigned_department_id', $departmentId)
                    ->where('status', 'accepted')
                    ->whereHas('candidate', fn($query) => $query->where('candidate_type', $candidate->candidate_type))
                    ->where('candidate_id', '!=', $candidate->id)
                    ->count();

                if ($acceptedCount >= $quotaRecord->quota) {
                    $deptName = Departmentsbiro::find($departmentId)?->name ?? 'departemen terpilih';
                    return back()->with('error', "Kuota penerimaan untuk departemen {$deptName} pada tipe {$candidate->candidate_type} sudah penuh (Maks: {$quotaRecord->quota}).");
                }
            }
        }

        Announcement::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'status' => $request->status,
                'assigned_department_id' => $request->status === 'accepted' ? $request->assigned_department_id : null,
            ]
        );
        $candidate->update(['status' => 'completed']);
        return back()->with('success', "Decision saved successfully for {$candidate->user->name}.");
    }

    public function announcements(Request $request)
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $query = Announcement::with(['candidate.user', 'candidate.departmentChoices.department', 'assignedDepartment'])
            ->leftJoin('departmentsbiro', 'announcements.assigned_department_id', '=', 'departmentsbiro.id')
            ->select('announcements.*')
            ->orderBy('announcements.status', 'asc')
            ->orderBy('departmentsbiro.name', 'asc');

        if ($search) {
            $query->whereHas('candidate.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('announcements.status', $statusFilter);
        }

        $announcements = $query->paginate(15)->withQueryString();
        $isPublished = Announcement::where('is_published', true)->exists();
        $departments = Departmentsbiro::where('is_active', true)->get();

        return view('admin.announcements', compact('announcements', 'isPublished', 'departments', 'search', 'statusFilter'));
    }

    public function publishAnnouncements(Request $request)
    {
        $request->validate([
            'is_published' => 'required|boolean',
        ]);
        Announcement::query()->update([
            'is_published' => $request->is_published,
            'published_at' => $request->is_published ? now() : null,
        ]);
        $status = $request->is_published ? 'published' : 'hidden';
        return back()->with('success', "All final announcements successfully {$status} on the public board!");
    }

    // ─────────────────────────────────────────────────────────────────
    // DSS PROFILE MATCHING — ADMIN CORE
    // ─────────────────────────────────────────────────────────────────

    public function profileMatching(Request $request)
    {
        $departments = Departmentsbiro::with('evaluationCriteria')->get();

        $selectedDepartment = null;
        $criteria = collect();
        $rankings = [];
        $candidates = collect();
        $existingScores = []; // [candidate_id][criteria_id] => score
        $error = null;
        $search = $request->get('search', '');

        if ($request->filled('department_id')) {
            $selectedDepartment = Departmentsbiro::with('evaluationCriteria')
                ->find($request->department_id);

            if (!$selectedDepartment) {
                $error = 'Department not found.';
            } else {
                $criteria = $selectedDepartment->evaluationCriteria()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

                if ($criteria->isEmpty()) {
                    $error = "Department \"{$selectedDepartment->name}\" has no evaluation criteria defined yet. Add criteria via Admin → Criteria.";
                } else {
                    // All candidates who chose this department (choice 1 or 2) + search
                    $candidatesQuery = Candidate::with('user', 'departmentChoices.department')
                        ->whereHas('departmentChoices', function ($q) use ($selectedDepartment) {
                            $q->where('departmentsbiro_id', $selectedDepartment->id);
                        });

                    if ($search) {
                        $candidatesQuery->where(function ($q) use ($search) {
                            $q->whereHas('user', function ($uq) use ($search) {
                                $uq->where('name', 'like', '%' . $search . '%')
                                   ->orWhere('nim', 'like', '%' . $search . '%');
                            });
                        });
                    }

                    $candidates = $candidatesQuery->paginate(5);

                    // Pre-load existing evaluation scores into a 2D map
                    if ($candidates->isNotEmpty()) {
                        Evaluation::where('department_id', $selectedDepartment->id)
                            ->whereIn('candidate_id', $candidates->pluck('id'))
                            ->get()
                            ->each(function ($ev) use (&$existingScores) {
                                $existingScores[$ev->candidate_id][$ev->criteria_id] = [
                                    'score' => $ev->score,
                                    'notes' => $ev->notes,
                                ];
                            });
                    }

                    // Always run rankings (service handles empty gracefully)
                    $rankings = $this->dss->getDepartmentRankings($selectedDepartment);
                }
            }
        }

        return view('admin.profile-matching', compact(
            'departments',
            'selectedDepartment',
            'criteria',
            'rankings',
            'candidates',
            'existingScores',
            'error',
            'search'
        ));
    }

    public function profileMatchingSaveScores(Request $request, Candidate $candidate, Departmentsbiro $department)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
            'global_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $first = true;
            foreach ($request->scores as $criteriaId => $score) {
                $criteriaExists = EvaluationCriteria::where('id', $criteriaId)
                    ->where('department_id', $department->id)
                    ->exists();
                if (!$criteriaExists) continue;

                $evaluation = Evaluation::firstOrNew([
                    'candidate_id' => $candidate->id,
                    'department_id' => $department->id,
                    'criteria_id' => $criteriaId,
                ]);
                $evaluation->score = $score;
                if ($first) {
                    $evaluation->notes = $request->input('global_notes');
                    $first = false;
                } else {
                    $evaluation->notes = null;
                }
                $evaluation->interviewer_id = Auth::id(); // Admin also records as interviewer for tracking
                $evaluation->version = $evaluation->exists ? $evaluation->version + 1 : 1;
                $evaluation->save();
            }
            $candidate->update(['status' => 'evaluated']);
            DB::commit();
            return back()->with('success', "Scores for {$candidate->user->name} in {$department->name} saved successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save scores. Please try again.');
        }
    }

    public function profileMatchingResetScores(Candidate $candidate, Departmentsbiro $department)
    {
        Evaluation::where('candidate_id', $candidate->id)
            ->where('department_id', $department->id)
            ->delete();

        if ($candidate->evaluations()->count() === 0) {
            $candidate->update(['status' => 'scheduled']);
            $candidate->announcement()->delete();
        }

        return back()->with('success', "Scores for {$candidate->user->name} have been reset.");
    }
}
