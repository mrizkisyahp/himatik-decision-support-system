<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CandidateWebController extends Controller
{
    // Stage 1: Show User Account Registration Form
    public function showUserRegisterForm()
    {
        /*
        // If already logged in, redirect them based on their status
        if (Auth::check()) {
            if (Auth::user()->role === 'candidate') {
                return redirect()->route('candidate.register.view');
            }
            return redirect('/');
        }
        */
        return view('auth.register');
    }

    // Stage 1: Handle User Account Registration
    public function registerUser(Request $request)
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
            DB::commit();

            // Auto-log the user in
            Auth::login($user);

            return redirect()->route('candidate.register.view')
                ->with('success', 'User account created successfully! Now, please complete your candidate profile.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong during account creation. Please try again.')->withInput();
        }
    }

    // Stage 2: Show Candidate Profile Completion Form (Public Documentation)
    public function showCandidateRegisterForm()
    {
        /*
        if (!Auth::check()) {
            return redirect()->route('user.register.view');
        }

        $user = Auth::user();
        if ($user->role !== 'candidate') {
            return redirect('/');
        }
        
        // If the candidate profile already exists, skip to schedule booking
        if ($user->candidate) {
            return redirect()->route('candidate.schedule.view');
        }
        */

        $departments = Departmentsbiro::select('name', 'description', 'id')->get();
        return view('candidate.register', compact('departments'));
    }

    // Stage 2: Handle Candidate Profile Completion (Guarded manually)
    public function registerCandidate(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.register.view');
        }

        $user = Auth::user();
        if ($user->role !== 'candidate') {
            return redirect('/');
        }

        $type = $request->candidate_type; // 'staff' or 'bph'

        $rules = [
            'candidate_type' => 'required|in:staff,bph',
            'nim' => 'required|string|unique:candidates,nim',
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'required|exists:departmentsbiro,id',
            'recruitment_form' => 'required|file|mimes:pdf|max:2048',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ];

        // Staff requires 2 additional documents; BPH does not
        if ($type === 'staff') {
            $rules['statement_letter'] = 'required|file|mimes:pdf,jpg,png,jpeg|max:2048';
            $rules['social_media_proof'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        $user = Auth::user();

        // Prevent double submission
        if ($user->candidate) {
            return redirect()->route('candidate.schedule.view')
                ->with('error', 'Your candidate profile is already registered.');
        }

        DB::beginTransaction();
        try {
            // Upload common files
            $formPath = $request->file('recruitment_form')->store('recruitment_forms', 'public');
            $photoPath = $request->file('photo')->store('photos', 'public');

            // Staff-only uploads
            $letterPath = $type === 'staff'
                ? $request->file('statement_letter')->store('statement_letters', 'public')
                : null;
            $proofPath = $type === 'staff'
                ? $request->file('social_media_proof')->store('social_media_proofs', 'public')
                : null;

            // Create Candidate Profile linked to authenticated user
            Candidate::create([
                'user_id' => $user->id,
                'candidate_type' => $type,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'phone' => $request->phone,
                'first_choice_id' => $request->first_choice_id,
                'second_choice_id' => $request->second_choice_id,
                'recruitment_form_path' => $formPath,
                'photo_path' => $photoPath,
                'statement_letter_path' => $letterPath,
                'social_media_proof_path' => $proofPath,
                'status' => 'registered'
            ]);
            DB::commit();

            return redirect()->route('candidate.schedule.view')
                ->with('success', 'Profile completed successfully! Now, please select your interview schedule.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while completing your profile. Please try again.')->withInput();
        }
    }
    // 3. Show interview schedule booking page (Secured by 'auth' middleware)
    public function showScheduleForm()
    {
        $user = Auth::user();
        $candidate = $user ? $user->candidate : \App\Models\Candidate::with(['firstChoice', 'secondChoice'])->first();

        /*
        if (!$candidate) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }
        */

        // Fetch announcement to check if results are ready & published
        $announcement = \App\Models\Announcement::where('candidate_id', $candidate->id)->first();
        $dssResults = null;

        // Eagerly calculate private DSS scores for their outcomes once published and interview completes
        if ($announcement && $announcement->is_published && in_array($candidate->status, ['evaluated', 'completed'])) {
            $dss = app(\App\Services\ProfileMatchingService::class);
            $targetDept = $announcement->assigned_department_id ?: $candidate->first_choice_id;
            $deptModel = \App\Models\Departmentsbiro::find($targetDept);
            if ($deptModel) {
                $dssResults = $dss->calculateScore($candidate, $deptModel);
            }
        }

        // Get slots that are either unbooked or currently booked by THIS candidate
        $availableSlots = InterviewSchedule::whereNull('candidate_id')
            ->orWhere('candidate_id', $candidate->id)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('candidate.schedule', compact('candidate', 'availableSlots', 'announcement', 'dssResults'));
    }
    // 4. Book / Change an interview schedule (Secured by 'auth' middleware)
    public function bookSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:interview_schedules,id',
        ]);
        $candidate = Auth::user()->candidate;

        // LOCKOUT access once candidate has been evaluated/completed
        if (in_array($candidate->status, ['evaluated', 'completed'])) {
            return back()->with('error', 'You have already completed your interview and cannot change your schedule slot.');
        }

        $newSlot = InterviewSchedule::findOrFail($request->schedule_id);
        // Check if the slot is taken by someone else
        if ($newSlot->candidate_id !== null && $newSlot->candidate_id !== $candidate->id) {
            return back()->with('error', 'Sorry, this schedule slot was just booked by someone else. Please choose another slot.');
        }
        // Free up their old slot first
        InterviewSchedule::where('candidate_id', $candidate->id)->update(['candidate_id' => null]);
        // Book the new slot
        $newSlot->update(['candidate_id' => $candidate->id]);
        // Update candidate status
        $candidate->update(['status' => 'scheduled']);
        return back()->with('success', 'Your interview schedule has been successfully booked!');
    }
}