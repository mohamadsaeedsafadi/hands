<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserProfile;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
    public function updateProfile($userId, array $data) {
        return UserProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'phone'  => $data['phone'] ?? null,
                'city'   => $data['city'] ?? null,
                'avatar' => $data['avatar'] ?? null,
            ]
        );
    }

    // جلب بيانات الملف الشخصي مع بيانات المستخدم الأساسية
    public function getProfileByUserId($userId) {
        return UserProfile::where('user_id', $userId)->with('user')->first();
    }
}