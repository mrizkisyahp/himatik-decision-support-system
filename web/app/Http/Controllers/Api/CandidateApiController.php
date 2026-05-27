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
    /**
     * Get Departments List
     *
     * Returns all available departments/biros for candidates to select as their recruitment preferences.
     *
     * @group Candidate
     * @unauthenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {"id": 1, "name": "Biro Humas", "description": "Biro Hubungan Masyarakat"},
     *     {"id": 2, "name": "Biro Akademik", "description": "Mengelola kegiatan akademik"}
     *   ]
     * }
     */
    public function getDepartments()
    {
        $departments = Departmentsbiro::select('id', 'name', 'description')->get();
        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    /**
     * Register Candidate
     *
     * Register a new candidate account. Accepts `multipart/form-data` due to file uploads.
     * On success, returns a Sanctum token so the candidate is immediately logged in.
     *
     * @group Candidate
     * @unauthenticated
     *
     * @header Content-Type multipart/form-data
     *
     * @bodyParam candidate_type string required Registration type. One of: `staff`, `bph`. Example: staff
     * @bodyParam email string required Candidate email. Must be unique. Example: ahmad@student.pnj.ac.id
     * @bodyParam nama string required Full name. Example: Ahmad Rizki
     * @bodyParam password string required Min 8 chars. Example: password123
     * @bodyParam password_confirmation string required Must match password. Example: password123
     * @bodyParam nim string required Student ID number. Must be unique. Example: 2211501234
     * @bodyParam prodi string required Study program. One of: `Teknik Informatika`, `Teknik Multimedia dan Jaringan`, `Teknik Multimedia dan Digital`. Example: Teknik Informatika
     * @bodyParam kelas string required Class name. Example: TI-2A
     * @bodyParam phone string required Phone number. Example: 081234567890
     * @bodyParam first_choice_id integer required Department ID for first choice. Example: 1
     * @bodyParam second_choice_id integer required Department ID for second choice. Example: 2
     * @bodyParam recruitment_form file required PDF file. Max 2MB.
     * @bodyParam photo file required JPEG/PNG image. Max 1MB. Staff: kemeja putih BG biru. BPH: jaket TIK BG biru.
     * @bodyParam statement_letter file required for staff only. PDF or image. Max 2MB.
     * @bodyParam social_media_proof file required for staff only. Screenshot image (JPEG/PNG). Max 2MB.
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Account and candidate profile successfully registered!",
     *   "token": "2|xyz789...",
     *   "candidate": {
     *     "id": 1,
     *     "nim": "2211501234",
     *     "prodi": "Teknik Informatika",
     *     "status": "registered"
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "message": "The nim has already been taken.",
     *   "errors": {"nim": ["The nim has already been taken."]}
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Registration failed. Please try again.",
     *   "error": "..."
     * }
     */
    public function register(Request $request)
    {
        $type = $request->candidate_type;

        $rules = [
            'candidate_type' => 'required|in:staff,bph',
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|string|unique:candidates,nim',
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'first_choice_id' => 'required|exists:departmentsbiro,id',
            'second_choice_id' => 'required|exists:departmentsbiro,id',
            'recruitment_form' => 'required|file|mimes:pdf|max:2048',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ];

        if ($type === 'staff') {
            $rules['statement_letter'] = 'required|file|mimes:pdf,jpg,png,jpeg|max:2048';
            $rules['social_media_proof'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // A. Create the User Account
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
            ]);

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

            // C. Create Candidate profile
            $candidate = Candidate::create([
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

    /**
     * Get Available Interview Schedules
     *
     * Returns all available interview time slots, plus the currently booked slot (if any) for the authenticated candidate.
     *
     * @group Candidate
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 3,
     *       "session_name": "Sesi Pagi A",
     *       "scheduled_at": "2025-08-15T09:00:00.000000Z",
     *       "location": "Ruang Rapat HIMATIK",
     *       "candidate_id": null
     *     }
     *   ],
     *   "current_booked_slot_id": null
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Candidate profile not found."
     * }
     */
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

    /**
     * Book Interview Schedule
     *
     * Book or change the candidate's interview time slot. Automatically releases any previously booked slot.
     * Updates candidate status to `scheduled`.
     *
     * @group Candidate
     * @authenticated
     *
     * @bodyParam schedule_id integer required ID of the schedule slot to book. Example: 3
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Schedule successfully booked!",
     *   "booked_slot": {
     *     "id": 3,
     *     "session_name": "Sesi Pagi A",
     *     "scheduled_at": "2025-08-15T09:00:00.000000Z",
     *     "location": "Ruang Rapat HIMATIK",
     *     "candidate_id": 1
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "This slot has already been booked by another candidate."
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Candidate profile not found."
     * }
     */
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