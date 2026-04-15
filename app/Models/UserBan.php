<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'reason',
        'banned_until',
        'is_active'
    ];

    protected $casts = [
        'banned_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
  
}
