<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'service_offer_id',
        'service_request_id',
        'user_id',
        'provider_id',
        'stripe_session_id',
        'amount_syp',
        'amount_usd',
        'status'
    ];
}
