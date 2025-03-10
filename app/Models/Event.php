<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Event extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'lead_id',
        'user_id',
        'title',
        'description',
        'event_type',
        'event_date',
        'start_date',
        'end_date',
        'status',
        'is_completed',
        'is_cancelled',
        'completion_notes',
        'company_id'
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'is_cancelled' => 'boolean',
        'event_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];
    
    /**
     * Default attribute values.
     */
    protected $attributes = [
        'status' => 'pending',
        'is_completed' => false,
        'is_cancelled' => false
    ];
    
    /**
     * Get the lead for this event
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    
    /**
     * Get the user that created the event
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the company this event belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
