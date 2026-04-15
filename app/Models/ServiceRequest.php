<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ServiceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'answers',
        'status'
    ];
    protected $casts = [
        'answers' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offers()
{
    return $this->hasMany(ServiceOffer::class);
}
}