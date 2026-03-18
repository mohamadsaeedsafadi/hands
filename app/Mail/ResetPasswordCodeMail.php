<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordCodeMail extends Mailable
{
    public function __construct(
        public int $code
    ) {}

    public function build()
    {
        return $this
            ->subject('رمز إعادة تعيين كلمة المرور')
            ->view('emails.reset-password-code')
            ->with([
                'code' => $this->code
            ]);
    }
}