<?php

namespace App\Models;

use App\Traits\AuditTrait;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'service_request_id',
        'user_id',
        'provider_id'
    ];
 use AuditTrait;
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
