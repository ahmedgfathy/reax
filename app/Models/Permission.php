<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'field_name',
        'actions', // create, read, update, delete
        'company_id',
        'role_id'
    ];

    protected $casts = [
        'actions' => 'array'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')
            ->withTimestamps();
    }

    /**
     * Get profiles that have this permission
     */
    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'profile_permission')
            ->withTimestamps();
    }
}
