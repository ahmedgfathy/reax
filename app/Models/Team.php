<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'leader_id',
        'department_id',
        'can_share_externally',
        'shared_companies',
        'visibility_settings',
        'public_listing_allowed'
    ];

    protected $casts = [
        'can_share_externally' => 'boolean',
        'shared_companies' => 'array',
        'visibility_settings' => 'array',
        'public_listing_allowed' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withTimestamps();
    }

    public function sharedProperties()
    {
        return $this->hasMany(Property::class)->where('is_shared', true);
    }

    public function sharedWithCompanies()
    {
        return $this->belongsToMany(Company::class, 'team_company_shares');
    }

    public function publicListings()
    {
        return $this->hasMany(Property::class)->where('is_public', true);
    }
}
