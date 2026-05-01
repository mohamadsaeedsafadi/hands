<?php
namespace App\Services\Auth;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailCodeMail;

class UserRegisterService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): void
    {
        if ($data['role'] === 'admin') {
            throw new \Exception('Unauthorized role');
        }

        $code = rand(100000, 999999);
 Mail::to($data['email'])->send(
            new VerifyEmailCodeMail($code)
        );
        $this->userRepository->create([
            'name'                     => $data['name'],
            'email'                    => $data['email'],
            'password'                 => Hash::make($data['password']),
            'role'                     => $data['role'],
            'email_verification_code'  => $code,
            'email_verified_at'        => null,
            'failed_attempts'          => 0,
            'locked_until'             => null,
            'password_changed_at' => now(),
        ]);

       
    }
}