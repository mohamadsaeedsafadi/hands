<?php
namespace App\Services;

use App\Models\Report;
use App\Models\UserBan;
use Illuminate\Support\Facades\Cache;
class BanService
{
   public function banUser($admin, $userId, $data)
{
    $alreadyBanned = UserBan::where('user_id', $userId)
        ->where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('banned_until')
              ->orWhere('banned_until', '>', now());
        })
        ->exists();

    if ($alreadyBanned) {
        throw new \Exception('User already banned');
    }
Cache::forget("admin:banned_users:v1");
    return UserBan::create([
        'user_id' => $userId,
        'admin_id' => $admin->id,
        'reason' => $data['reason'] ?? null,
        'banned_until' => $data['banned_until'] ?? null,
        'is_active' => true
    ]);
}

    public function unbanUser($userId)
    {
        Cache::forget("admin:banned_users:v1");
        UserBan::where('user_id', $userId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return true;
    }

   public function autoBanIfNeeded($userId)
{
    Cache::forget("admin:banned_users:v1");
    $reportsCount = Report::where('reported_user_id', $userId)
        ->where('status', 'resolved')
        ->count();

  
    $alreadyBanned = UserBan::where('user_id', $userId)
        ->where(function ($q) {
            $q->whereNull('banned_until')
              ->orWhere('banned_until', '>', now());
        })
        ->exists();

    if ($reportsCount >= 100 && !$alreadyBanned) {
        UserBan::create([
            'user_id' => $userId,
            'reason' => 'Auto ban بسبب كثرة البلاغات',
            'banned_until' => now()->addDays(7),
            'is_active' => true
        ]);
    }
}
public function getBannedUsers()
{
    $key = "admin:banned_users:v1";

    return Cache::remember($key, 300, function () {

        return \App\Models\User::whereHas('bans', function ($q) {
            $q->where('is_active', true)
              ->where(function ($q2) {
                  $q2->whereNull('banned_until')
                     ->orWhere('banned_until', '>', now());
              });
        })
        ->with(['bans' => function ($q) {
            $q->where('is_active', true)->latest();
        }])
        ->get()
        ->map(function ($user) {

            $ban = $user->bans->first();

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'reason' => $ban->reason,
                'banned_until' => $ban->banned_until,
                'is_permanent' => is_null($ban->banned_until),
                'banned_by_admin' => $ban->admin_id,
                'created_at' => $ban->created_at
            ];
        });
    });
}

}