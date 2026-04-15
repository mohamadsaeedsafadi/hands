<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'type',
        'title',
        'description',
        'priority',
        'status',
        'version'
    ];
protected $casts = [
    'version' => 'integer'
];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function attachments()
{
    return $this->morphMany(TicketAttachment::class, 'attachable');
}

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
}