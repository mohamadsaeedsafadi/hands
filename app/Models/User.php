<?php

namespace App\Models;

use App\Traits\AuditTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'provider_verified_at',
        'email_verification_code',
        'email_verified_at',
        'failed_attempts',
        'locked_until',
        'rating_avg',
        'ratings_count',
        'lat',
        'lng'

    ];
    protected $hidden = [
        'password',
        'email_verification_code',
    ];

    protected $casts = [
        'email_verified_at'     => 'datetime',
        'provider_verified_at'  => 'datetime',
        'locked_until'          => 'datetime',
    ];

    /* ================= JWT ================= */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'guard' => 'user_api',
        ];
    }

    /* ================= Helpers ================= */

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isProviderVerified(): bool
    {
        return !is_null($this->provider_verified_at);
    }
    public function offers()
{
    return $this->hasMany(ServiceOffer::class, 'provider_id');
}
public function categories()
{
    return $this->belongsToMany(
        ServiceCategory::class,
        'provider_categories',
        'provider_id',
        'category_id'
    );
}
public function ratings()
{
    return $this->hasMany(Rating::class,'provider_id');
}

public function averageRating()
{
    return $this->ratings()->avg('rating');
}

public function verificationRequests()
{
    return $this->hasMany(VerificationRequest::class);
}

public function tickets()
{
    return $this->hasMany(Ticket::class);
}
public function bans()
{
    return $this->hasMany(UserBan::class);
}

public function isBanned()
{
    // فك الحظر المنتهي تلقائياً
    $this->bans()
        ->where('is_active', true)
        ->whereNotNull('banned_until')
        ->where('banned_until', '<=', now())
        ->update(['is_active' => false]);

    // تحقق من وجود حظر فعال
    return $this->bans()
        ->where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('banned_until')
              ->orWhere('banned_until', '>', now());
        })
        ->exists();
}
public function profile()
{
    return $this->hasOne(Profile::class);
}

public function portfolios()
{
    return $this->hasMany(Portfolio::class);
}
}