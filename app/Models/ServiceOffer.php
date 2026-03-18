<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ServiceRequest;

class ServiceOffer extends Model
{
    protected $fillable = [
        'service_request_id',
        'provider_id',
        'min_price',
        'max_price',
        'final_price',
        'status', // pending, accepted, completed, awaiting_payment, awaiting_user_approval, closed
        'message'
    ];

   public function serviceRequest()
{
    return $this->belongsTo(ServiceRequest::class);
}

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}