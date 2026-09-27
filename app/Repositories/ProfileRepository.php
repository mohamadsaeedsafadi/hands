<?php
namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;

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
        return [Profile::where('user_id', $userId)->first(),User::where('id',$userId)->get(['provider_verified_at','rating_avg'])];
    }
}