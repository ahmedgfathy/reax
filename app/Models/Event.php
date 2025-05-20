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
        'start_date',
        'end_date',
        'status',
        'location',
        'user_id',
        'lead_id',
        'is_completed',
        'company_id'
    ];

    protected $casts = [
        'start_date' => 'datetime', // Updated from event_date
        'end_date' => 'datetime',
        'attendees' => 'array',
        'is_completed' => 'boolean'  // Add this line
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
