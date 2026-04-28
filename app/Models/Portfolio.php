<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
class Portfolio extends Model
{
    protected $fillable = ['user_id', 'title', 'description'];

    public function images()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}