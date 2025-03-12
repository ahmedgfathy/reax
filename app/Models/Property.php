<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
        'total_area',
        'unit_area',
        'land_area',
        'garden_area',
        'space_earth',
        'rooms',
        'bathrooms',
        'amenities',
        'location_type',
        'category',
        'status',
        'total_price',
        'price_per_meter',
        'currency',
        'rent_from',
        'rent_to',
        'property_offered_by',
        'owner_name',
        'owner_mobile',
        'owner_tel',
        'contact_status',
        'handler_id',
        'sales_person_id',
        'sales_category',
        'sales_notes',
        'project_id',
        'description',
        'features',
        'last_follow_up',
        'is_featured'
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
