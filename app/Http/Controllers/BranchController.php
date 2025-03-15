<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        // Get the first company for now (we can modify this based on your requirements)
        $company = Company::first();
        
        if (!$company) {
            // Handle case when no company exists
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

        // Get the first company (or handle company selection based on your needs)
        $company = Company::first();
        
        if (!$company) {
            return redirect()->route('companies.create')
                ->with('error', 'Please create a company first');
        }

        $validated['company_id'] = $company->id;

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully');
    }

    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
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
