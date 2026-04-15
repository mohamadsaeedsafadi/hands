<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'service_request_id',
        'user_id',
        'provider_id',
        'status'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function provider()
{
    return $this->belongsTo(User::class, 'provider_id');
}
}
