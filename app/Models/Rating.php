<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'service_offer_id',
        'user_id',
        'provider_id',
        'rating',
        'review'
    ];

    public function offer()
    {
        return $this->belongsTo(ServiceOffer::class,'service_offer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
}