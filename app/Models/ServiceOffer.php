<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Traits\AuditTrait;

class ServiceOffer extends Model
{
    protected $fillable = [
        'service_request_id',
        'provider_id',
        'min_price',
        'max_price',
        'final_price',
        'status', 
        'message'
    ];
 use AuditTrait;
   public function serviceRequest()
{
    return $this->belongsTo(ServiceRequest::class);
}

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}