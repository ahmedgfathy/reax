<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'title',
        'type',
        'avatar',
        'notes'
    ];

    protected $appends = ['avatar_url'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? Storage::disk('public')->url($this->avatar)
            : null;
    }
}
