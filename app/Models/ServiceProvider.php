<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ServiceProvider extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    protected $table = 'service_providers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'is_verified',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'provider_api',
        ];
    }
  
}