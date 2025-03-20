<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
        'logged_out_at',
        'device_name',
        'location',
        'latitude',
        'longitude',
        'login_time',
        'session_duration'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'last_activity' => 'datetime',
        'logged_out_at' => 'datetime',
        'location' => 'array',
        'login_time' => 'datetime',
        'session_duration' => 'integer'
    ];

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if the session is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return is_null($this->logged_out_at);
    }

    /**
     * Get location details of the session.
     *
     * @return array
     */
    public function getLocationDetails()
    {
        return [
            'ip' => $this->ip_address,
            'location' => $this->location,
            'coordinates' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ]
        ];
    }
}
