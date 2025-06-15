<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TeamActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'team_id',
        'user_id',
        'related_user_id',
        'type',
        'title',
        'description',
        'activity_data',
        'related_type',
        'related_id',
        'priority',
        'visibility',
        'is_system_generated',
        'requires_action',
        'participants',
        'mentions',
        'likes_count',
        'comments_count',
        'is_pinned',
        'occurred_at',
        'due_at',
        'completed_at',
        'status',
        'status_history',
        'completion_notes'
    ];

    protected $casts = [
        'activity_data' => 'array',
        'participants' => 'array',
        'mentions' => 'array',
        'status_history' => 'array',
        'is_system_generated' => 'boolean',
        'requires_action' => 'boolean',
        'is_pinned' => 'boolean',
        'occurred_at' => 'datetime',
        'due_at' => 'datetime',
        'completed_at' => 'datetime'
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

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->hasMany(ActivityComment::class, 'activity_id');
    }

    public function likes()
    {
        return $this->hasMany(ActivityLike::class, 'activity_id');
    }

    // Scopes
    public function scopeForTeam(Builder $query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRequiringAction(Builder $query)
    {
        return $query->where('requires_action', true)
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopePinned(Builder $query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeRecent(Builder $query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeByPriority(Builder $query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Helper Methods
    public function markAsCompleted($notes = null)
    {
        $this->status = 'completed';
        $this->completed_at = Carbon::now();
        if ($notes) {
            $this->completion_notes = $notes;
        }
        
        $this->addToStatusHistory('completed', $notes);
        $this->save();
        
        return $this;
    }

    public function addToStatusHistory($newStatus, $notes = null)
    {
        $history = $this->status_history ?? [];
        $history[] = [
            'status' => $newStatus,
            'changed_at' => Carbon::now()->toISOString(),
            'changed_by' => auth()->id(),
            'notes' => $notes
        ];
        
        $this->status_history = $history;
    }

    public function addParticipant($userId)
    {
        $participants = $this->participants ?? [];
        if (!in_array($userId, $participants)) {
            $participants[] = $userId;
            $this->participants = $participants;
            $this->save();
        }
        
        return $this;
    }

    public function addMention($userId)
    {
        $mentions = $this->mentions ?? [];
        if (!in_array($userId, $mentions)) {
            $mentions[] = $userId;
            $this->mentions = $mentions;
            $this->save();
        }
        
        return $this;
    }

    public function toggleLike($userId)
    {
        $like = $this->likes()->where('user_id', $userId)->first();
        
        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
        } else {
            $this->likes()->create(['user_id' => $userId]);
            $this->increment('likes_count');
        }
        
        return $this;
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function isOverdue()
    {
        return $this->due_at && Carbon::now() > $this->due_at && $this->status !== 'completed';
    }

    public function getTimeUntilDue()
    {
        if (!$this->due_at) {
            return null;
        }
        
        return Carbon::now()->diffForHumans(Carbon::parse($this->due_at));
    }

    // Static Methods
    public static function createSystemActivity($teamId, $type, $title, $data = [])
    {
        return self::create([
            'company_id' => auth()->user()->company_id,
            'team_id' => $teamId,
            'user_id' => auth()->id(),
            'type' => $type,
            'title' => $title,
            'activity_data' => $data,
            'is_system_generated' => true,
            'occurred_at' => Carbon::now(),
            'priority' => 'medium',
            'visibility' => 'team',
            'status' => 'completed'
        ]);
    }
}
