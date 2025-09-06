<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Log the user in
        auth()->login($user);

        return redirect('/');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'company_phone' => ['nullable', 'string', 'max:20'],
        ]);
    }

    protected function create(array $data)
    {
        // Create company
        $company = Company::create([
            'name' => $data['company_name'],
            'slug' => Str::slug($data['company_name']),
            'email' => $data['company_email'],
            'phone' => $data['company_phone'] ?? null,
            'is_active' => true,
        ]);

        // Create CEO role for the company
        $role = Role::create([
            'name' => 'CEO',
            'display_name' => 'Chief Executive Officer',
            'description' => 'Company Chief Executive Officer',
        ]);

        // Create user as CEO
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $company->id,
            'role_id' => $role->id,
            'is_company_admin' => true,
            'is_active' => true,
        ]);

        // Set company owner
        $company->update(['owner_id' => $user->id]);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        // Record activity
        activity()
            ->performedOn($user->company)
            ->causedBy($user)
            ->log('New company registered');

        return redirect('/dashboard');
    }
}
