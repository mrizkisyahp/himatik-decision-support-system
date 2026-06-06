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

        if ($user->candidate) {
            return redirect()->route('candidate.dashboard')->with('error', 'Your candidate profile is already registered.');
        }

        $validated = $request->validate([
            'candidate_type' => 'required|in:staff,bph',
            'nama' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'nim' => ['required', 'digits:10', 'unique:candidates,nim'],
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if (!$openRecruitmentService->isOpenFor($validated['candidate_type'])) {
            return back()
                ->with('error', 'Open recruitment ' . strtoupper($validated['candidate_type']) . ' sedang tidak dibuka.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user->update(['name' => $validated['nama']]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => $validated['candidate_type'],
                'nickname' => $validated['nickname'],
                'nim' => $validated['nim'],
                'prodi' => $validated['prodi'],
                'kelas' => $validated['kelas'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'photo_path' => 'testing/placeholder.jpg',
                'status' => 'registered',
            ]);

            Announcement::firstOrCreate(
                ['candidate_id' => $candidate->id],
                ['status' => 'pending', 'is_published' => false]
            );

            DB::commit();

            return redirect()->route('candidate.dashboard')
                ->with('success', 'Profile completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong while completing your profile. Please try again.')->withInput();
        }
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

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }

        $candidate->load(['selectedInterviewSchedule.schedule', 'announcement']);
        $announcement = $candidate->announcement;

        return view('candidate.dashboardcandidate', compact('candidate', 'announcement'));
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
        $availableSlots = InterviewSchedule::where('department_id', $firstChoiceDepartmentId)
            ->where('is_active', true)
            ->whereDoesntHave('booking', function ($query) use ($candidate) {
                $query->where('candidate_id', '!=', $candidate->id);
            })
            ->orderBy('scheduled_at', 'asc')
            ->get();
        $currentBookedSlotId = $candidate->selectedInterviewSchedule?->interview_schedule_id;
        
        $openRecruitment = \App\Models\OpenRecruitment::where('candidate_type', $candidate->candidate_type)->first();

        return view('candidate.schedule', compact('candidate', 'availableSlots', 'announcement', 'dssResults', 'currentBookedSlotId', 'openRecruitment'));
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
        $newSlot = InterviewSchedule::where('is_active', true)->findOrFail($request->schedule_id);

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
            return redirect()->route('interviewer.schedule');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('candidate.otp.view');
        }

        if (!$user->candidate) {
            return redirect()->route('candidate.register.view');
        }

        return redirect()->route('candidate.dashboard');
    }
}
