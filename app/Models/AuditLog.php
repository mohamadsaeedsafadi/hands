<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'before',
        'after',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array'
    ];
}