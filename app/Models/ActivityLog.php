<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    // Allow all fields to be mass assigned
    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    public static function log($entityId = null, $action, $description, $details = [])
    {
        $data = [
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'details' => $details
        ];
        
        // Check what columns exist in the table
        $columns = Schema::getColumnListing('activity_logs');
        
        // Set the entity ID in the appropriate column
        if (in_array('entity_id', $columns)) {
            $data['entity_id'] = $entityId;
        } elseif (in_array('lead_id', $columns)) {
            $data['lead_id'] = $entityId;
        }
        
        // Set entity_type if the column exists
        if (in_array('entity_type', $columns)) {
            $data['entity_type'] = $entityId ? 'lead' : null;
        }
        
        return self::create($data);
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
