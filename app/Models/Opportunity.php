<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'company_id',
        'lead_id',
        'property_id',
        'assigned_to',
        'status',
        'value',
        'probability',
        'expected_close_date',
        'description',
        'notes',
        'source',
        'stage',
        'type',
        'priority',
        'last_activity_at',
        'last_modified_by'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'decimal:2',
        'expected_close_date' => 'date',
        'last_activity_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    // Add company relationship
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
