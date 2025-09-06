<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'level',
        'privileges',
        'authentication_settings',
        'is_active'
    ];

    protected $casts = [
        'privileges' => 'array',
        'authentication_settings' => 'array',
        'is_active' => 'boolean'
    ];

    // Hierarchy levels
    const LEVEL_ADMINISTRATION = 'administration';
    const LEVEL_MANAGER = 'manager';
    const LEVEL_TEAM_LEADER = 'team_leader';
    const LEVEL_EMPLOYEE = 'employee';

    /**
     * Get users assigned to this profile
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get permissions assigned to this profile
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'profile_permission')
            ->withTimestamps();
    }

    /**
     * Check if profile has a specific permission
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('slug', $permission);
        }

        return $this->permissions->contains('id', $permission->id);
    }

    /**
     * Assign permission to profile
     */
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission)) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Remove permission from profile
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }

        return $this;
    }

    /**
     * Get hierarchy level as integer
     */
    public function getHierarchyLevelAttribute()
    {
        return match($this->level) {
            self::LEVEL_ADMINISTRATION => 1,
            self::LEVEL_MANAGER => 2,
            self::LEVEL_TEAM_LEADER => 3,
            self::LEVEL_EMPLOYEE => 4,
            default => 4
        };
    }

    /**
     * Scope to filter by level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to filter active profiles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
