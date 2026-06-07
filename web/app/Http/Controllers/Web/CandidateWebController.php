<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Candidate;
use App\Models\CandidateInterviewSchedule;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Services\CandidateOtpService;
use App\Services\OpenRecruitmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CandidateWebController extends Controller
{
    public function showUserRegisterForm(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectCandidateUser(Auth::user());
        }

        $candidateType = in_array($request->candidate_type, ['staff', 'bph'], true)
            ? $request->candidate_type
            : 'staff';

        return view('auth.register', compact('candidateType'));
    }

    public function registerUser(Request $request, CandidateOtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'candidate_type' => 'nullable|in:staff,bph',
        ]);

        DB::beginTransaction();
        try {
            $emailName = str($request->email)->before('@')->replace(['.', '_', '-'], ' ')->title()->toString();

            $user = User::create([
                'name' => $emailName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
            ]);

            Auth::login($user);
            session(['intended_candidate_type' => $request->candidate_type ?: 'staff']);
            $otpService->issueFor($user);

            DB::commit();

            return redirect()->route('candidate.otp.view')
                ->with('success', 'User account created successfully. Please verify your email OTP.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong during account creation. Please try again.')->withInput();
        }
    }

    public function showOtpForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return $this->redirectCandidateUser($user);
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request, CandidateOtpService $otpService)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $result = $otpService->verify($user, $request->otp);
        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        return $this->redirectCandidateUser($user)->with('success', 'Email verified successfully.');
    }

    public function resendOtp(CandidateOtpService $otpService)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return $this->redirectCandidateUser($user);
        }

        $otpService->issueFor($user);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function showCandidateRegisterForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'candidate') {
            return redirect()->route('landing');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view')->with('error', 'Please verify your email first.');
        }

        if ($user->candidate) {
            return redirect()->route('candidate.dashboard');
        }

        if (in_array($request->candidate_type, ['staff', 'bph'], true)) {
            session(['intended_candidate_type' => $request->candidate_type]);
        }

        $candidateType = session('intended_candidate_type', 'staff');

        $departments = Departmentsbiro::where('is_active', true)
            ->select('id', 'name', 'slug', 'description')
            ->orderBy('name')
            ->get();

        return view('candidate.register', compact('departments', 'candidateType'));
    }

    public function registerCandidate(Request $request, OpenRecruitmentService $openRecruitmentService)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'candidate') {
            return redirect()->route('landing');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view')->with('error', 'Please verify your email first.');
        }

        if ($user->nim) {
            return redirect()->route('candidate.dashboard')->with('error', 'Your profile is already completed.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'nim' => ['required', 'digits:10', 'unique:users,nim,' . $user->id],
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['nama'],
                'nickname' => $validated['nickname'],
                'nim' => $validated['nim'],
                'prodi' => $validated['prodi'],
                'kelas' => $validated['kelas'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            DB::commit();

            return redirect()->route('candidate.dashboard')
                ->with('success', 'Profile completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong while completing your profile. Please try again.')->withInput();
        }
    }

    /**
     * Determine where the candidate should be redirected based on what step they completed last.
     * Steps: preferences -> experience -> skills -> documents -> signatures -> schedule
     */
    private function getCandidateNextStep(Candidate $candidate): string
    {
        // Must have department preferences
        if (!$candidate->departmentChoices()->where('choice_order', 1)->exists()) {
            return route('candidate.preferences.view');
        }
        // Must have passed experience step — we track this via skills or just move on (experience is optional data)
        // Must have documents
        if (!$candidate->photo_path || !$candidate->instagram_proof_path || !$candidate->youtube_proof_path || !$candidate->political_statement_path) {
            // Check if we're past skills: if no docs at all, send to experience first
            if (!$candidate->photo_path && !$candidate->instagram_proof_path) {
                // Could be at experience or skills step — we'll check skills
                // If no skills ever added, assume they're at experience step
                // Experience is fully optional data so we can't gate on it; send to skills next
                return route('candidate.skills.view');
            }
            return route('candidate.documents.view');
        }
        // Must have both signatures
        if (!$candidate->candidate_signature_path || !$candidate->parent_signature_path) {
            return route('candidate.signatures.view');
        }
        // All done — go to schedule
        return route('candidate.schedule.view');
    }

    public function showDashboard()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view')->with('error', 'Please verify your email first.');
        }

        if (!$user->nim) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }

        $candidate = $user->candidate ?: new Candidate();
        $announcement = null;
        if ($candidate->exists) {
            $candidate->load(['selectedInterviewSchedule.schedule', 'announcement']);
            $announcement = $candidate->announcement;
        }
        $openRecruitments = \App\Models\OpenRecruitment::where('status', 'open')->get();

        return view('candidate.dashboardcandidate', compact('candidate', 'announcement', 'openRecruitments'));
    }

    public function showApplyStartPage(\App\Models\OpenRecruitment $openRecruitment)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if (!$user->nim) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete your profile first.');
        }

        if ($user->candidate) {
            // Resume from last incomplete step
            return redirect($this->getCandidateNextStep($user->candidate));
        }

        return view('candidate.start', ['oprec' => $openRecruitment]);
    }

    public function applyOprec(Request $request)
    {
        $request->validate([
            'open_recruitment_id' => 'required|exists:open_recruitments,id',
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if (!$user->nim) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete your profile first.');
        }

        $oprec = \App\Models\OpenRecruitment::findOrFail($request->open_recruitment_id);

        if ($user->candidate) {
            // Resume from last incomplete step
            return redirect($this->getCandidateNextStep($user->candidate));
        }

        return redirect()->route('candidate.preferences.view', ['open_recruitment_id' => $oprec->id])->with('success', 'Berhasil memulai pendaftaran. Silakan isi preferensi Anda.');
    }

    public function showScheduleForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view')->with('error', 'Please verify your email first.');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }

        // Block schedule access until all registration steps are complete
        $nextStep = $this->getCandidateNextStep($candidate);
        if ($nextStep !== route('candidate.schedule.view')) {
            return redirect($nextStep)->with('error', 'Harap selesaikan semua langkah pendaftaran terlebih dahulu.');
        }

        $announcement = Announcement::where('candidate_id', $candidate->id)->first();
        $dssResults = null;

        if ($announcement && $announcement->is_published && in_array($candidate->status, ['evaluated', 'completed'])) {
            $dss = app(\App\Services\ProfileMatchingService::class);
            $targetDept = $announcement->assigned_department_id ?: $candidate->first_choice_department?->id;
            $deptModel = Departmentsbiro::find($targetDept);
            if ($deptModel) {
                $dssResults = $dss->calculateScore($candidate, $deptModel);
            }
        }

        $firstChoiceDepartmentId = $candidate->first_choice_department?->id;
        
        // Matrix generation logic
        $dates = [];
        $timeSlots = [];
        $schedules = [];

        if ($firstChoiceDepartmentId) {
            $schedulesRaw = InterviewSchedule::with('booking')
                ->where('department_id', $firstChoiceDepartmentId)
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();
                
            $dates = $schedulesRaw->pluck('date')->unique()->values();
            $timeSlotsRaw = $schedulesRaw->map(function($s) {
                return $s->start_time . '|' . $s->end_time;
            })->unique()->values();

            foreach ($timeSlotsRaw as $ts) {
                [$start, $end] = explode('|', $ts);
                $timeSlots[] = ['start_time' => $start, 'end_time' => $end];
            }
            usort($timeSlots, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));

            foreach ($schedulesRaw as $sch) {
                $timeKey = $sch->start_time . '|' . $sch->end_time;
                $dateKey = is_string($sch->date) ? \Carbon\Carbon::parse($sch->date)->format('Y-m-d') : $sch->date->format('Y-m-d');
                $schedules[$dateKey][$timeKey] = $sch;
            }
        }

        $currentBookedSlotId = $candidate->selectedInterviewSchedule?->interview_schedule_id;
        
        $openRecruitment = \App\Models\OpenRecruitment::where('candidate_type', $candidate->candidate_type)->first();

        return view('candidate.schedule', compact('candidate', 'announcement', 'dssResults', 'currentBookedSlotId', 'openRecruitment', 'dates', 'timeSlots', 'schedules', 'firstChoiceDepartmentId'));
    }

    public function bookSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:interview_schedules,id',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }

        if (in_array($candidate->status, ['evaluated', 'completed'])) {
            return back()->with('error', 'You have already completed your interview and cannot change your schedule slot.');
        }

        $firstChoiceDepartmentId = $candidate->first_choice_department?->id;
        $newSlot = InterviewSchedule::where('is_blocked', false)->findOrFail($request->schedule_id);

        if ($newSlot->department_id !== $firstChoiceDepartmentId) {
            return back()->with('error', 'You can only book a schedule from your first-choice department.');
        }

        $bookedByOther = CandidateInterviewSchedule::where('interview_schedule_id', $newSlot->id)
            ->where('candidate_id', '!=', $candidate->id)
            ->exists();
        if ($bookedByOther) {
            return back()->with('error', 'Sorry, this schedule slot was just booked by someone else. Please choose another slot.');
        }

        CandidateInterviewSchedule::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'interview_schedule_id' => $newSlot->id,
                'department_id' => $newSlot->department_id,
            ]
        );
        $candidate->update(['status' => 'scheduled']);

        return back()->with('success', 'Your interview schedule has been successfully booked!');
    }

    private function redirectCandidateUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'interviewer') {
            return redirect()->route('interviewer.schedules');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view');
        }

        if (!$user->candidate) {
            return redirect()->route('candidate.register.view');
        }

        return redirect()->route('candidate.dashboard');
    }

    public function showPreferencesForm(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if ($candidate && $candidate->departmentChoices()->where('choice_order', 1)->exists()) {
            return redirect()->route('candidate.schedule.view');
        }

        $departments = \App\Models\Departmentsbiro::all();
        $oprecId = $request->query('open_recruitment_id');
        if ($oprecId) {
            $oprec = \App\Models\OpenRecruitment::find($oprecId);
        } else {
            $oprec = \App\Models\OpenRecruitment::where('status', 'open')->first();
        }

        if (!$oprec) {
            return redirect()->route('candidate.dashboard')->with('error', 'Tidak ada pendaftaran yang terbuka saat ini.');
        }

        return view('candidate.preferences', compact('departments', 'oprec', 'candidate'));
    }

    public function savePreferences(Request $request)
    {
        $request->validate([
            'open_recruitment_id' => 'required|exists:open_recruitments,id',
            'first_choice_department_id' => 'required|exists:departmentsbiro,id',
            'second_choice_department_id' => 'nullable|exists:departmentsbiro,id',
            'reason_for_department' => 'required|string',
            'weaknesses' => 'required|string',
            'concrete_steps_if_chosen' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $oprec = \App\Models\OpenRecruitment::findOrFail($request->open_recruitment_id);

        DB::beginTransaction();
        try {
            $candidate = $user->candidate;

            if (!$candidate) {
                $candidate = Candidate::create([
                    'user_id' => $user->id,
                    'candidate_type' => $oprec->candidate_type,
                    'department_choice_reason' => $request->reason_for_department,
                    'weakness_description' => $request->weaknesses,
                    'contribution_plan' => $request->concrete_steps_if_chosen,
                    'status' => 'registered',
                ]);

                Announcement::firstOrCreate(
                    ['candidate_id' => $candidate->id],
                    ['status' => 'pending', 'is_published' => false]
                );
            } else {
                $candidate->update([
                    'department_choice_reason' => $request->reason_for_department,
                    'weakness_description' => $request->weaknesses,
                    'contribution_plan' => $request->concrete_steps_if_chosen,
                ]);
            }

            $candidate->departmentChoices()->updateOrCreate(
                ['choice_order' => 1],
                ['departmentsbiro_id' => $request->first_choice_department_id]
            );

            if ($request->second_choice_department_id && $request->second_choice_department_id != $request->first_choice_department_id) {
                $candidate->departmentChoices()->updateOrCreate(
                    ['choice_order' => 2],
                    ['departmentsbiro_id' => $request->second_choice_department_id]
                );
            } else {
                $candidate->departmentChoices()->where('choice_order', 2)->delete();
            }

            DB::commit();

            return redirect()->route('candidate.experience.view')->with('success', 'Preferensi berhasil disimpan. Silakan isi riwayat pendidikan dan pengalaman Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan preferensi: ' . $e->getMessage());
        }
    }

    public function showExperienceForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Silakan mulai pendaftaran terlebih dahulu.');
        }

        $candidate->load(['educations', 'organizations', 'committees']);

        return view('candidate.experience', compact('candidate'));
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:candidate_educations,id',
            'education_type' => 'required|in:formal,informal',
            'school_name' => 'required|string|max:255',
            'start_year' => 'required|string|max:4',
            'end_year' => 'nullable|string|max:4',
            'city' => 'required|string|max:255',
            'major' => 'nullable|string|max:255',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $data = $request->only(['education_type', 'school_name', 'start_year', 'end_year', 'city', 'major']);

        if ($request->id) {
            $candidate->educations()->where('id', $request->id)->update($data);
            $msg = 'Riwayat pendidikan berhasil diperbarui.';
        } else {
            $candidate->educations()->create($data);
            $msg = 'Riwayat pendidikan berhasil ditambahkan.';
        }

        return back()->with('success', $msg);
    }

    public function destroyEducation($id)
    {
        $candidate = Auth::user()->candidate;
        if ($candidate) {
            $candidate->educations()->where('id', $id)->delete();
        }
        return back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }

    public function storeOrganization(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:candidate_organizations,id',
            'organization_name' => 'required|string|max:255',
            'start_year' => 'required|string|max:4',
            'end_year' => 'nullable|string|max:4',
            'place_or_institution' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $data = $request->only(['organization_name', 'start_year', 'end_year', 'place_or_institution', 'position']);

        if ($request->id) {
            $candidate->organizations()->where('id', $request->id)->update($data);
            $msg = 'Pengalaman organisasi berhasil diperbarui.';
        } else {
            $candidate->organizations()->create($data);
            $msg = 'Pengalaman organisasi berhasil ditambahkan.';
        }

        return back()->with('success', $msg);
    }

    public function destroyOrganization($id)
    {
        $candidate = Auth::user()->candidate;
        if ($candidate) {
            $candidate->organizations()->where('id', $id)->delete();
        }
        return back()->with('success', 'Pengalaman organisasi berhasil dihapus.');
    }

    public function storeCommittee(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:candidate_committees,id',
            'committee_name' => 'required|string|max:255',
            'start_year' => 'required|string|max:4',
            'end_year' => 'nullable|string|max:4',
            'organizer' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $data = $request->only(['committee_name', 'start_year', 'end_year', 'organizer', 'position']);

        if ($request->id) {
            $candidate->committees()->where('id', $request->id)->update($data);
            $msg = 'Pengalaman kepanitiaan berhasil diperbarui.';
        } else {
            $candidate->committees()->create($data);
            $msg = 'Pengalaman kepanitiaan berhasil ditambahkan.';
        }

        return back()->with('success', $msg);
    }

    public function destroyCommittee($id)
    {
        $candidate = Auth::user()->candidate;
        if ($candidate) {
            $candidate->committees()->where('id', $id)->delete();
        }
        return back()->with('success', 'Pengalaman kepanitiaan berhasil dihapus.');
    }

    public function nextFromExperience()
    {
        return redirect()->route('candidate.skills.view');
    }

    public function showSkillsFacilitiesForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Silakan mulai pendaftaran terlebih dahulu.');
        }

        $candidate->load(['skills', 'facilities']);

        return view('candidate.skills_facilities', compact('candidate'));
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:candidate_skills,id',
            'skill_type' => 'required|in:soft,hard',
            'skill_name' => 'required|string|max:255',
            'proficiency' => 'required|in:dasar,sedang,cakap',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $data = $request->only(['skill_type', 'skill_name', 'proficiency']);

        if ($request->id) {
            $candidate->skills()->where('id', $request->id)->update($data);
            $msg = 'Kemampuan berhasil diperbarui.';
        } else {
            $candidate->skills()->create($data);
            $msg = 'Kemampuan berhasil ditambahkan.';
        }

        return back()->with('success', $msg);
    }

    public function destroySkill($id)
    {
        $candidate = Auth::user()->candidate;
        if ($candidate) {
            $candidate->skills()->where('id', $id)->delete();
        }
        return back()->with('success', 'Kemampuan berhasil dihapus.');
    }

    public function storeFacility(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $candidate->facilities()->create(['facility_name' => $request->facility_name]);

        return back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function destroyFacility($id)
    {
        $candidate = Auth::user()->candidate;
        if ($candidate) {
            $candidate->facilities()->where('id', $id)->delete();
        }
        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }

    public function nextFromSkillsFacilities()
    {
        return redirect()->route('candidate.documents.view');
    }

    public function showDocumentsForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Silakan mulai pendaftaran terlebih dahulu.');
        }

        return view('candidate.documents', compact('candidate'));
    }

    public function saveDocuments(Request $request)
    {
        $request->validate([
            'photo'               => 'nullable|image|max:5120',  // 5MB
            'instagram_proof'     => 'nullable|image|max:5120',
            'youtube_proof'       => 'nullable|image|max:5120',
            'political_statement' => 'nullable|mimes:pdf|max:10240', // 10MB
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $updates = [];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidate_documents', 'public');
            $updates['photo_path'] = $path;
        }
        if ($request->hasFile('instagram_proof')) {
            $path = $request->file('instagram_proof')->store('candidate_documents', 'public');
            $updates['instagram_proof_path'] = $path;
        }
        if ($request->hasFile('youtube_proof')) {
            $path = $request->file('youtube_proof')->store('candidate_documents', 'public');
            $updates['youtube_proof_path'] = $path;
        }
        if ($request->hasFile('political_statement')) {
            $path = $request->file('political_statement')->store('candidate_documents', 'public');
            $updates['political_statement_path'] = $path;
        }

        if (!empty($updates)) {
            $candidate->update($updates);
        }

        return redirect()->route('candidate.signatures.view')->with('success', 'Berkas administratif berhasil disimpan.');
    }

    public function showSignaturesForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'candidate') {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Silakan mulai pendaftaran terlebih dahulu.');
        }

        return view('candidate.signatures', compact('candidate'));
    }

    public function saveSignatures(Request $request)
    {
        $request->validate([
            'candidate_signature' => 'nullable|image|max:2048',
            'parent_signature'    => 'nullable|image|max:2048',
        ]);

        $candidate = Auth::user()->candidate;
        if (!$candidate) return back()->with('error', 'Kandidat tidak ditemukan.');

        $updates = [];

        if ($request->hasFile('candidate_signature')) {
            $updates['candidate_signature_path'] = $request->file('candidate_signature')->store('candidate_signatures', 'public');
        }
        if ($request->hasFile('parent_signature')) {
            $updates['parent_signature_path'] = $request->file('parent_signature')->store('candidate_signatures', 'public');
        }

        if (!empty($updates)) {
            $candidate->update($updates);
        }

        return redirect()->route('candidate.schedule.view')->with('success', 'Tanda tangan berhasil disimpan. Pendaftaran selesai!');
    }
    public function showInterviewDetail()
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Kandidat tidak ditemukan.');
        }

        $schedule = $candidate->selectedInterviewSchedule?->schedule;
        if (!$schedule) {
            return redirect()->route('candidate.dashboard')->with('error', 'Belum ada jadwal wawancara yang dipilih.');
        }

        return view('candidate.interview-detail', compact('candidate', 'schedule'));
    }

    public function showRegistrationForm()
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Kandidat tidak ditemukan.');
        }

        return view('candidate.registration-form', compact('candidate'));
    }

    public function showRegistrationAttachments()
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Kandidat tidak ditemukan.');
        }

        return view('candidate.registration-attachments', compact('candidate'));
    }

    public function downloadDocument(Request $request, Candidate $candidate, string $field)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $user->role !== 'interviewer' && $user->candidate?->id !== $candidate->id) {
            abort(403, 'Unauthorized.');
        }

        $validFields = [
            'photo_path',
            'instagram_proof_path',
            'youtube_proof_path',
            'political_statement_path',
            'candidate_signature_path',
            'parent_signature_path',
        ];

        if (!in_array($field, $validFields)) {
            abort(404, 'Invalid document field.');
        }

        $path = $candidate->$field;
        if (!$path) {
            abort(404, 'Document not found.');
        }

        $storage = \Illuminate\Support\Facades\Storage::disk('public')->exists($path)
            ? \Illuminate\Support\Facades\Storage::disk('public')
            : \Illuminate\Support\Facades\Storage::disk('local');

        if (!$storage->exists($path)) {
            abort(404, 'Document not found.');
        }

        return $storage->response($path);
    }
}
