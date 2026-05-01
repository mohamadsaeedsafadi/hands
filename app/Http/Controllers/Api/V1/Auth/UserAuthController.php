<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\UserAuthService;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function __construct(
        private UserAuthService $authService
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        try {
            return response()->json(
                $this->authService->login($request->only('email', 'password'))
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

  public function refresh()
{
    try {
        return response()->json(
            $this->authService->refresh()
        );
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 401);
    }
}
  public function verifyEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
         'code'  => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)
        ->where('email_verification_code', $request->code)
        ->first();

    if (!$user) {
        return response()->json(['message' => 'رمز غير صحيح'], 422);
    }

    $user->update([
        'email_verified_at' => now(),
        'email_verification_code' => null,
    ]);

    return response()->json(['message' => 'تم توثيق الحساب بنجاح']);
}
}