<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'created_by',
        'name',
        'type',
        'filters',
        'columns',
        'description',
        'schedule',
        'last_run_at'
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
        'schedule' => 'array',
        'last_run_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
