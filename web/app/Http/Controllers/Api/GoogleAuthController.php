<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    /**
     * Login With Google
     *
     * Authenticate or register a candidate account using a Google ID token from a mobile client.
     * The backend verifies the token with Google, requires a verified Google email, then returns
     * a Sanctum token and the same next-step contract used by normal login.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam id_token string required Google ID token returned by the mobile Google Sign-In SDK.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged in with Google successfully.",
     *   "token": "1|abc123...",
     *   "user": {
     *     "id": 1,
     *     "name": "Nama Google",
     *     "email": "user@gmail.com",
     *     "role": "candidate",
     *     "email_verified": true
     *   },
     *   "candidate": null,
     *   "next_step": "candidate_registration"
     * }
     */
    public function login(Request $request, GoogleAuthService $googleAuthService)
    {
        $validated = $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        $response = Http::acceptJson()
            ->timeout(15)
            ->get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $validated['id_token'],
            ]);

        if (! $response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Google token.',
            ], 422);
        }

        $payload = $response->json();
        if (! data_get($payload, 'sub') || ! data_get($payload, 'email')) {
            return response()->json([
                'success' => false,
                'message' => 'Google token payload is incomplete.',
            ], 422);
        }

        $clientId = config('services.google.client_id');

        if ($clientId && data_get($payload, 'aud') !== $clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Google token audience is invalid.',
            ], 422);
        }

        if (! filter_var(data_get($payload, 'email_verified'), FILTER_VALIDATE_BOOLEAN)) {
            return response()->json([
                'success' => false,
                'message' => 'Google email is not verified.',
            ], 422);
        }

        $user = $googleAuthService->findOrCreateVerifiedUser([
            'google_id' => data_get($payload, 'sub'),
            'name' => data_get($payload, 'name'),
            'email' => data_get($payload, 'email'),
            'avatar_url' => data_get($payload, 'picture'),
        ]);

        $candidate = $user->role === 'candidate' ? $user->candidate : null;
        $token = $user->createToken('google-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in with Google successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => (bool) $user->email_verified_at,
            ],
            'candidate' => $candidate,
            'next_step' => $googleAuthService->resolveNextStep($user),
        ]);
    }
}
