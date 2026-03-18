<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'image'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}