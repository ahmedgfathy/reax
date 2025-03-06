<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // User can view if they created it, it's public, or they're on the same team
        return $user->id === $report->created_by || 
               $report->is_public || 
               ($report->access_level === 'team' && $user->team_id === $report->creator->team_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->id === $report->created_by;
    }

    /**
     * Determine whether the user can share the model.
     */
    public function share(User $user, Report $report): bool
    {
        return $user->id === $report->created_by || 
               ($report->access_level === 'team' && $user->team_id === $report->creator->team_id);
    }
}
