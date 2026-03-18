<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    
    public function update(Request $request) {
        $request->validate([
            'phone'  => 'nullable|string|max:20',
            'city'   => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userProfile = $this->userService->updateUserInfo(
            Auth::user()->id,
            $request->only(['phone', 'city']),
            $request->file('avatar')
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully',
            'data'    => $userProfile
        ]);
    }
}