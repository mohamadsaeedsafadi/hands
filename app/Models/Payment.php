<?php

namespace App\Models;

use App\Traits\AuditTrait;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'service_offer_id',
        'service_request_id',
        'user_id',
        'provider_id',
        'reference',
        'amount_syp',
        'status'
    ];
    public function offer()
{
    return $this->belongsTo(ServiceOffer::class, 'service_offer_id');
}
}
