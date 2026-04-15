<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    protected $fillable = [
        'user_id',
        'id_document',
        'work_document',
        'status',
        'admin_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}