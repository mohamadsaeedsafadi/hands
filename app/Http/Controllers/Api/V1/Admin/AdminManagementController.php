<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminManagementService;
use Illuminate\Http\Request;

class AdminManagementController extends Controller
{
    public function __construct(
        private AdminManagementService $service
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8'
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