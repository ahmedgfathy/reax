<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    // Replace $guarded with $fillable for better security
    protected $fillable = [
        'company_id',
        'property_name',
        'compound_name',
        'property_number',
        'unit_no',
        'unit_for',
        'type',
        'phase',
        'building',
        'floor',
        'finished',
        // ...all other fields from migration...
    ];

    protected $casts = [
        'finished' => 'boolean',
        'amenities' => 'array',
        'features' => 'array',
        'rent_from' => 'date',
        'rent_to' => 'date',
        'last_follow_up' => 'datetime',
        'is_featured' => 'boolean',
        'total_area' => 'decimal:2',
        'unit_area' => 'decimal:2',
        'land_area' => 'decimal:2',
        'garden_area' => 'decimal:2',
        'space_earth' => 'decimal:2',
        'total_price' => 'decimal:2',
        'price_per_meter' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForSale($query)
    {
        return $query->where('unit_for', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('unit_for', 'rent');
    }
}
