<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCodeMail;

class ForgotPasswordService
{
    public function sendResetCode(string $email): void
    {
        $code = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(
            new ResetPasswordCodeMail($code)
        );
    }

    public function resetPassword(string $email, string $code, string $password): void
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$record) {
            throw new \Exception('رمز غير صحيح');
        }

        if (now()->diffInMinutes($record->created_at) > 10) {
            throw new \Exception('انتهت صلاحية الرمز');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($password),
            'failed_attempts' => 0,
            'locked_until' => null,
            'password_changed_at' => now(),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}