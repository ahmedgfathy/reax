@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('Create New User') }}</h1>
                    <p class="text-muted mb-0">{{ __('Add a new user to the system') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('administration.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Users') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('administration.users.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('Minimum 8 characters') }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role }}" 
                                                        {{ old('role') === $role ? 'selected' : '' }}
                                                        @if($role === 'admin' && !auth()->user()->isSuperAdmin()) disabled @endif>
                                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                                        @if($role === 'admin' && !auth()->user()->isSuperAdmin())
                                                            ({{ __('Super Admin Only') }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_id" class="form-label">{{ __('User Profile') }}</label>
                                            <select class="form-select @error('profile_id') is-invalid @enderror" id="profile_id" name="profile_id">
                                                <option value="">{{ __('No Profile Assigned') }}</option>
                                                @foreach($profiles as $profile)
                                                    <option value="{{ $profile->id }}" 
                                                        {{ old('profile_id') == $profile->id ? 'selected' : '' }}>
                                                        {{ $profile->display_name }} ({{ ucfirst($profile->level) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('profile_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="manager_id" class="form-label">{{ __('Manager') }}</label>
                                            <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id">
                                                <option value="">{{ __('No Manager') }}</option>
                                                @foreach($managers as $manager)
                                                    <option value="{{ $manager->id }}" 
                                                        {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                                        {{ $manager->name }} ({{ $manager->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('manager_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile" class="form-label">{{ __('Mobile') }}</label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" 
                                                   id="mobile" name="mobile" value="{{ old('mobile') }}">
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="position" class="form-label">{{ __('Position') }}</label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                                   id="position" name="position" value="{{ old('position') }}">
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    {{ __('Active User') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Create User') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Guidelines') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">{{ __('Role Hierarchy') }}</h6>
                                <ul class="mb-0 small">
                                    <li><strong>Admin:</strong> {{ __('Full system access (Super Admin only)') }}</li>
                                    <li><strong>Manager:</strong> {{ __('Manage team leaders and employees') }}</li>
                                    <li><strong>Team Leader:</strong> {{ __('Manage employees only') }}</li>
                                    <li><strong>Employee:</strong> {{ __('Standard user access') }}</li>
                                </ul>
                            </div>

                            <div class="alert alert-warning">
                                <h6 class="alert-heading">{{ __('Password Requirements') }}</h6>
                                <ul class="mb-0 small">
                                    <li>{{ __('Minimum 8 characters') }}</li>
                                    <li>{{ __('Mix of letters and numbers recommended') }}</li>
                                    <li>{{ __('User will be prompted to change on first login') }}</li>
                                </ul>
                            </div>

                            <div class="alert alert-success">
                                <h6 class="alert-heading">{{ __('After Creation') }}</h6>
                                <p class="mb-0 small">{{ __('The user will receive login credentials and can access the system immediately if marked as active.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach ($errors->all() as $error)
    toastr.error('{{ $error }}');
    @endforeach
});
</script>
@endif
@endsection
