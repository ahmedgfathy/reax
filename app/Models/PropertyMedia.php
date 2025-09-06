<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'type',
        'file_path',
        'appwrite_file_id',
        'thumbnail_path',
        'title',
        'description',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->file_path) {
            if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
                return $this->file_path;
            }
            return Storage::disk('public')->url($this->file_path);
        }
        
        return null;
    }
}
