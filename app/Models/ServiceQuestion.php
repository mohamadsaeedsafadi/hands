<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceQuestion extends Model
{
    protected $fillable = [
        'category_id',
        'question',
        'type',
        'options',
        'is_required'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}