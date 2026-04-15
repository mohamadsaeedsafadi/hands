<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\UserBan;
use App\Services\BanService;
use Illuminate\Http\Request;
class BanController extends Controller
{
    protected $service;

    public function __construct(BanService $service)
    {
        $this->service = $service;
    }

    public function ban(Request $request, $userId)
    {
        $request->validate([
            'reason' => 'nullable|string',
            'banned_until' => 'nullable|date'
        ]);

        $ban = $this->service->banUser(
            auth('admin_api')->user(),
            $userId,
            $request->all()
        );

        return response()->json($ban);
    }

    public function unban($userId)
    {
        $this->service->unbanUser($userId);
        return response()->json(['message' => 'User unbanned']);
    }
 
}