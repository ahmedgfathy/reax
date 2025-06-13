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
        'appwrite_id',
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'manager_id',
        'profile_id',
        'hierarchy_level',
        'phone',
        'mobile',
        'position',
        'address',
        'avatar',
        'is_admin',
        'is_company_admin',
        'is_active',
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
        'is_active' => 'boolean',
    ];
    
    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_TEAM_LEADER = 'team_leader';
    const ROLE_AGENT = 'agent';
    const ROLE_EMPLOYEE = 'employee';
    
    /**
     * Get formatted role name
     */
    public function getRoleNameAttribute()
    {
        return ucfirst($this->role);
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is super admin (full system access)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_admin === 1;
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }

    /**
     * Check if user is agent
     */
    public function isAgent()
    {
        return $this->role === self::ROLE_AGENT;
    }
    
    /**
     * Check if user is team leader
     */
    public function isTeamLeader()
    {
        return $this->role === self::ROLE_TEAM_LEADER;
    }

    /**
     * Check if user is employee
     */
    public function isEmployee()
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }
    
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

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withTimestamps();
    }

    /**
     * HIERARCHICAL RELATIONSHIPS
     */

    /**
     * Get the manager of this user
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get users managed by this user
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Get the profile assigned to this user
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get all users under this user's hierarchy (recursive)
     */
    public function getAllSubordinates()
    {
        $subordinates = collect();
        
        foreach ($this->subordinates as $subordinate) {
            $subordinates->push($subordinate);
            $subordinates = $subordinates->merge($subordinate->getAllSubordinates());
        }
        
        return $subordinates;
    }

    /**
     * Check if this user can manage another user
     */
    public function canManage(User $user)
    {
        // Admin can manage everyone
        if ($this->isAdmin()) {
            return true;
        }

        // Manager can manage team leaders and employees
        if ($this->isManager() && ($user->isTeamLeader() || $user->isEmployee())) {
            return true;
        }

        // Team leader can manage employees
        if ($this->isTeamLeader() && $user->isEmployee()) {
            return true;
        }

        // Check direct management relationship
        return $user->manager_id === $this->id;
    }

    /**
     * Get hierarchy level as integer
     */
    public function getHierarchyLevelAttribute()
    {
        return match($this->role) {
            self::ROLE_ADMIN => 1,
            self::ROLE_MANAGER => 2,
            self::ROLE_TEAM_LEADER => 3,
            self::ROLE_EMPLOYEE => 4,
            default => 4
        };
    }

    /**
     * Check if user has permission through profile or role
     */
    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check through profile
        if ($this->profile && $this->profile->hasPermission($permission)) {
            return true;
        }

        // Check through role
        if ($this->role && $this->role->permissions && $this->role->permissions->contains('slug', $permission)) {
            return true;
        }

        return false;
    }
}
