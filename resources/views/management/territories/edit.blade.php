@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Territory</h1>
                    <p class="mt-2 text-sm text-gray-600">Update territory information and assignments</p>
                </div>
                <div>
                    <a href="{{ route('management.territories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Territories
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Territory Information</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('management.territories.update', $territory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Territory Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Territory Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $territory->name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Territory Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Territory Type</label>
                            <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="geographic" {{ old('type', $territory->type) == 'geographic' ? 'selected' : '' }}>Geographic</option>
                                <option value="demographic" {{ old('type', $territory->type) == 'demographic' ? 'selected' : '' }}>Demographic</option>
                                <option value="product" {{ old('type', $territory->type) == 'product' ? 'selected' : '' }}>Product-based</option>
                                <option value="account" {{ old('type', $territory->type) == 'account' ? 'selected' : '' }}>Account-based</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company (for Super Admin) -->
                        @if($isSuperAdmin)
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <select name="company_id" id="company_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $territory->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="is_active" id="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('is_active', $territory->is_active) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('is_active', $territory->is_active) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $territory->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned Users -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Users</label>
                        <div class="border border-gray-300 rounded-md p-4 max-h-48 overflow-y-auto">
                            @foreach($users as $user)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}" 
                                           id="user_{{ $user->id }}" 
                                           {{ $territory->assignedUsers->contains($user->id) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $user->name }} ({{ $user->email }})
                                        @if($isSuperAdmin && $user->company)
                                            <span class="text-gray-500">- {{ $user->company->name }}</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('assigned_users')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Boundaries (JSON) -->
                    <div class="mt-6">
                        <label for="boundaries" class="block text-sm font-medium text-gray-700 mb-2">Territory Boundaries (JSON)</label>
                        <textarea name="boundaries" id="boundaries" rows="6" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                                  placeholder='{"type": "geographic", "areas": ["Area 1", "Area 2"], "coordinates": {...}}'>{{ old('boundaries', $territory->boundaries) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter territory boundary data in JSON format</p>
                        @error('boundaries')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('management.territories.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>Update Territory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
