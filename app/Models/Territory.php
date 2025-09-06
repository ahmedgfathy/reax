<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Territory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'team_id',
        'manager_id',
        'name',
        'code',
        'description',
        'type',
        'geographic_boundaries',
        'postal_codes',
        'cities',
        'regions',
        'latitude',
        'longitude',
        'radius_km',
        'target_demographics',
        'customer_segments',
        'total_leads',
        'active_opportunities',
        'total_revenue',
        'target_revenue',
        'properties_count',
        'assignment_rules',
        'auto_assign_leads',
        'exclusive_territory',
        'priority_level',
        'is_active',
        'effective_from',
        'effective_until',
        'working_hours'
    ];

    protected $casts = [
        'geographic_boundaries' => 'array',
        'postal_codes' => 'array',
        'cities' => 'array',
        'regions' => 'array',
        'target_demographics' => 'array',
        'customer_segments' => 'array',
        'assignment_rules' => 'array',
        'working_hours' => 'array',
        'auto_assign_leads' => 'boolean',
        'exclusive_territory' => 'boolean',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'total_revenue' => 'decimal:2',
        'target_revenue' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'territory_user')
                    ->withTimestamps()
                    ->withPivot('assigned_at', 'role', 'is_primary');
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCompany(Builder $query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper Methods
    public function getRevenueAchievementPercentage()
    {
        if ($this->target_revenue == 0) {
            return 0;
        }
        
        return round(($this->total_revenue / $this->target_revenue) * 100, 2);
    }

    public function isWithinGeographicBounds($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude || !$this->radius_km) {
            return false;
        }

        $distance = $this->calculateDistance($latitude, $longitude, $this->latitude, $this->longitude);
        return $distance <= $this->radius_km;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    public function canAutoAssignLead($leadData)
    {
        if (!$this->auto_assign_leads || !$this->is_active) {
            return false;
        }

        // Check assignment rules
        if ($this->assignment_rules) {
            foreach ($this->assignment_rules as $rule) {
                if (!$this->evaluateRule($rule, $leadData)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function evaluateRule($rule, $leadData)
    {
        // Implementation for rule evaluation
        // This would check various criteria like location, demographics, etc.
        return true;
    }
}
