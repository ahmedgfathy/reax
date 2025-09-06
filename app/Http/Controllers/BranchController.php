<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('companies.create')
                ->with('error', 'Please create a company first');
        }

        $branches = Branch::where('company_id', $company->id)
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        return view('branches.index', compact('branches', 'company'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $user = auth()->user();
        $company = $user->company;

        // Check if user is company admin/CEO
        if (!$user->is_company_admin && !$user->isCompanyOwner()) {
            return redirect()->route('branches.index')
                ->with('error', 'You do not have permission to create branches');
        }

        $validated['company_id'] = $company->id;
        $branch = Branch::create($validated);

        // Log activity with required fields
        ActivityLog::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'event_type' => 'branch_created',
            'action_type' => 'create', // Add required field
            'module_name' => 'branches', // Add required field
            'description' => "Created new branch: {$branch->name}",
            'loggable_type' => Branch::class,
            'loggable_id' => $branch->id,
            'ip_address' => request()->ip(),
            'old_values' => null,
            'new_values' => $validated
        ]);

        return redirect()->route('branches.index')
            ->with('success', __('Branch created successfully'));
    }

    public function show(Branch $branch)
    {
        // Authorization check: Super admin can view all branches, others can only view their company's branches
        if (!auth()->user()->isSuperAdmin() && $branch->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to this branch.');
        }

        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        // Authorization check: Super admin can edit all branches, others can only edit their company's branches
        if (!auth()->user()->isSuperAdmin() && $branch->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to edit this branch.');
        }

        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully');
    }
}
