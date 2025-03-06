<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'lead_id',
        'user_id',
        'title',
        'description',
        'event_type',
        'event_date',
        'is_completed',
        'completion_notes'
    ];
    
    protected $casts = [
        'event_date' => 'datetime',
        'is_completed' => 'boolean'
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
}
