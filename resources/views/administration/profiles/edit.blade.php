@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit Profile: {{ $profile->display_name }}</h3>
                    <div>
                        <a href="{{ route('administration.profiles.show', $profile) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('administration.profiles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Profiles
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('administration.profiles.update', $profile) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Profile Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $profile->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">Display Name *</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" value="{{ old('display_name', $profile->display_name) }}" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Level *</label>
                                    <select class="form-control @error('level') is-invalid @enderror" id="level" name="level" required>
                                        <option value="">Select Level</option>
                                        @foreach($levels as $value => $label)
                                            <option value="{{ $value }}" {{ old('level', $profile->level) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $profile->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Profile
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $profile->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($permissions->count() > 0)
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="card">
                                <div class="card-body">
                                    @foreach($permissions as $module => $modulePermissions)
                                        <div class="mb-3">
                                            <h6 class="text-primary">{{ ucfirst($module) }}</h6>
                                            <div class="row">
                                                @foreach($modulePermissions as $permission)
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" 
                                                                   id="permission_{{ $permission->id }}" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}"
                                                                   {{ in_array($permission->id, old('permissions', $assignedPermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                            <a href="{{ route('administration.profiles.show', $profile) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                            <a href="{{ route('administration.profiles.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
