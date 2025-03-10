<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'entity',
        'entity_type',
        'entity_id',
        'details',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'details' => 'json',
    ];

    /**
     * Get the user who performed the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method to create a new activity log
     */
    public static function log($entityId, $action, $description, $details = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'entity_id' => $entityId,
            'entity_type' => 'lead', // Default to 'lead' for backward compatibility
            'entity' => 'lead', // Add default entity value
            'action' => $action,
            'description' => $description,
            'details' => $details ? json_encode($details) : null,
        ]);
    }

    /**
     * Get the related entity (polymorphic relationship)
     */
    public function entity()
    {
        // First determine what columns we have
        $columns = Schema::getColumnListing('activity_logs');
        
        if (in_array('entity_id', $columns) && in_array('entity_type', $columns)) {
            // Modern structure with entity_id and entity_type
            if ($this->entity_type === 'lead') {
                return $this->belongsTo(Lead::class, 'entity_id');
            }
        } elseif (in_array('lead_id', $columns)) {
            // Old structure with just lead_id
            return $this->belongsTo(Lead::class, 'lead_id');
        }
        
        return null;
    }
}
