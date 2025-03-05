<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'share_type', 'recipient', 'subject',
        'message', 'scheduled', 'frequency', 'last_sent_at', 'next_send_at'
    ];

    protected $casts = [
        'scheduled' => 'boolean',
        'last_sent_at' => 'datetime',
        'next_send_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
