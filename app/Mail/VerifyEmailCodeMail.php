<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $code
    ) {}

    public function build()
    {
        return $this
            ->subject('رمز توثيق الحساب')
            ->view('emails.verify-email-code')
            ->with([
                'code' => $this->code,
            ]);
    }
}