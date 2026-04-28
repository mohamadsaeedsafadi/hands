<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Cache;

class ProfileService
{
    protected $repo;

    public function __construct(ProfileRepository $repo)
    {
        $this->repo = $repo;
    }

    private function cacheKey($userId)
    {
        return "profile_user_{$userId}";
    }

    public function getProfile($userId)
    {
        return Cache::remember($this->cacheKey($userId), 3600, function () use ($userId) {
            return $this->repo->getByUserId($userId);
        });
    }

    public function updateProfile($user, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('profiles', 'public');
        }

        $profile = $this->repo->updateOrCreate($user->id, $data);

        Cache::forget($this->cacheKey($user->id));

        return $profile;
    }
}