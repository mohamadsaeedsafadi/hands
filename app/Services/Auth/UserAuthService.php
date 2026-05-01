<?php

namespace App\Services\Auth;

use App\Mail\SecurityAlertMail;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserAuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}


    public function login(array $credentials): array
{
    $user = $this->userRepository->findByEmail($credentials['email']);

    if (!$user) {
        throw new \Exception('Invalid credentials');
    }

   
    if ($user->locked_until && now()->lessThan($user->locked_until)) {
        throw new \Exception('Account locked temporarily');
    }

    if (!Hash::check($credentials['password'], $user->password)) {

        $user->increment('failed_attempts');

        if ($user->failed_attempts >= 5) {
            $user->update([
                'failed_attempts' => 0,
                'locked_until' => now()->addMinutes(15),
            ]);

            Mail::to($user->email)->send(new SecurityAlertMail());

            throw new \Exception('Account locked due to multiple failed attempts');
        }

        throw new \Exception('Invalid credentials');
    }

    if (!$user->email_verified_at) {
        throw new \Exception('Email not verified');
    }

   
    $user->update([
        'failed_attempts' => 0,
        'locked_until' => null,
    ]);

    auth()->shouldUse('user_api');

    $token = JWTAuth::fromUser($user);
    $mustChangePassword = false;

if ($user->password_changed_at) {
    $mustChangePassword = $user->password_changed_at->diffInDays(now()) >= 90;
    
}

$userrole = $user->role;
      return [
    'token' => $this->respondWithToken($token),
    'role' => $user->role,
    'must_change_password' => $mustChangePassword
];
}

  public function refresh(): array
{
    auth()->shouldUse('user_api');

    try {
        $newToken = auth('user_api')->refresh();
    } catch (\Exception $e) {
        throw new \Exception('Refresh token expired');
    }

        return [$this->respondWithToken($newToken)];
}

    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') ,
        ];
    }
}