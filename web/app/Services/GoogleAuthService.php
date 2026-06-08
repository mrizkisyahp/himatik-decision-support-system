<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthService
{
    public function findOrCreateVerifiedUser(array $googleUser): User
    {
        $email = strtolower((string) $googleUser['email']);
        $googleId = (string) $googleUser['google_id'];

        $user = User::where('google_id', $googleId)->first()
            ?: User::where('email', $email)->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleId,
                'auth_provider' => $user->auth_provider === 'local' ? 'google' : $user->auth_provider,
                'avatar_url' => $googleUser['avatar_url'] ?? $user->avatar_url,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();

            return $user->fresh();
        }

        return User::create([
            'name' => $googleUser['name'] ?: str($email)->before('@')->replace(['.', '_', '-'], ' ')->title()->toString(),
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'role' => 'candidate',
            'google_id' => $googleId,
            'auth_provider' => 'google',
            'avatar_url' => $googleUser['avatar_url'] ?? null,
            'email_verified_at' => now(),
        ]);
    }

    public function resolveNextStep(User $user): string
    {
        if ($user->role !== 'candidate') {
            return 'dashboard';
        }

        $candidate = $user->candidate;

        if (! $user->email_verified_at) {
            return 'verify_email';
        }

        if (! $candidate) {
            return 'candidate_registration';
        }

        return $candidate->interviewSchedules()->exists() ? 'candidate_status' : 'schedule_selection';
    }
}
