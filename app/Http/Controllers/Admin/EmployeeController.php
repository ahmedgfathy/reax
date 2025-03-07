<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('administration.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('administration.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        User::create($validated);

        return redirect()->route('administration.employees.index')
            ->with('success', 'Employee created successfully');
    }

    public function edit(User $employee)
    {
        return view('administration.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$employee->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $employee->update($validated);

        return redirect()->route('administration.employees.index')
            ->with('success', 'Employee updated successfully');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('administration.employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}
