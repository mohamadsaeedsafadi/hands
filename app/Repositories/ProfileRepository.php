<?php
namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
     public function updateOrCreate($userId, $data)
    {
        return Profile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    public function getByUserId($userId)
    {
        return Profile::where('user_id', $userId)->first();
    }
}