<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'event_type',
        'action_type',  // Required field
        'module_name',  // Required field
        'loggable_type',
        'loggable_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'device_info',
        'location_info',
        'shared_with',
        'visibility_changed'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'device_info' => 'array',
        'location_info' => 'array',
        'shared_with' => 'array',
        'visibility_changed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    public function session()
    {
        return $this->belongsTo(UserSession::class, 'session_id');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByModule($query, $moduleName)
    {
        return $query->where('module_name', $moduleName);
    }
}
