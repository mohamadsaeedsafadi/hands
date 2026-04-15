<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'event_type',
        'severity',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];
}