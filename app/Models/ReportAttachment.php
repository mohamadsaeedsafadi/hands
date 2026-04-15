<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportAttachment extends Model
{
    protected $fillable = [
        'report_id',
        'file_path'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
