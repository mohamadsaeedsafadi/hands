<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Auth;
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
          $x= Auth::user()->id;
        $name= User::where('id',$x)->get('name');
        return [Cache::remember($this->cacheKey($userId), 3600, function () use ($userId) {
            return $this->repo->getByUserId($userId);
        }),'name',$name];
    }

    public function updateProfile($user, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('profiles', 'public');
        }

        $profile = $this->repo->updateOrCreate($user->id, $data);

        Cache::forget($this->cacheKey($user->id));
        $x= Auth::user()->id;
        $name= User::where('id',$x)->get('name');
        return [$profile,'name:',$name];
    }
}