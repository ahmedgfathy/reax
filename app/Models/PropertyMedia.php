<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'type',
        'file_path',
        'original_name',
        'appwrite_file_id',
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
}
