<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('companies.create')
                ->with('error', 'Please create a company first');
        }

        $departments = Department::where('company_id', $company->id)
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        return view('departments.index', compact('departments', 'company'));
    }

    public function create()
    {
        $departments = Department::where('company_id', auth()->user()->company_id)->get();
        return view('departments.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully');
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $departments = Department::where('company_id', auth()->user()->company_id)
            ->where('id', '!=', $department->id)
            ->get();
        return view('departments.edit', compact('department', 'departments'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }
}
