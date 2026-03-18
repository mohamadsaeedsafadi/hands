<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ForgotPasswordService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private ForgotPasswordService $service
    ) {}

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $this->service->sendResetCode($request->email);

        return response()->json([
            'message' => 'تم إرسال رمز إعادة تعيين كلمة المرور'
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'code'     => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $this->service->resetPassword(
            $request->email,
            $request->code,
            $request->password
        );

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }
}