<?php

namespace App\Services\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthService
{
    public function login(array $credentials): array
    {
        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            throw new \Exception('Invalid credentials');
        }

        if ($admin->locked_until && now()->lessThan($admin->locked_until)) {
            throw new \Exception('Account locked');
        }

        if (!Hash::check($credentials['password'], $admin->password)) {

            $admin->increment('failed_attempts');

            if ($admin->failed_attempts >= 5) {
                $admin->update([
                    'failed_attempts' => 0,
                    'locked_until' => now()->addMinutes(15),
                ]);
                throw new \Exception('Account locked due to failed attempts');
            }

            throw new \Exception('Invalid credentials');
        }

        $admin->update([
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);

        auth()->shouldUse('admin_api');
        $token = JWTAuth::fromUser($admin);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') ,
            'role'=>$admin->role
        ];
    }
}