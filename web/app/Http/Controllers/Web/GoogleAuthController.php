<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        if (in_array($request->candidate_type, ['staff', 'bph'], true)) {
            session(['intended_candidate_type' => $request->candidate_type]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(GoogleAuthService $googleAuthService)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed. Please try again.',
            ]);
        }

        if (! $googleUser->getId() || ! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google account data is incomplete.',
            ]);
        }

        if (! (bool) data_get($googleUser->user, 'email_verified')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google email is not verified.',
            ]);
        }

        $user = $googleAuthService->findOrCreateVerifiedUser([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'avatar_url' => $googleUser->getAvatar(),
        ]);

        Auth::login($user, true);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'interviewer') {
            return redirect()->route('interviewer.schedules');
        }

        if (! $user->candidate) {
            return redirect()->route('candidate.register.view');
        }

        return redirect()->route('candidate.dashboard');
    }
}
