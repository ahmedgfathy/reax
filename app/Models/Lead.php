<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema; // Add this import

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appwrite_id',
        'company_id',
        'assigned_to', // Changed from user_id
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'status',
        'source',
        'lead_source',
        'lead_type',
        'budget',
        'requirements',
        'notes',
        'last_contact',
        'next_follow_up',
        'lead_classification_id' // Add this line
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'budget' => 'decimal:2',
        'last_contact' => 'datetime',
        'next_follow_up' => 'datetime',
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
     * Get the company associated with the lead.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
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
        return $this->morphMany(ActivityLog::class, 'loggable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the property that the lead is interested in.
     */
    public function interestedProperty()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get the classification for this lead.
     */
    public function classification()
    {
        return $this->belongsTo(LeadClassification::class, 'lead_classification_id');
    }

    /**
     * Get the lead's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
