@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Profile Details: {{ $profile->display_name }}</h3>
                    <div>
                        <a href="{{ route('administration.profiles.edit', $profile) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('administration.profiles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Profiles
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Profile Name:</th>
                                    <td>{{ $profile->name }}</td>
                                </tr>
                                <tr>
                                    <th>Display Name:</th>
                                    <td>{{ $profile->display_name }}</td>
                                </tr>
                                <tr>
                                    <th>Level:</th>
                                    <td>
                                        <span class="badge badge-{{ $profile->level == 'administration' ? 'danger' : ($profile->level == 'manager' ? 'warning' : ($profile->level == 'team_leader' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $profile->level)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $profile->is_active ? 'success' : 'danger' }}">
                                            {{ $profile->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $profile->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $profile->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            @if($profile->description)
                            <div class="mb-3">
                                <h6>Description:</h6>
                                <p class="text-muted">{{ $profile->description }}</p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <h6>Users Assigned: <span class="badge badge-primary">{{ $profile->users->count() }}</span></h6>
                                @if($profile->users->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($profile->users->take(5) as $user)
                                            <li><i class="fas fa-user"></i> {{ $user->name }} ({{ $user->email }})</li>
                                        @endforeach
                                        @if($profile->users->count() > 5)
                                            <li class="text-muted">... and {{ $profile->users->count() - 5 }} more</li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($profile->permissions->count() > 0)
                    <div class="mt-4">
                        <h5>Assigned Permissions</h5>
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $permissionsByModule = $profile->permissions->groupBy('module');
                                @endphp
                                
                                @foreach($permissionsByModule as $module => $modulePermissions)
                                    <div class="mb-3">
                                        <h6 class="text-primary">{{ ucfirst($module) }} ({{ $modulePermissions->count() }})</h6>
                                        <div class="row">
                                            @foreach($modulePermissions as $permission)
                                                <div class="col-md-3 col-sm-6 mb-1">
                                                    <span class="badge badge-light">
                                                        <i class="fas fa-check text-success"></i> {{ $permission->name }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This profile has no permissions assigned.
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <div class="btn-group">
                            <a href="{{ route('administration.profiles.edit', $profile) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            
                            @if($profile->users->count() == 0)
                            <form action="{{ route('administration.profiles.destroy', $profile) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this profile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Profile
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger" disabled title="Cannot delete profile with assigned users">
                                <i class="fas fa-trash"></i> Delete Profile
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
