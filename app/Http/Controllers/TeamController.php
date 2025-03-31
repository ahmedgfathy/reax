<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::where('company_id', auth()->user()->company_id)
                    ->latest()
                    ->paginate(10);
                    
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Team::create($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully');
    }

    public function show(Team $team)
    {
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $departments = Department::where('company_id', auth()->user()->company_id)->get();
        return view('teams.edit', compact('team', 'users', 'departments'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:teams,code,' . $team->id,
            'leader_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'can_share_externally' => 'boolean',
            'public_listing_allowed' => 'boolean'
        ]);

        $team->update($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Team updated successfully');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully');
    }
}
