<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TeamPerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'team_id',
        'user_id',
        'period_start',
        'period_end',
        'metric_type',
        'period_type',
        'target_value',
        'actual_value',
        'achievement_percentage',
        'leads_generated',
        'leads_converted',
        'conversion_rate',
        'revenue_generated',
        'deals_closed',
        'average_deal_size',
        'calls_made',
        'emails_sent',
        'meetings_held',
        'properties_shown',
        'follow_ups_completed',
        'working_hours',
        'productive_hours',
        'productivity_score',
        'team_rank',
        'company_rank',
        'points_earned',
        'badges_earned',
        'notes',
        'status'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'target_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'revenue_generated' => 'decimal:2',
        'average_deal_size' => 'decimal:2',
        'productivity_score' => 'decimal:2',
        'badges_earned' => 'array'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForCompany(Builder $query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForTeam(Builder $query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeCurrentPeriod(Builder $query, $periodType = 'monthly')
    {
        $now = Carbon::now();
        
        switch ($periodType) {
            case 'monthly':
                return $query->whereMonth('period_start', $now->month)
                            ->whereYear('period_start', $now->year);
            case 'quarterly':
                return $query->whereBetween('period_start', [
                    $now->startOfQuarter()->toDateString(),
                    $now->endOfQuarter()->toDateString()
                ]);
            default:
                return $query->whereMonth('period_start', $now->month);
        }
    }

    // Helper Methods
    public function calculateAchievementPercentage()
    {
        if ($this->target_value == 0) {
            return 0;
        }
        
        $percentage = ($this->actual_value / $this->target_value) * 100;
        $this->achievement_percentage = round($percentage, 2);
        return $this->achievement_percentage;
    }

    public function getPerformanceGrade()
    {
        $percentage = $this->achievement_percentage;
        
        if ($percentage >= 95) return 'A+';
        if ($percentage >= 90) return 'A';
        if ($percentage >= 85) return 'B+';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 75) return 'C+';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
}
