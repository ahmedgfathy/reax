<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // Removed Laravel\Sanctum\HasApiTokens because it's not installed

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'role_id',
        'company_id',
        'team_id',
        'is_admin',
        'is_company_admin',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_company_admin' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the avatar URL
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::disk('public')->exists($this->avatar) 
                ? Storage::url($this->avatar) 
                : asset('images/default-avatar.png');
        }
        
        return asset('images/default-avatar.png');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    
    /**
     * Get the properties associated with the user as handler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'handler_id');
    }
    
    /**
     * Get recent activity for this user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentActivity()
    {
        // This method is likely used in the profile page too
        // Return empty collection by default or implement actual logic if needed
        return collect([]);
    }
    
    /**
     * Get reports created by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'created_by');
    }
}
