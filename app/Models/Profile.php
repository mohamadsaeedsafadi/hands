<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'image',
        'city',
        'location',
        'bio',
        'extra',
        'phone',
        'name'
    ];

    protected $casts = [
        'skills' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}