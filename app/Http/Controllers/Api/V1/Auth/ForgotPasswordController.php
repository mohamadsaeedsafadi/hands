<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ForgotPasswordService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

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
            'code'     => 'required|digits:6',
            'password' => [
        'required',
        'confirmed',
        Password::min(8)
            ->mixedCase() 
            ->numbers()   
            ->symbols()   
    ],
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