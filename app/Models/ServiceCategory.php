<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id');
    }

    public function questions()
    {
        return $this->hasMany(ServiceQuestion::class, 'category_id');
    }
    public function providers()
{
    return $this->belongsToMany(
        User::class,
        'provider_categories',
        'category_id',
        'provider_id'
    );
}
}