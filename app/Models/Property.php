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
        'appwrite_id',
        'company_id',
        'territory_id',
        'project_id',
        'team_id',
        'handler_id',
        'sales_person_id',
        'property_name',
        'compound_name',
        'unit_no',
        'unit_for',
        'type',
        'phase',
        'building',
        'floor',
        'category',
        'finished',
        'location_type',
        'total_area',
        'unit_area',
        'building_area',
        'garden_area',
        'land_area',
        'built_area',
        'space_earth',
        'space_unit',
        'space_guard',
        'location',
        'rooms',
        'bathrooms',
        'features',
        'amenities',
        'total_price',
        'price_per_meter',
        'currency',
        'rent_from',
        'rent_to',
        'owner_name',
        'owner_phone',
        'owner_mobile',
        'owner_email',
        'owner_contact_status',
        'owner_address',
        'owner_notes',
        'owner_contact_status',
        'sales_category',
        'sales_notes',
        'commission_percentage',
        'commission_amount',
        'deal_status',
        'deal_type',
        'deal_value',
        'title',
        'description',
        'meta_title',
        'meta_description',
        'keywords',
        'slug',
        'is_published',
        'is_featured',
        'is_shared',
        'sharing_settings',
        'visibility_level',
        'access_restrictions',
        'sharing_expiry',
        'status',
        'last_follow_up',
        'next_follow_up',
        'priority_level',
        'source',
        'reference_number'
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'finished' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'is_shared' => 'boolean',
        'sharing_settings' => 'array',
        'total_area' => 'decimal:2',
        'unit_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'garden_area' => 'decimal:2',
        'land_area' => 'decimal:2',
        'built_area' => 'decimal:2',
        'space_earth' => 'decimal:2',
        'space_unit' => 'decimal:2',
        'space_guard' => 'decimal:2',
        'total_price' => 'decimal:2',
        'price_per_meter' => 'decimal:2',
        'rent_from' => 'date',
        'rent_to' => 'date',
        'last_follow_up' => 'datetime',
        'owner_notes' => 'array',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'deal_value' => 'decimal:2',
        'sharing_expiry' => 'datetime',
        'next_follow_up' => 'datetime',
        'access_restrictions' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            $property->property_number = static::generatePropertyNumber();
        });
    }

    protected static function generatePropertyNumber()
    {
        $prefix = 'PRO';
        $lastProperty = static::orderBy('property_number', 'desc')
            ->where('property_number', 'like', $prefix . '%')
            ->first();

        if (!$lastProperty) {
            return $prefix . '10000000';
        }

        $lastNumber = (int) substr($lastProperty->property_number, 3);
        $newNumber = $lastNumber + 1;

        if ($newNumber > 99999999) {
            throw new \Exception('Property number limit reached');
        }

        return $prefix . $newNumber;
    }

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedAgents()
    {
        return $this->belongsToMany(User::class, 'property_agent', 'property_id', 'user_id')
            ->withPivot('role', 'commission_rate')
            ->withTimestamps();
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
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

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeSharedWith($query, $companyId)
    {
        return $query->where(function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
                ->orWhere('is_shared', true);
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByPriority($query, $level)
    {
        return $query->where('priority_level', $level);
    }

    // Add property status management
    public function markAsSold()
    {
        $this->update([
            'status' => 'sold',
            'sold_at' => now(),
        ]);
    }

    public function markAsRented()
    {
        $this->update([
            'status' => 'rented',
            'rented_at' => now(),
        ]);
    }

    // Helper methods
    public function getFullAddressAttribute()
    {
        return collect([
            $this->unit_no,
            $this->building,
            $this->compound_name,
            $this->phase
        ])->filter()->implode(', ');
    }

    public function getDealStatusBadgeAttribute()
    {
        return match($this->deal_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function isAvailableFor($action)
    {
        return $this->status === 'available' && 
               ($action === 'sale' ? $this->unit_for !== 'rent' : $this->unit_for !== 'sale');
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'available' => 'green',
            'sold' => 'blue',
            'rented' => 'purple',
            'reserved' => 'yellow',
            default => 'gray'
        };
    }

    // Accessor methods for backward compatibility with views
    public function getNameAttribute()
    {
        return $this->property_name;
    }

    public function getPriceAttribute()
    {
        return $this->total_price;
    }

    public function getImageUrlAttribute()
    {
        return $this->getFeaturedImageUrlAttribute();
    }

    public function getFeaturedImageUrlAttribute()
    {
        $featuredMedia = $this->media->where('is_featured', true)->first();
        
        if ($featuredMedia && $featuredMedia->file_path) {
            if (filter_var($featuredMedia->file_path, FILTER_VALIDATE_URL)) {
                return $featuredMedia->file_path;
            }
            return Storage::disk('public')->url($featuredMedia->file_path);
        }
        
        return 'https://source.unsplash.com/800x600/?property=' . $this->id;
    }
}
