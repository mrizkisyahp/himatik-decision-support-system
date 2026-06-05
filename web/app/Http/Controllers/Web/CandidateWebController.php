<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CandidateInterviewSchedule;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Services\CandidateOtpService;
use App\Services\CandidateProfileService;
use App\Support\CandidateProfileRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CandidateWebController extends Controller
{
    public function showUserRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectCandidateUser(Auth::user());
        }

        return view('auth.register');
    }

    public function registerUser(Request $request, CandidateOtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
            ]);

            Auth::login($user);
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

        return redirect()->route('landing')->with('success', 'Email verified successfully.');
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

    public function showCandidateRegisterForm()
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
            return redirect()->route('candidate.schedule.view');
        }

        $departments = Departmentsbiro::where('is_active', true)
            ->select('id', 'name', 'slug', 'description')
            ->orderBy('name')
            ->get();

        return view('candidate.register', compact('departments'));
    }

    public function registerCandidate(Request $request, CandidateProfileService $profileService)
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
            return redirect()->route('candidate.schedule.view')->with('error', 'Your candidate profile is already registered.');
        }

        $validated = $request->validate(CandidateProfileRules::rules());

        DB::beginTransaction();
        try {
            $profileService->createFor($user, $validated);

            DB::commit();

            return redirect()->route('candidate.schedule.view')
                ->with('success', 'Profile completed successfully! Now, please select your interview schedule.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong while completing your profile. Please try again.')->withInput();
        }
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

        return view('candidate.schedule', compact('candidate', 'availableSlots', 'announcement', 'dssResults', 'currentBookedSlotId'));
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

        return redirect()->route('candidate.schedule.view');
    }
}
