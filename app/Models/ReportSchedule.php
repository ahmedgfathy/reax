<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'name', 'frequency', 'recipients', 'time',
        'days_of_week', 'day_of_month', 'is_active', 'last_run_at'
    ];

    protected $casts = [
        'recipients' => 'json',
        'days_of_week' => 'json',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
