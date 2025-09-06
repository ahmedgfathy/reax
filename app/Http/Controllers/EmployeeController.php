<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::paginate(10);
        return view('administration.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('administration.employees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('administration.employees.create')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
            'role' => 'employee'
        ]);

        return redirect()
            ->route('administration.employees.index')
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $employee->update($validated);

        return redirect()->route('administration.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('administration.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
