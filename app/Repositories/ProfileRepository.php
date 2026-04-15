<?php
namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    public function getByUser($userId)
    {
        return Profile::where('user_id', $userId)->first();
    }

    public function update(Profile $profile, $data)
    {
        $profile->update($data);
        return $profile;
    }
}