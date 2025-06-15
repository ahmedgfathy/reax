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
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('companies.create')
                ->with('error', 'Please create a company first');
        }

        $teams = Team::where('company_id', $company->id)
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        return view('teams.index', compact('teams', 'company'));
    }

    public function create()
    {
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $departments = Department::where('company_id', auth()->user()->company_id)->get();
        return view('teams.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:teams',
            'leader_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'can_share_externally' => 'boolean',
            'public_listing_allowed' => 'boolean'
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        Team::create($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully');
    }

    public function show(Team $team)
    {
        // Authorization check: Super admin can view all teams, others can only view their company's teams
        if (!auth()->user()->isSuperAdmin() && $team->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to this team.');
        }

        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        // Authorization check: Super admin can edit all teams, others can only edit their company's teams
        if (!auth()->user()->isSuperAdmin() && $team->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to edit this team.');
        }

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

    public function assignMembersForm(Team $team)
    {
        $users = User::whereNotIn('id', $team->members->pluck('id'))->get();
        return view('teams.assign-members', compact('team', 'users'));
    }

    public function assignMembers(Request $request, Team $team)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $team->members()->attach($validated['user_ids']);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team members assigned successfully.');
    }
}
