<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead_id',
        'action',
        'description',
        'details'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the lead that this activity belongs to
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

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
    public static function log($leadId, $action, $description, $details = [])
    {
        return self::create([
            'user_id' => Auth::id(),
            'lead_id' => $leadId,
            'action' => $action,
            'description' => $description,
            'details' => $details
        ]);
    }
}
