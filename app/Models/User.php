<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'address',
        'avatar',
        'team_id',
        'is_active'  // Add this line
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',  // Add this line
        ];
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
