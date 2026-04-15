<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminManagementService
{
    public function createAdmin(Admin $currentAdmin, array $data): void
    {
        if (!$currentAdmin->isSuperAdmin()) {
            throw new \Exception('Unauthorized');
        }

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin'
        ]);
    }
}