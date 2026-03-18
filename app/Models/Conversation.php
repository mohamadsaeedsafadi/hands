<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'service_request_id',
        'user_id',
        'provider_id'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
