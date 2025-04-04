<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'mobile',
        'position',
        'address',
        'avatar',
        'is_admin',
        'is_active',
        'company_id',
        'team_id',
        'role_id'
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
        'roles' => 'array', // Add this line
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

    // Update the leads relationship to use assigned_to
    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    // Update relationship name to match leads table
    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
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

    // Add relationships
    public function company()
    {
        return $this->belongsTo(Company::class)->withDefault([
            'name' => 'No Company'
        ]);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault([
            'name' => 'Guest'
        ]);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function managedTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('name', $permission);
    }

    public function isCompanyOwner()
    {
        return $this->company && $this->company->owner_id === $this->id;
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return !empty(array_intersect($this->getRoles(), $roles));
        }
        return in_array($roles, $this->getRoles());
    }

    public function getRoles()
    {
        return $this->roles ?? [];
    }
}
