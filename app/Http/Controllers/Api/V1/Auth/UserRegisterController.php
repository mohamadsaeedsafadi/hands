<?php
namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\UserRegisterService;
use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    public function __construct(
        private UserRegisterService $registerService
    ) {}

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:user,provider',
        ]);

        $this->registerService->register($request->only(
            'name', 'email', 'password', 'role'
        ));

        return response()->json([
            'message' => 'تم التسجيل بنجاح، يرجى توثيق الإيميل'
        ], 201);
    }
}