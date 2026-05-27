<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     *     "role": "candidate"
     *   },
     *   "candidate": {
     *     "id": 1,
     *     "nim": "2211501234",
     *     "status": "registered"
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Credentials not found"
     * }
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Credentials not found'
        ], 422);
    }

    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Logged in successfully!',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'candidate' => $user->role === 'candidate' ? $user->candidate : null,
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
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}