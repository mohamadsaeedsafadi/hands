<?php
namespace App\Services;

use App\Repositories\User\UserRepository ;
use Illuminate\Support\Facades\Storage;

class UserService {
    protected $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function updateUserInfo($userId, $data, $avatarFile = null) {
        if ($avatarFile) {
            // رفع الصورة وتخزين المسار
            $data['avatar'] = $avatarFile->store('avatars', 'public');
        }

        return $this->userRepo->updateProfile($userId, $data);
    }
}