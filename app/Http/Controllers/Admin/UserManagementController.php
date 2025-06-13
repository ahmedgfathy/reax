<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users (for super admin) or company users (for regular admin)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only admin can access user management
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can access user management.');
        }

        $query = User::with(['profile', 'role', 'manager']);

        // Super admin sees ALL users, regular admin sees only their company's users
        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $roles = ['admin', 'manager', 'team_leader', 'agent', 'employee'];
        $profiles = Profile::where('is_active', true)->get();

        return view('administration.users.index', compact('users', 'roles', 'profiles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can create users.');
        }

        $roles = ['admin', 'manager', 'team_leader', 'agent', 'employee'];
        $profiles = Profile::where('is_active', true)->get();
        $managers = User::where('role', 'manager')
            ->where('company_id', $user->company_id)
            ->get();

        return view('administration.users.create', compact('roles', 'profiles', 'managers'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can create users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,manager,team_leader,agent,employee',
            'profile_id' => 'nullable|exists:profiles,id',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        // Only super admin can create admin users
        if ($validated['role'] === 'admin' && !$user->isSuperAdmin()) {
            return back()->withErrors(['role' => 'Only super administrators can create admin users.']);
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['company_id'] = $user->company_id;
        $validated['is_admin'] = $validated['role'] === 'admin' ? 1 : 0;
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('administration.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Super admin can view all users, regular admin can view users from their company
        if (!$currentUser->isSuperAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized access');
        }

        $user->load(['profile', 'role', 'manager', 'subordinates']);

        return view('administration.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Only administrators can edit users.');
        }

        // Super admin can edit all users, regular admin can edit users from their company
        if (!$currentUser->isSuperAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized access');
        }

        $roles = ['admin', 'manager', 'team_leader', 'agent', 'employee'];
        $profiles = Profile::where('is_active', true)->get();
        $managers = User::where('role', 'manager')
            ->where('company_id', $currentUser->company_id)
            ->where('id', '!=', $user->id) // Can't be manager of themselves
            ->get();

        return view('administration.users.edit', compact('user', 'roles', 'profiles', 'managers'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Only administrators can update users.');
        }

        // Super admin can update all users, regular admin can update users from their company
        if (!$currentUser->isSuperAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,manager,team_leader,agent,employee',
            'profile_id' => 'nullable|exists:profiles,id',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        // Only super admin can change admin role
        if ($validated['role'] === 'admin' && !$currentUser->isSuperAdmin()) {
            return back()->withErrors(['role' => 'Only super administrators can manage admin users.']);
        }

        // Don't allow changing your own role
        if ($user->id === $currentUser->id && $validated['role'] !== $user->role) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $validated['role'] === 'admin' ? 1 : 0;

        $user->update($validated);

        return redirect()->route('administration.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Only administrators can delete users.');
        }

        // Can't delete yourself
        if ($user->id === $currentUser->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        // Super admin can delete all users, regular admin can delete users from their company
        if (!$currentUser->isSuperAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized access');
        }

        $user->delete();

        return redirect()->route('administration.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
