<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'source',
        'property_interest',
        'budget',
        'notes',
        'assigned_to'
    ];

    /**
     * Get the user that the lead is assigned to
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    /**
     * Get the property that the lead is interested in
     */
    public function interestedProperty()
    {
        return $this->belongsTo(Property::class, 'property_interest');
    }
    
    /**
     * Get the events for the lead
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    /**
     * Get the activity logs for the lead
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->orderBy('created_at', 'desc');
    }
    
    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
