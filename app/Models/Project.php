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
        'code',
        'name',
        'description',
        'location',
        'developer',
        'status',
        'launch_date',
        'completion_date',
        'featured_image'
    ];

    protected $casts = [
        'launch_date' => 'date',
        'completion_date' => 'date',
        'amenities' => 'array'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
