<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SecurityAlertMail extends Mailable
{
    public function __construct(
        
    ) {}

    public function build()
    {
        return $this
            ->subject('تنبيه امني')
            ->view('emails.SecurityAlertMail')
            ;
    }
}