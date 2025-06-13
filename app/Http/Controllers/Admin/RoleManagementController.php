<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    /**
     * Display the role management dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Only admin can access role management
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can access role management.');
        }

        $roles = Role::with(['users', 'permissions'])->paginate(10);
        
        // Super admin sees all profiles, others see only their company's profiles
        $profilesQuery = Profile::with(['permissions']);
        if (!$user->isSuperAdmin()) {
            // Regular admins would filter by company if that column existed
            // For now, show all profiles since table doesn't have company_id
        }
        $profiles = $profilesQuery->paginate(10);
        
        // Super admin sees all permissions, others see only their company's permissions
        $permissionsQuery = Permission::orderBy('module')->orderBy('name');
        if (!$user->isSuperAdmin()) {
            $permissionsQuery->where('company_id', $user->company_id);
        }
        $permissions = $permissionsQuery->get()->groupBy('module');

        // Super admin sees all users, others see only their company's users
        $usersQuery = User::with(['role', 'profile', 'manager']);
        if (!$user->isSuperAdmin()) {
            $usersQuery->where('company_id', $user->company_id);
        }
        $users = $usersQuery->paginate(15);

        return view('administration.role-management.index', compact('roles', 'profiles', 'permissions', 'users'));
    }

    /**
     * Create or update permissions
     */
    public function storePermission(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string|max:1000',
            'module' => 'required|string|max:100',
            'field_name' => 'nullable|string|max:100',
            'actions' => 'required|array',
            'actions.*' => 'in:create,read,update,delete'
        ]);

        Permission::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'],
            'field_name' => $validated['field_name'] ?? null,
            'actions' => $validated['actions'],
            'company_id' => $user->company_id,
            'role_id' => 1 // Default to admin role
        ]);

        return back()->with('success', 'Permission created successfully.');
    }

    /**
     * Assign user to manager (hierarchy management)
     */
    public function assignToManager(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id'
        ]);

        $targetUser = User::find($validated['user_id']);
        $manager = User::find($validated['manager_id']);

        // Validate hierarchy rules
        if (!$user->canManage($targetUser)) {
            return back()->withErrors(['assignment' => 'You cannot manage this user.']);
        }

        if (!$manager->isManager() && !$manager->isTeamLeader()) {
            return back()->withErrors(['manager' => 'Selected manager must be a Manager or Team Leader.']);
        }

        // Update hierarchy level based on manager's level
        $hierarchyLevel = $manager->hierarchy_level + 1;
        
        $targetUser->update([
            'manager_id' => $validated['manager_id'],
            'hierarchy_level' => $hierarchyLevel
        ]);

        return back()->with('success', 'User assigned to manager successfully.');
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $targetUser)
    {
        $user = Auth::user();
        
        if (!$user->canManage($targetUser)) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,manager,team_leader,agent,employee',
            'profile_id' => 'nullable|exists:profiles,id'
        ]);

        // Validate role assignment rules
        if (!$user->isAdmin() && $validated['role'] === 'admin') {
            return back()->withErrors(['role' => 'Only administrators can assign admin role.']);
        }

        if ($user->isManager() && in_array($validated['role'], ['admin', 'manager'])) {
            return back()->withErrors(['role' => 'Managers cannot assign admin or manager roles.']);
        }

        // Update hierarchy level based on role
        $hierarchyLevel = match($validated['role']) {
            'admin' => 1,
            'manager' => 2,
            'team_leader' => 3,
            'employee', 'agent' => 4,
            default => 4
        };

        $targetUser->update([
            'role' => $validated['role'],
            'profile_id' => $validated['profile_id'],
            'hierarchy_level' => $hierarchyLevel
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Get users that can be managed by current user
     */
    public function getManageableUsers()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $users = User::where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->get();
        } elseif ($user->isManager()) {
            $users = User::where('company_id', $user->company_id)
                ->whereIn('role', ['team_leader', 'employee', 'agent'])
                ->get();
        } elseif ($user->isTeamLeader()) {
            $users = User::where('company_id', $user->company_id)
                ->where('role', 'employee')
                ->get();
        } else {
            $users = collect();
        }

        return response()->json($users);
    }

    /**
     * Bulk assign profiles
     */
    public function bulkAssignProfiles(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'profile_id' => 'required|exists:profiles,id'
        ]);

        $profile = Profile::find($validated['profile_id']);
        $updatedCount = 0;

        foreach ($validated['user_ids'] as $userId) {
            $targetUser = User::find($userId);
            
            if ($user->canManage($targetUser)) {
                $targetUser->update(['profile_id' => $profile->id]);
                $updatedCount++;
            }
        }

        return back()->with('success', "Profile assigned to {$updatedCount} users successfully.");
    }

    /**
     * Show user hierarchy
     */
    public function showHierarchy()
    {
        $user = Auth::user();
        
        // Build hierarchy tree
        $admins = User::where('company_id', $user->company_id)
            ->where('role', 'admin')
            ->with(['subordinates.subordinates.subordinates'])
            ->get();

        return view('administration.role-management.hierarchy', compact('admins'));
    }
}
