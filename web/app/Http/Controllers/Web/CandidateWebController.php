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
    // 1. Show the registration page
    public function showRegisterForm()
    {
        // If already logged in, redirect them to their schedule booking
        if (Auth::check() && Auth::user()->role === 'candidate') {
            return redirect()->route('candidate.schedule.view');
        }
        $departments = Departmentsbiro::all();
        return view('candidate.register', compact('departments'));
    }
    // 2. Handle candidate and user registration
    public function register(Request $request)
    {
        $request->validate([
            // User credentials
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            // Candidate profile details
            'nim' => 'required|string|unique:candidates,nim',
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'required|exists:departmentsbiro,id|different:first_choice_id',

            // File uploads
            'recruitment_form' => 'required|file|mimes:pdf|max:2048',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'statement_letter' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'social_media_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Using a Database Transaction to ensure both User and Candidate are created successfully
        DB::beginTransaction();
        try {
            // A. Create the User Account
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
            ]);
            // B. Upload files securely
            $formPath = $request->file('recruitment_form')->store('recruitment_forms', 'public');
            $photoPath = $request->file('photo')->store('photos', 'public');
            $letterPath = $request->file('statement_letter')->store('statement_letters', 'public');
            $proofPath = $request->file('social_media_proof')->store('social_media_proofs', 'public');
            // C. Create the Candidate profile linked to the user
            Candidate::create([
                'user_id' => $user->id,
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
            // D. Automatically log the candidate in!
            Auth::login($user);
            return redirect()->route('candidate.schedule.view')
                ->with('success', 'Account created successfully! Now, please select your interview schedule.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong during registration. Please try again.')->withInput();
        }
    }
    // 3. Show interview schedule booking page (Secured by 'auth' middleware)
    public function showScheduleForm()
    {
        $candidate = Auth::user()->candidate;
        if (!$candidate) {
            return redirect()->route('candidate.register.view')->with('error', 'Please complete registration first.');
        }
        // Get slots that are either unbooked or currently booked by THIS candidate
        $availableSlots = InterviewSchedule::whereNull('candidate_id')
            ->orWhere('candidate_id', $candidate->id)
            ->orderBy('scheduled_at', 'asc')
            ->get();
        return view('candidate.schedule', compact('candidate', 'availableSlots'));
    }
    // 4. Book / Change an interview schedule (Secured by 'auth' middleware)
    public function bookSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:interview_schedules,id',
        ]);
        $candidate = Auth::user()->candidate;
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