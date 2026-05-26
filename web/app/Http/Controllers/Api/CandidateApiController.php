<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CandidateApiController extends Controller
{
    // 1. Get departments list
    public function getDepartments()
    {
        $departments = Departmentsbiro::select('id', 'name', 'description')->get();
        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    // 2. Register Candidate & User Account from Flutter (Multipart Form Data)
    public function register(Request $request)
    {
        $request->validate([
            // User Account Credentials
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',

            // Candidate Profile Details
            'nim' => 'required|string|unique:candidates,nim',
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'required|exists:departmentsbiro,id|different:first_choice_id',

            // File Uploads
            'recruitment_form' => 'required|file|mimes:pdf|max:2048',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'statement_letter' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'social_media_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // A. Create the User Account
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
            ]);

            // B. Upload files
            $formPath = $request->file('recruitment_form')->store('recruitment_forms', 'public');
            $photoPath = $request->file('photo')->store('photos', 'public');
            $letterPath = $request->file('statement_letter')->store('statement_letters', 'public');
            $proofPath = $request->file('social_media_proof')->store('social_media_proofs', 'public');

            // C. Create Candidate profile
            $candidate = Candidate::create([
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

            // D. Generate Sanctum Bearer Token for Flutter
            $token = $user->createToken('candidate-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Account and candidate profile successfully registered!',
                'token' => $token,
                'candidate' => $candidate->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 3. Get available schedules for Flutter (Requires Sanctum Auth)
    public function getAvailableSchedules(Request $request)
    {
        $candidate = $request->user()->candidate;

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile not found.'
            ], 404);
        }

        $availableSlots = InterviewSchedule::whereNull('candidate_id')
            ->orWhere('candidate_id', $candidate->id)
            ->orderBy('scheduled_at', 'asc')
            ->select('id', 'session_name', 'scheduled_at', 'location', 'candidate_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableSlots,
            'current_booked_slot_id' => InterviewSchedule::where('candidate_id', $candidate->id)->value('id')
        ]);
    }

    // 4. Book / Change schedule from Flutter (Requires Sanctum Auth)
    public function bookSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:interview_schedules,id',
        ]);

        $candidate = $request->user()->candidate;

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile not found.'
            ], 404);
        }

        $newSlot = InterviewSchedule::findOrFail($request->schedule_id);

        if ($newSlot->candidate_id !== null && $newSlot->candidate_id !== $candidate->id) {
            return response()->json([
                'success' => false,
                'message' => 'This slot has already been booked by another candidate.'
            ], 422);
        }

        // Free up previous slot
        InterviewSchedule::where('candidate_id', $candidate->id)->update(['candidate_id' => null]);

        // Book slot
        $newSlot->update(['candidate_id' => $candidate->id]);

        $candidate->update(['status' => 'scheduled']);

        return response()->json([
            'success' => true,
            'message' => 'Schedule successfully booked!',
            'booked_slot' => $newSlot
        ]);
    }
}