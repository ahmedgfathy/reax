<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'status',
        'price',
        'description',
        'location',
        'bedrooms',
        'bathrooms',
        'area',
        'is_featured'
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
        'is_published' => 'boolean',
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
        if (class_exists('\App\Models\PropertyMedia')) {
            return $this->hasMany(\App\Models\PropertyMedia::class);
        }
        
        return null;
    }

    /**
     * Get the main image URL for the property
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // Try to get image from property_media relationship if it exists
        if (method_exists($this, 'mediaFiles') && $this->mediaFiles && $this->mediaFiles->count() > 0) {
            $featuredMedia = $this->mediaFiles->where('is_featured', true)->first();
            if ($featuredMedia) {
                return asset('storage/' . $featuredMedia->file_path);
            } else {
                // If no featured image, use the first one
                return asset('storage/' . $this->mediaFiles->first()->file_path);
            }
        }

        // If there's a direct image field
        if ($this->image) {
            return asset('storage/' . $this->image);
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
