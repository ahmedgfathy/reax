@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('User Management') }}</h1>
                    <p class="text-muted mb-0">{{ __('Manage all system users') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('administration.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Administration') }}
                    </a>
                    <a href="{{ route('administration.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Add New User') }}
                    </a>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('administration.users.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">{{ __('Search') }}</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="{{ __('Search by name or email...') }}" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">{{ __('Role') }}</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">{{ __('All Roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary me-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('administration.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ __('All Users') }} ({{ $users->total() }})
                        @if(auth()->user()->isSuperAdmin())
                            <span class="badge bg-danger ms-2">{{ __('Super Admin View') }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Profile') }}</th>
                                    <th>{{ __('Manager') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th width="120">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                @if($user->avatar)
                                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-info ms-1">{{ __('You') }}</span>
                                                @endif
                                                @if($user->isSuperAdmin())
                                                    <span class="badge bg-danger ms-1">{{ __('Super Admin') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($user->role === 'admin') bg-danger
                                            @elseif($user->role === 'manager') bg-warning
                                            @elseif($user->role === 'team_leader') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->role ?? 'No Role')) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->profile)
                                            <span class="text-primary">{{ $user->profile->display_name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('No Profile') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->manager)
                                            <span class="text-dark">{{ $user->manager->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('No Manager') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('administration.users.show', $user) }}" 
                                               class="btn btn-sm btn-outline-info" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('administration.users.edit', $user) }}" 
                                               class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('administration.users.destroy', $user) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small">
                            {{ __('Showing') }} {{ $users->firstItem() ?? 0 }} {{ __('to') }} {{ $users->lastItem() ?? 0 }} 
                            {{ __('of') }} {{ $users->total() }} {{ __('users') }}
                        </div>
                        {{ $users->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No users found') }}</h5>
                        <p class="text-muted">{{ __('No users match your current filters.') }}</p>
                        <a href="{{ route('administration.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('Add First User') }}
                        </a>
                    </div>
                    @endif
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
