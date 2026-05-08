<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cashier;
use App\Models\User;
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
    public function createCashier(Request $request)
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
            $this->service->createCashier(
                auth('admin_api')->user(),
                $request->only('name','email','password')
            );

            return response()->json([
                'message' => 'cashier created successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
}
public function admins()
{
    return response()->json(
        $this->service->getAdmins(auth('admin_api')->user())
    );
}
public function updateAdmin(Request $request, $id)
{
    $admin = Admin::findOrFail($id);

    $data = $request->validate([
        'name' => 'sometimes|string|min:2|max:30',
        'email' => 'sometimes|email|unique:admins,email,' . $admin->id,
        'password' => 'nullable|min:8'
    ]);

    return response()->json(
        $this->service->updateAdmin(
            auth('admin_api')->user(),
            $admin,
            $data
        )
    );
}
public function deleteAdmin($id)
{
    $admin = Admin::findOrFail($id);

    $this->service->deleteAdmin(
        auth('admin_api')->user(),
        $admin
    );

    return response()->json([
        'message' => 'تم حذف الأدمن'
    ]);
}
public function cashiers()
{
    return response()->json(
        $this->service->getCashiers(auth('admin_api')->user())
    );
}
public function updateCashier(Request $request, $id)
{
    $cashier = Cashier::findOrFail($id);

    $data = $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|email|unique:cashiers,email,' . $cashier->id,
        'password' => 'nullable|min:8'
    ]);

    return response()->json(
        $this->service->updateCashier(
            auth('admin_api')->user(),
            $cashier,
            $data
        )
    );
}
public function deleteCashier($id)
{
    $cashier = Cashier::findOrFail($id);

    $this->service->deleteCashier(
        auth('admin_api')->user(),
        $cashier
    );

    return response()->json([
        'message' => 'تم حذف الكاشير'
    ]);
}
}