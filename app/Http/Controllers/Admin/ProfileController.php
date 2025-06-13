<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the profiles.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Only admin and managers can view profiles
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        // Super admin sees all profiles, others see only their company's profiles
        $profilesQuery = Profile::with(['permissions']);
        if (!$user->isSuperAdmin()) {
            // Regular admins would filter by company if that column existed
            // For now, show all profiles since table doesn't have company_id
        }
        $profiles = $profilesQuery->paginate(10);

        return view('administration.profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only admin and managers can create profiles
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $permissions = Permission::orderBy('module')
            ->orderBy('name');
        
        // Super admin sees all permissions, others see only their company's permissions
        if (!$user->isSuperAdmin()) {
            $permissions->where('company_id', $user->company_id);
        }
        
        $permissions = $permissions->get()->groupBy('module');

        $levels = [
            Profile::LEVEL_ADMINISTRATION => 'Administration',
            Profile::LEVEL_MANAGER => 'Manager',
            Profile::LEVEL_TEAM_LEADER => 'Team Leader',
            Profile::LEVEL_EMPLOYEE => 'Employee'
        ];

        return view('administration.profiles.create', compact('permissions', 'levels'));
    }

    /**
     * Store a newly created profile in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:profiles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|in:administration,manager,team_leader,employee',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'privileges' => 'array',
            'authentication_settings' => 'array',
            'is_active' => 'boolean'
        ]);

        // Managers cannot create administration level profiles
        if (!$user->isAdmin() && $validated['level'] === Profile::LEVEL_ADMINISTRATION) {
            return back()->withErrors(['level' => 'Only administrators can create administration level profiles.']);
        }

        $profile = Profile::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'privileges' => $validated['privileges'] ?? [],
            'authentication_settings' => $validated['authentication_settings'] ?? [],
            'is_active' => $validated['is_active'] ?? true
        ]);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $profile->permissions()->attach($validated['permissions']);
        }

        return redirect()->route('administration.profiles.index')
            ->with('success', 'Profile created successfully.');
    }

    /**
     * Display the specified profile.
     */
    public function show(Profile $profile)
    {
        $user = Auth::user();
        
        // Super admin can see all profiles, others would check company_id if it existed
        if (!$user->isSuperAdmin()) {
            // For now, allow all since table doesn't have company_id
        }

        $profile->load(['permissions', 'users']);

        return view('administration.profiles.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(Profile $profile)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        // Super admin can edit all profiles, others would check company_id if it existed
        if (!$user->isSuperAdmin()) {
            // For now, allow all since table doesn't have company_id
        }

        $permissions = Permission::orderBy('module')
            ->orderBy('name');
        
        // Super admin sees all permissions, others see only their company's permissions
        if (!$user->isSuperAdmin()) {
            $permissions->where('company_id', $user->company_id);
        }
        
        $permissions = $permissions->get()
            ->groupBy('module');

        $levels = [
            Profile::LEVEL_ADMINISTRATION => 'Administration',
            Profile::LEVEL_MANAGER => 'Manager',
            Profile::LEVEL_TEAM_LEADER => 'Team Leader',
            Profile::LEVEL_EMPLOYEE => 'Employee'
        ];

        $assignedPermissions = $profile->permissions->pluck('id')->toArray();

        return view('administration.profiles.edit', compact('profile', 'permissions', 'levels', 'assignedPermissions'));
    }

    /**
     * Update the specified profile in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        // Super admin can update all profiles, others would check company_id if it existed
        if (!$user->isSuperAdmin()) {
            // For now, allow all since table doesn't have company_id
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:profiles,name,' . $profile->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|in:administration,manager,team_leader,employee',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'privileges' => 'array',
            'authentication_settings' => 'array',
            'is_active' => 'boolean'
        ]);

        // Managers cannot create/edit administration level profiles
        if (!$user->isAdmin() && $validated['level'] === Profile::LEVEL_ADMINISTRATION) {
            return back()->withErrors(['level' => 'Only administrators can manage administration level profiles.']);
        }

        $profile->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'privileges' => $validated['privileges'] ?? [],
            'authentication_settings' => $validated['authentication_settings'] ?? [],
            'is_active' => $validated['is_active'] ?? true
        ]);

        // Update permissions
        $profile->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('administration.profiles.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified profile from storage.
     */
    public function destroy(Profile $profile)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        // Super admin can delete all profiles, others would check company_id if it existed
        if (!$user->isSuperAdmin()) {
            // For now, allow all since table doesn't have company_id
        }

        // Check if profile is in use
        if ($profile->users()->count() > 0) {
            return back()->withErrors(['delete' => 'Cannot delete profile that is assigned to users.']);
        }

        $profile->delete();

        return redirect()->route('administration.profiles.index')
            ->with('success', 'Profile deleted successfully.');
    }

    /**
     * Assign profile to user
     */
    public function assignToUser(Request $request, Profile $profile)
    {
        $user = Auth::user();
        
        if (!$user->canManage(User::find($request->user_id))) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $targetUser = User::find($validated['user_id']);
        $targetUser->update(['profile_id' => $profile->id]);

        return back()->with('success', 'Profile assigned to user successfully.');
    }
}
