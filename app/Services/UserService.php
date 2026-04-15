<?php
namespace App\Services;

use App\Repositories\User\UserRepository ;
use Illuminate\Support\Facades\Storage;

class UserService {
    protected $userRepo;
    

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }
public function nearbyProviders($user, $radius = 10)
{
    if (!$user->lat || !$user->lng) {
        throw new \Exception("User location not set");
    }

    return $this->userRepo->getNearbyProviders(
        $user->lat,
        $user->lng,
        $radius
    );
}
   
}