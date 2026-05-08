<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Cashier extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'password',
     
    ];

    protected $hidden = [
        'password'
    ];

  
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'cashier_api'
        ];
    }

    public function withdrawals()
    {
        return $this->hasMany(
            WithdrawalRequest::class,
            'cashier_id'
        );
    }
}