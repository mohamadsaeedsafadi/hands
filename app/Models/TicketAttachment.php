<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'file_path'
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}

