<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'team_id',
        'user_id',
        'created_by',
        'territory_id',
        'title',
        'description',
        'type',
        'category',
        'metric_type',
        'target_value',
        'current_value',
        'unit',
        'achievement_percentage',
        'start_date',
        'end_date',
        'period_type',
        'status',
        'priority',
        'progress_percentage',
        'last_updated_date',
        'reward_amount',
        'reward_type',
        'reward_description',
        'auto_update',
        'update_rules',
        'milestone_checkpoints',
        'send_notifications',
        'notification_settings',
        'manager_notes',
        'employee_notes',
        'review_history',
        'next_review_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_updated_date' => 'date',
        'next_review_date' => 'date',
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'reward_amount' => 'decimal:2',
        'auto_update' => 'boolean',
        'send_notifications' => 'boolean',
        'update_rules' => 'array',
        'milestone_checkpoints' => 'array',
        'notification_settings' => 'array',
        'review_history' => 'array'
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTeam(Builder $query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByCategory(Builder $query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeCurrentPeriod(Builder $query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeOverdue(Builder $query)
    {
        return $query->where('end_date', '<', Carbon::now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // Helper Methods
    public function updateProgress($newValue = null)
    {
        if ($newValue !== null) {
            $this->current_value = $newValue;
        }

        // Calculate achievement percentage
        if ($this->target_value > 0) {
            $this->achievement_percentage = ($this->current_value / $this->target_value) * 100;
        }

        // Calculate progress percentage based on time and achievement
        $timeProgress = $this->getTimeProgress();
        $this->progress_percentage = min(100, max(0, ($this->achievement_percentage + $timeProgress) / 2));

        // Update status based on progress
        $this->updateStatus();

        // Check for milestone achievements
        $this->checkMilestones();

        $this->last_updated_date = Carbon::now();
        $this->save();

        return $this;
    }

    public function getTimeProgress()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($now <= $start) {
            return 0;
        }

        if ($now >= $end) {
            return 100;
        }

        $totalDuration = $end->diffInDays($start);
        $elapsed = $now->diffInDays($start);

        return ($elapsed / $totalDuration) * 100;
    }

    public function updateStatus()
    {
        if ($this->achievement_percentage >= 100) {
            $this->status = 'completed';
        } elseif (Carbon::now() > $this->end_date) {
            $this->status = 'overdue';
        } elseif ($this->current_value > 0) {
            $this->status = 'active';
        }
    }

    public function checkMilestones()
    {
        if (!$this->milestone_checkpoints) {
            return;
        }

        foreach ($this->milestone_checkpoints as $milestone) {
            $percentage = $milestone['percentage'] ?? 0;
            if ($this->achievement_percentage >= $percentage && !$milestone['achieved']) {
                // Mark milestone as achieved
                $this->milestone_checkpoints = collect($this->milestone_checkpoints)
                    ->map(function ($item) use ($milestone) {
                        if ($item['percentage'] === $milestone['percentage']) {
                            $item['achieved'] = true;
                            $item['achieved_at'] = Carbon::now()->toISOString();
                        }
                        return $item;
                    })->toArray();

                // Send notification if enabled
                if ($this->send_notifications) {
                    $this->sendMilestoneNotification($milestone);
                }
            }
        }
    }

    public function sendMilestoneNotification($milestone)
    {
        // Implementation for sending milestone notifications
        // This would integrate with the notification system
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'active' => 'blue',
            'completed' => 'green',
            'overdue' => 'red',
            'paused' => 'yellow',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    public function getRemainingDays()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->end_date), false);
    }

    public function isOnTrack()
    {
        $timeProgress = $this->getTimeProgress();
        return $this->achievement_percentage >= ($timeProgress * 0.8); // 80% of expected progress
    }
}
