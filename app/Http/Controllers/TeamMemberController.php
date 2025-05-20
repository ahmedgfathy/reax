<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function assignForm(Team $team)
    {
        $users = User::whereDoesntHave('teams', function ($query) use ($team) {
            $query->where('team_id', $team->id);
        })->get();
        
        return view('teams.assign-members', compact('team', 'users'));
    }

    public function store(Request $request, Team $team)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $team->members()->attach($validated['user_ids']);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team members assigned successfully');
    }
}
