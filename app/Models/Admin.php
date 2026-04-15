<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'failed_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'admin_api',
            'role'  => $this->role,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}