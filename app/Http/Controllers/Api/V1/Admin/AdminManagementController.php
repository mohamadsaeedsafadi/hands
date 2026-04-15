<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminManagementService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function __construct(
        private AdminManagementService $service
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'email' => 'required|email|unique:admins,email',
                     'password' => [
        'required',
        'confirmed',
        Password::min(8)
            ->mixedCase() 
            ->numbers()   
            ->symbols()     
    ],
        ]);

        try {
            $this->service->createAdmin(
                auth('admin_api')->user(),
                $request->only('name','email','password')
            );

            return response()->json([
                'message' => 'Admin created successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }
}