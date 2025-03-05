<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'completion_date' => 'date'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
