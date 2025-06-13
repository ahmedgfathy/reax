@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('User Details') }}</h1>
                    <p class="text-muted mb-0">{{ __('Comprehensive user information and permissions') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('administration.users.edit', $user) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit User') }}
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Full Name:') }}</strong>
                                        <br>{{ $user->name }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Email Address:') }}</strong>
                                        <br>{{ $user->email }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Role:') }}</strong>
                                        <br>
                                        <span class="badge 
                                            @if($user->role === 'admin') bg-danger
                                            @elseif($user->role === 'manager') bg-warning
                                            @elseif($user->role === 'team_leader') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->role ?? 'No Role')) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('User Profile:') }}</strong>
                                        <br>
                                        @if($user->profile)
                                            <span class="badge bg-primary">{{ $user->profile->display_name }} ({{ ucfirst($user->profile->level) }})</span>
                                        @else
                                            <span class="text-muted">{{ __('No Profile Assigned') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($user->phone || $user->mobile)
                            <div class="row">
                                @if($user->phone)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Phone:') }}</strong>
                                        <br>{{ $user->phone }}
                                    </div>
                                </div>
                                @endif
                                @if($user->mobile)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Mobile:') }}</strong>
                                        <br>{{ $user->mobile }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($user->position)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Position:') }}</strong>
                                        <br>{{ $user->position }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($user->address)
                            <div class="mb-3">
                                <strong>{{ __('Address:') }}</strong>
                                <br>{{ $user->address }}
                            </div>
                            @endif

                            @if($user->manager)
                            <div class="mb-3">
                                <strong>{{ __('Reports To:') }}</strong>
                                <br>{{ $user->manager->name }} ({{ $user->manager->email }})
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($user->profile && $user->profile->permissions->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Permissions') }}</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $groupedPermissions = $user->profile->permissions->groupBy('module');
                            @endphp
                            
                            <div class="row">
                                @foreach($groupedPermissions as $module => $permissions)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary">{{ ucfirst($module) }}</h6>
                                    <ul class="list-unstyled">
                                        @foreach($permissions as $permission)
                                        <li class="mb-1">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $permission->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Status') }}</h5>
                        </div>
                        <div class="card-body">
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

                            @if($user->last_login_at)
                            <div class="mb-3">
                                <strong>{{ __('Last Login:') }}</strong>
                                <br><small class="text-muted">{{ $user->last_login_at->format('M d, Y H:i') }}</small>
                            </div>
                            @endif

                            @if($user->isSuperAdmin())
                            <div class="alert alert-warning">
                                <i class="fas fa-crown me-2"></i>
                                <strong>{{ __('Super Administrator') }}</strong><br>
                                <small>{{ __('This user has full system access.') }}</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($user->subordinates->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('Direct Reports') }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach($user->subordinates as $subordinate)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <strong>{{ $subordinate->name }}</strong><br>
                                    <small class="text-muted">{{ $subordinate->email }}</small>
                                </div>
                                <span class="badge bg-secondary">{{ ucfirst($subordinate->role) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
