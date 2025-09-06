<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'is_active',
        'owner_id'  // Add company owner/CEO
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function administrators()
    {
        return $this->hasMany(User::class)->where('is_company_admin', true);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
