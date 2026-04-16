<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

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
    

    public function getNearbyProviders($lat, $lng, $radius = 10)
{
    return DB::table('users')
        ->selectRaw("
            users.*,
            ( 6371 * acos(
                cos(radians(?)) *
                cos(radians(lat)) *
                cos(radians(lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(lat))
            )) AS distance
        ", [$lat, $lng, $lat])
        ->where('role', 'provider')
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->paginate(10);
}
  
}