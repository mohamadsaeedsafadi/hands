<?php
namespace App\Services;

use App\Models\Report;
use App\Models\UserBan;

class BanService
{
    public function banUser($admin, $userId, $data)
    {
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
        UserBan::where('user_id', $userId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return true;
    }

   public function autoBanIfNeeded($userId)
{
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
}