@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('Edit User') }}</h1>
                    <p class="text-muted mb-0">{{ __('Edit user information and permissions') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('administration.users.show', $user) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>{{ __('View User') }}
                    </a>
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
                            <form action="{{ route('administration.users.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('Password') }}</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" placeholder="{{ __('Leave blank to keep current password') }}">
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
                                                @foreach($roles as $role)
                                                    <option value="{{ $role }}" 
                                                        {{ old('role', $user->role) === $role ? 'selected' : '' }}
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
                                                        {{ old('profile_id', $user->profile_id) == $profile->id ? 'selected' : '' }}>
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
                                                        {{ old('manager_id', $user->manager_id) == $manager->id ? 'selected' : '' }}>
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
                                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile" class="form-label">{{ __('Mobile') }}</label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" 
                                                   id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}">
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
                                                   id="position" name="position" value="{{ old('position', $user->position) }}">
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
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
                                              id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Update User') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Current Role:') }}</strong>
                                <span class="badge 
                                    @if($user->role === 'admin') bg-danger
                                    @elseif($user->role === 'manager') bg-warning
                                    @elseif($user->role === 'team_leader') bg-info
                                    @else bg-secondary
                                    @endif ms-2">
                                    {{ ucfirst(str_replace('_', ' ', $user->role ?? 'No Role')) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Status:') }}</strong>
                                @if($user->is_active)
                                    <span class="badge bg-success ms-2">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary ms-2">{{ __('Inactive') }}</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Member Since:') }}</strong>
                                <br><small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                            </div>

                            @if($user->email_verified_at)
                            <div class="mb-3">
                                <strong>{{ __('Email Verified:') }}</strong>
                                <br><small class="text-muted">{{ $user->email_verified_at->format('M d, Y') }}</small>
                            </div>
                            @endif

                            @if($user->isSuperAdmin())
                            <div class="alert alert-warning">
                                <i class="fas fa-crown me-2"></i>
                                <strong>{{ __('Super Administrator') }}</strong><br>
                                <small>{{ __('This user has full system access.') }}</small>
                            </div>
                            @endif

                            @if($user->id === auth()->id())
                            <div class="alert alert-info">
                                <i class="fas fa-user me-2"></i>
                                <strong>{{ __('This is you!') }}</strong><br>
                                <small>{{ __('Be careful when editing your own account.') }}</small>
                            </div>
                            @endif
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
