<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    public function __construct(protected ProfileRepository $repo) {}

    public function getMyProfile($user)
    {
        return $this->repo->getByUser($user->id);
    }

    public function updateProfile($user, $data)
    {
        $profile = $this->repo->getByUser($user->id);

        
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('profiles', 'public');
        }

        return $this->repo->update($profile, $data);
    }
}