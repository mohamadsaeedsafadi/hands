<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reporter_type',
        'reported_user_id',
        'type',
        'description',
        'status'
    ];

    public function reporter()
    {
        return $this->morphTo();
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class);
    }
}