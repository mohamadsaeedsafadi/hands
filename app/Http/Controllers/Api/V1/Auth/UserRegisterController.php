<?php
namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\UserRegisterService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserRegisterController extends Controller
{
    public function __construct(
        private UserRegisterService $registerService
    ) {}

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255|min:3|alpha',
            'email'    => 'required|email|unique:users,email',
                'password' => [
        'required',
        'confirmed',
        Password::min(8)
            ->mixedCase() 
            ->numbers()   
            ->symbols()   
    ],
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