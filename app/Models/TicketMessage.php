<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_type',
        'message'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

  
    public function sender()
    {
        return $this->morphTo();
    }
    public function attachments()
{
    return $this->morphMany(TicketAttachment::class, 'attachable');
}
}