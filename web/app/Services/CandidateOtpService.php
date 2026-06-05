<?php

namespace App\Services;

use App\Mail\CandidateOtpMail;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CandidateOtpService
{
    public const EXPIRES_IN_MINUTES = 10;
    public const MAX_ATTEMPTS = 5;

    public function issueFor(User $user): EmailVerificationOtp
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = EmailVerificationOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
                'consumed_at' => null,
            ]
        );

        Mail::to($user->email)->send(new CandidateOtpMail($code, self::EXPIRES_IN_MINUTES));

        return $otp;
    }

    public function verify(User $user, string $code): array
    {
        $otp = EmailVerificationOtp::where('user_id', $user->id)->first();

        if (!$otp) {
            return ['success' => false, 'message' => 'OTP not found. Please request a new code.'];
        }

        if ($otp->consumed_at) {
            return ['success' => false, 'message' => 'OTP has already been used. Please request a new code.'];
        }

        if ($otp->expires_at->isPast()) {
            return ['success' => false, 'message' => 'OTP has expired. Please request a new code.'];
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            return ['success' => false, 'message' => 'OTP attempt limit reached. Please request a new code.'];
        }

        if (!Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            return ['success' => false, 'message' => 'OTP is invalid.'];
        }

        $otp->forceFill([
            'consumed_at' => now(),
        ])->save();

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return ['success' => true];
    }
}
