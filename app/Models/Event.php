<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'event_date',
        'status',
        'is_completed',
        'is_cancelled',
        'lead_id',
        'user_id'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_completed' => 'boolean',
        'is_cancelled' => 'boolean'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
