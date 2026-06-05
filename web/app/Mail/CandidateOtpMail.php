<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public int $expiresInMinutes
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Kode Verifikasi Email HIMATIK PNJ')
            ->view('emails.candidate-otp');
    }
}
