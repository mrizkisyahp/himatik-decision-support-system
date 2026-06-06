<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CandidateInterviewSchedule;
use App\Models\Departmentsbiro;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Services\CandidateOtpService;
use App\Services\CandidateProfileService;
use App\Services\OpenRecruitmentService;
use App\Support\CandidateProfileRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $departments = Departmentsbiro::where('is_active', true)
            ->select('id', 'name', 'slug', 'description')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }

    /**
     * Register Candidate Account
     *
     * Register a candidate account and send an email OTP. This endpoint only creates the user account.
     * The candidate profile is submitted separately after OTP verification.
     *
     * @group Candidate
     * @unauthenticated
     *
     * @bodyParam email string required Candidate email. Must be unique. Example: ahmad@student.pnj.ac.id
     * @bodyParam nama string required Full name. Example: Ahmad Rizki
     * @bodyParam password string required Min 8 chars. Example: password123
     * @bodyParam password_confirmation string required Must match password. Example: password123
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Account created successfully. OTP sent to email.",
     *   "token": "2|xyz789...",
     *   "user": {
     *     "id": 1,
     *     "name": "Ahmad Rizki",
     *     "email": "ahmad@student.pnj.ac.id",
     *     "role": "candidate",
     *     "email_verified": false
     *   },
     *   "next_step": "verify_email"
     * }
     */
    public function register(Request $request, CandidateOtpService $otpService)
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

            $otpService->issueFor($user);
            $token = $user->createToken('candidate-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully. OTP sent to email.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'email_verified' => false,
                ],
                'next_step' => 'verify_email',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit Candidate Profile
     *
     * Submit the verified candidate's full registration profile. Accepts `multipart/form-data`.
     *
     * @group Candidate
     * @authenticated
     *
     * @header Content-Type multipart/form-data
     *
     * @bodyParam candidate_type string required Registration type. One of: `staff`, `bph`. Example: staff
     * @bodyParam nickname string required Candidate nickname. Example: Ahmad
     * @bodyParam nim string required Exactly 10 digits and unique. Example: 2211501234
     * @bodyParam prodi string required Study program. Example: Teknik Informatika
     * @bodyParam kelas string required Class name. Example: TI-2A
     * @bodyParam phone string required Phone number. Example: 081234567890
     * @bodyParam address string required Full address.
     * @bodyParam first_choice_id integer required Department ID for first choice. Example: 1
     * @bodyParam second_choice_id integer optional Department ID for second choice. Must differ from first choice. Example: 2
     * @bodyParam department_choice_reason string required Combined reason for department choices.
     * @bodyParam weakness_description string required Self-described weakness.
     * @bodyParam contribution_plan string required Concrete plan if selected.
     * @bodyParam photo file required Candidate photo image.
     * @bodyParam instagram_proof file required Instagram follow proof image.
     * @bodyParam youtube_proof file required YouTube subscribe proof image.
     * @bodyParam political_statement file required Statement letter file.
     * @bodyParam candidate_signature file required Candidate signature image.
     * @bodyParam parent_signature file required Parent signature image.
     * @bodyParam educations array optional Education rows.
     * @bodyParam organizations array optional External organization rows.
     * @bodyParam committees array optional Committee rows.
     * @bodyParam skills array optional Skill rows.
     * @bodyParam facilities array optional Facility rows.
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Candidate profile registered successfully!",
     *   "candidate": {
     *     "id": 1,
     *     "nim": "2211501234",
     *     "status": "registered"
     *   },
     *   "next_step": "schedule_selection"
     * }
     */
    public function storeProfile(Request $request, CandidateProfileService $profileService, OpenRecruitmentService $openRecruitmentService)
    {
        $user = $request->user();
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email is not verified.',
            ], 403);
        }

        if ($user->candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile already exists.',
            ], 409);
        }

        $validated = $request->validate(CandidateProfileRules::rules());

        if (!$openRecruitmentService->isOpenFor($validated['candidate_type'])) {
            return response()->json([
                'success' => false,
                'message' => 'Open recruitment ' . strtoupper($validated['candidate_type']) . ' is currently closed.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $candidate = $profileService->createFor($user, $validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate profile registered successfully!',
                'candidate' => $candidate,
                'next_step' => 'schedule_selection',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Candidate profile registration failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Candidate Profile, Schedule & Results
     *
     * Returns the authenticated user's profile info, candidate profile if it exists,
     * booked interview schedule slot, and final announcement outcome & DSS score breakdown if published.
     *
     * @group Candidate
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "user": {"id": 1, "name": "Ahmad Rizki", "email": "candidate@himatik.ac.id", "role": "candidate", "email_verified": true},
     *   "candidate": null,
     *   "schedule": null,
     *   "announcement": null,
     *   "dss_results": null,
     *   "next_step": "candidate_registration"
     * }
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $candidate = $user->candidate;
        $announcement = null;
        $dssResults = null;
        $schedule = null;

        if ($candidate) {
            $candidate->load([
                'departmentChoices.department',
                'educations',
                'organizations',
                'committees',
                'skills',
                'facilities',
                'selectedInterviewSchedule.schedule.department',
                'spkResults.department',
            ]);
            $schedule = $candidate->selectedInterviewSchedule?->schedule;
            $announcement = Announcement::where('candidate_id', $candidate->id)->first();

            if ($announcement && $announcement->is_published && in_array($candidate->status, ['evaluated', 'completed'])) {
                $dss = app(\App\Services\ProfileMatchingService::class);
                $targetDept = $announcement->assigned_department_id ?: $candidate->first_choice_department?->id;
                $deptModel = Departmentsbiro::find($targetDept);
                if ($deptModel) {
                    $dssResults = $dss->calculateScore($candidate, $deptModel);
                }
            }
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => (bool) $user->email_verified_at,
            ],
            'candidate' => $candidate,
            'schedule' => $schedule,
            'announcement' => $announcement,
            'dss_results' => $dssResults,
            'next_step' => $this->resolveNextStep($user, $candidate, $schedule),
        ]);
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
                'message' => 'Candidate profile not found.',
            ], 404);
        }

        $firstChoiceDepartmentId = $candidate->first_choice_department?->id;
        if (!$firstChoiceDepartmentId) {
            return response()->json([
                'success' => false,
                'message' => 'First choice department not found.',
            ], 422);
        }

        $currentBooking = $candidate->selectedInterviewSchedule;
        $availableSlots = InterviewSchedule::where('department_id', $firstChoiceDepartmentId)
            ->where('is_active', true)
            ->whereDoesntHave('booking', function ($query) use ($candidate) {
                $query->where('candidate_id', '!=', $candidate->id);
            })
            ->orderBy('scheduled_at', 'asc')
            ->select('id', 'department_id', 'session_name', 'scheduled_at', 'location', 'is_active')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableSlots,
            'current_booked_slot_id' => $currentBooking?->interview_schedule_id,
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
                'message' => 'Candidate profile not found.',
            ], 404);
        }

        $firstChoiceDepartmentId = $candidate->first_choice_department?->id;
        $newSlot = InterviewSchedule::where('is_active', true)->findOrFail($request->schedule_id);

        if ($newSlot->department_id !== $firstChoiceDepartmentId) {
            return response()->json([
                'success' => false,
                'message' => 'You can only book a schedule from your first-choice department.',
            ], 422);
        }

        $bookedByOther = CandidateInterviewSchedule::where('interview_schedule_id', $newSlot->id)
            ->where('candidate_id', '!=', $candidate->id)
            ->exists();
        if ($bookedByOther) {
            return response()->json([
                'success' => false,
                'message' => 'This slot has already been booked by another candidate.',
            ], 422);
        }

        CandidateInterviewSchedule::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'interview_schedule_id' => $newSlot->id,
                'department_id' => $newSlot->department_id,
            ]
        );
        $candidate->update(['status' => 'scheduled']);

        return response()->json([
            'success' => true,
            'message' => 'Schedule successfully booked!',
            'booked_slot' => $newSlot->load('department'),
        ]);
    }

    private function resolveNextStep(User $user, mixed $candidate, mixed $schedule): string
    {
        if (!$user->email_verified_at) {
            return 'verify_email';
        }

        if (!$candidate) {
            return 'candidate_registration';
        }

        if (!$schedule) {
            return 'schedule_selection';
        }

        return 'candidate_status';
    }
}
