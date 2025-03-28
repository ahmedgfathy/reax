<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'location',
        'description',
        'developer',
        'status',
        'amenities',
        'features',
        'total_area',
        'total_units',
        'start_date',
        'completion_date'
    ];

    protected $casts = [
        'amenities' => 'array',
        'features' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
        'total_area' => 'decimal:2',
        'total_units' => 'integer',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
