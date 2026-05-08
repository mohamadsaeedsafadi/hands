<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Cashier;
use Illuminate\Support\Facades\Hash;

class AdminManagementService
{
    /*
    |--------------------------------------------------------------------------
    | Admins
    |--------------------------------------------------------------------------
    */

    public function createAdmin(Admin $currentAdmin, array $data): void
    {
        $this->authorize($currentAdmin);

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin'
        ]);
    }

    public function getAdmins(Admin $currentAdmin)
    {
        $this->authorize($currentAdmin);

        return Admin::select('id','name','email','role','created_at')->get();
    }

    public function updateAdmin(Admin $currentAdmin, Admin $admin, array $data)
    {
        $this->authorize($currentAdmin);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);

        return $admin;
    }

    public function deleteAdmin(Admin $currentAdmin, Admin $admin)
    {
        $this->authorize($currentAdmin);

        if ($admin->role === 'super_admin') {
            throw new \Exception('لا يمكن حذف السوبر أدمن');
        }

        $admin->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Cashiers
    |--------------------------------------------------------------------------
    */

    public function getCashiers(Admin $currentAdmin)
    {
        $this->authorize($currentAdmin);

        return Cashier::select('id','name','email','created_at')->get();
    }

    public function updateCashier(Admin $currentAdmin, Cashier $cashier, array $data)
    {
        $this->authorize($currentAdmin);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $cashier->update($data);

        return $cashier;
    }

    public function deleteCashier(Admin $currentAdmin, Cashier $cashier)
    {
        $this->authorize($currentAdmin);

        $cashier->delete();
    }
  public function createCashier(Admin $currentAdmin, array $data)
{
    
$this->authorize($currentAdmin);
  $cashier= Cashier::create([
    'name' => $data['name'],
    'email' => $data['email'],
   'password' => Hash::make($data['password']),
    
]);

}
    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    private function authorize(Admin $admin)
    {
        if (!$admin->isSuperAdmin()) {
            throw new \Exception('Unauthorized');
        }
    }
}