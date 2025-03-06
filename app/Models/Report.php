<?php

namespace App\Models;

use App\Policies\ReportPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'created_by', 'data_source',
        'filters', 'columns', 'visualization', 'is_public', 'access_level'
    ];

    protected $casts = [
        'filters' => 'json',
        'columns' => 'json',
        'visualization' => 'json',
        'is_public' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shares()
    {
        return $this->hasMany(ReportShare::class);
    }

    public function schedules()
    {
        return $this->hasMany(ReportSchedule::class);
    }

    // Generate a slug when creating a new report
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $report->slug = \Str::slug($report->name);
            
            // Ensure slug is unique
            $count = static::where('slug', $report->slug)->count();
            if ($count > 0) {
                $report->slug = $report->slug . '-' . ($count + 1);
            }
        });
    }

    /**
     * The policy mappings for the model.
     *
     * @var array
     */
    protected static function booted()
    {
        parent::boot();
        
        static::addGlobalScope('policy', function ($query) {
            return $query;
        });
    }

    // Scope for reports the user can access
    public function scopeAccessibleBy($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('created_by', $user->id)
              ->orWhere('is_public', true)
              ->orWhere(function($subq) use ($user) {
                 $subq->where('access_level', 'team')
                      ->whereHas('creator', function($creatorQuery) use ($user) {
                          // Remove the reference to team_id
                          // $creatorQuery->where('team_id', $user->team_id);
                      });
              });
        });
    }
}
