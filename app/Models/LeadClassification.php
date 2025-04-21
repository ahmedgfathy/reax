<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadClassification extends Model
{
    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'priority',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
