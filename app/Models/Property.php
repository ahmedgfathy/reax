<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',                // Property Title
        'name',                 // Property Name
        'compound_name',        // Compound Name
        'property_number',      // Property Number
        'unit_number',          // Unit No.
        'unit_for',             // Unit-For (rent/sale)
        'area',                 // Area location
        'rooms',                // Number of rooms
        'phase',                // Phase
        'type',                 // Property type
        'building',             // Building name/number
        'floor',                // Floor number
        'finished',             // Finished status (yes/no/semi)
        'property_props',       // Props of unit
        'location_type',        // Inside/Outside compound
        'price',                // Total Price
        'price_per_meter',      // Price per meter
        'currency',             // Currency
        'project_id',           // Project ID
        'last_follow_up',       // Last Follow-up date
        'category',             // Category
        'status',               // Status
        'rent_from',            // Rent From date
        'rent_to',              // Rent To date
        'land_area',            // Land Area
        'space_earth',          // Space Earth
        'garden_area',          // Garden Area
        'unit_area',            // Unit Area
        'description',          // Description
        'property_offered_by',  // Property offered by
        'owner_name',           // Owner name
        'owner_mobile',         // Mobile number
        'owner_tel',            // Tel number
        'update_calls',         // Update calls
        'handler_id',           // Handler (Agent) ID
        'sales_person_id',      // Sales person ID
        'sales_category',       // Sales category
        'sales_notes',          // Sales notes
        'is_primary',           // Is primary unit flag
        'address',              // Address
        'bathrooms',            // Number of bathrooms
        'features',             // Property features (JSON)
        'is_featured',          // Is featured property
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'array',
        'is_primary' => 'boolean',
        'last_follow_up' => 'datetime',
        'rent_from' => 'datetime',
        'rent_to' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_featured' => 'boolean',
    ];
    
    /**
     * Get the project that this property belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    /**
     * Get the handler (agent) of this property
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id');
    }
    
    /**
     * Get the sales person of this property
     */
    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }
    
    /**
     * Get media files for this property
     */
    public function mediaFiles()
    {
        return $this->hasMany(PropertyMedia::class);
    }

    /**
     * Get the main image URL for the property
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // Try to get a featured image first
        $featuredMedia = $this->mediaFiles->where('is_featured', true)->first();
        
        if ($featuredMedia && Storage::disk('public')->exists($featuredMedia->file_path)) {
            return asset('storage/' . $featuredMedia->file_path);
        }
        
        // If no featured image, get the first image
        $firstMedia = $this->mediaFiles->first();
        if ($firstMedia && Storage::disk('public')->exists($firstMedia->file_path)) {
            return asset('storage/' . $firstMedia->file_path);
        }
        
        // Default placeholder based on property type
        $type = strtolower($this->type ?? 'default');
        if ($type == 'apartment') {
            return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
        } elseif ($type == 'villa') {
            return 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
        } elseif ($type == 'office') {
            return 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
        }
        
        // Default fallback
        return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
    }
    
    /**
     * Get the leads interested in this property.
     */
    public function interestedLeads()
    {
        return $this->hasMany(Lead::class, 'property_interest');
    }
}
