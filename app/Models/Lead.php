<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',  // Added
        'status',
        'source',
        'lead_source',  // Added
        'property_interest',
        'budget',
        'notes',
        'assigned_to',
        'last_follow_up',  // Added
        'agent_follow_up',  // Added
        'lead_class',  // Added
        'last_modified_by',  // Added
        'type_of_request',  // Added
        'lead_status',  // Added
        'description',  // Added (assuming 'desc' means 'description')
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_follow_up' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that the lead is assigned to.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    /**
     * Get the user that last modified the lead.
     */
    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    /**
     * Get the property that the lead is interested in.
     */
    public function interestedProperty()
    {
        return $this->belongsTo(Property::class, 'property_interest');
    }

    /**
     * Get the events for this lead.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'lead_id');
    }

    /**
     * Get the activity logs for this lead.
     */
    public function activityLogs()
    {
        // Check which columns exist in the activity_logs table
        $columns = Schema::getColumnListing('activity_logs');
        
        if (in_array('entity_id', $columns) && in_array('entity_type', $columns)) {
            // Modern structure with entity_id and entity_type
            return $this->hasMany(ActivityLog::class, 'entity_id')
                ->where('entity_type', 'lead')
                ->orderBy('created_at', 'desc');
        } elseif (in_array('lead_id', $columns)) {
            // Old structure with just lead_id column
            return $this->hasMany(ActivityLog::class, 'lead_id')
                ->orderBy('created_at', 'desc');
        }
        
        // Fallback to empty collection
        return $this->hasMany(ActivityLog::class, 'entity_id')
            ->whereRaw('1=0'); // Always false condition to return empty set
    }

    /**
     * Get the lead's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
