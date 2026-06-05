<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CandidateOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthApiController extends Controller
{
    /**
     * Login
     *
     * Authenticate a user and receive a Sanctum Bearer token for use on protected endpoints.
     * Works for all roles: `candidate`, `interviewer`, and `admin`.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam email string required The user's email address. Example: candidate@himatik.ac.id
     * @bodyParam password string required The user's password. Example: password123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged in successfully!",
     *   "token": "1|abc123def456...",
     *   "user": {
     *     "id": 1,
     *     "name": "Ahmad Rizki",
     *     "email": "candidate@himatik.ac.id",
     *     "role": "candidate",
     *     "email_verified": true
     *   },
     *   "candidate": null,
     *   "next_step": "candidate_registration"
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credentials not found',
            ], 422);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        $candidate = $user->role === 'candidate' ? $user->candidate : null;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => (bool) $user->email_verified_at,
            ],
            'candidate' => $candidate,
            'next_step' => $this->resolveNextStep($user, $candidate),
        ]);
    }

    /**
     * Verify Email OTP
     *
     * Verify the candidate user's email OTP and mark the email as verified.
     *
     * @group Authentication
     * @authenticated
     *
     * @bodyParam otp string required Six digit OTP code. Example: 123456
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Email verified successfully.",
     *   "redirect_to": "landing",
     *   "next_step": "candidate_registration"
     * }
     */
    public function verifyOtp(Request $request, CandidateOtpService $otpService)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = $request->user();
        if ($user->role !== 'candidate') {
            return response()->json([
                'success' => false,
                'message' => 'Only candidate accounts can verify OTP.',
            ], 403);
        }

        $result = $otpService->verify($user, $request->otp);
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
            'redirect_to' => 'landing',
            'next_step' => $user->candidate ? 'schedule_selection' : 'candidate_registration',
        ]);
    }

    /**
     * Resend Email OTP
     *
     * Send a new OTP to the authenticated candidate user's email.
     *
     * @group Authentication
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "OTP sent successfully."
     * }
     */
    public function resendOtp(Request $request, CandidateOtpService $otpService)
    {
        $user = $request->user();
        if ($user->role !== 'candidate') {
            return response()->json([
                'success' => false,
                'message' => 'Only candidate accounts can request OTP.',
            ], 403);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.',
            ], 422);
        }

        $otpService->issueFor($user);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ]);
    }

    /**
     * Logout
     *
     * Revoke the current Sanctum Bearer token, effectively logging the user out.
     *
     * @group Authentication
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    private function resolveNextStep(User $user, mixed $candidate): string
    {
        if ($user->role !== 'candidate') {
            return 'dashboard';
        }

        if (!$user->email_verified_at) {
            return 'verify_email';
        }

        if (!$candidate) {
            return 'candidate_registration';
        }

        return $candidate && $candidate->interviewSchedules()->exists() ? 'candidate_status' : 'schedule_selection';
    }
}
