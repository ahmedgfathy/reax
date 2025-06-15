@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Goal</h1>
                    <p class="mt-2 text-sm text-gray-600">Update goal information and targets</p>
                </div>
                <div>
                    <a href="{{ route('management.goals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Goals
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Goal Information</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('management.goals.update', $goal) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Goal Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Goal Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $goal->title) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Goal Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Goal Type</label>
                            <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="sales" {{ old('type', $goal->type) == 'sales' ? 'selected' : '' }}>Sales</option>
                                <option value="revenue" {{ old('type', $goal->type) == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                <option value="leads" {{ old('type', $goal->type) == 'leads' ? 'selected' : '' }}>Leads</option>
                                <option value="calls" {{ old('type', $goal->type) == 'calls' ? 'selected' : '' }}>Calls</option>
                                <option value="meetings" {{ old('type', $goal->type) == 'meetings' ? 'selected' : '' }}>Meetings</option>
                                <option value="individual" {{ old('type', $goal->type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="team" {{ old('type', $goal->type) == 'team' ? 'selected' : '' }}>Team</option>
                                <option value="company" {{ old('type', $goal->type) == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Value -->
                        <div>
                            <label for="target_value" class="block text-sm font-medium text-gray-700 mb-2">Target Value</label>
                            <input type="number" name="target_value" id="target_value" value="{{ old('target_value', $goal->target_value) }}" 
                                   step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('target_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Value -->
                        <div>
                            <label for="current_value" class="block text-sm font-medium text-gray-700 mb-2">Current Value</label>
                            <input type="number" name="current_value" id="current_value" value="{{ old('current_value', $goal->current_value) }}" 
                                   step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('current_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Measurement Unit -->
                        <div>
                            <label for="measurement_unit" class="block text-sm font-medium text-gray-700 mb-2">Measurement Unit</label>
                            <input type="text" name="measurement_unit" id="measurement_unit" value="{{ old('measurement_unit', $goal->measurement_unit) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="e.g., USD, Units, Calls, etc." required>
                            @error('measurement_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $goal->deadline ? $goal->deadline->format('Y-m-d') : '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="low" {{ old('priority', $goal->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $goal->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $goal->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority', $goal->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="draft" {{ old('status', $goal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $goal->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $goal->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $goal->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $goal->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned User (optional) -->
                    <div class="mt-6">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned User (Optional)</label>
                        <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a user (optional)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $goal->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                    @if($isSuperAdmin && $user->company)
                                        ({{ $user->company->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned Team (optional) -->
                    <div class="mt-6">
                        <label for="team_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned Team (Optional)</label>
                        <select name="team_id" id="team_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a team (optional)</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id', $goal->team_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                    @if($isSuperAdmin && $team->company)
                                        ({{ $team->company->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Milestones (JSON) -->
                    <div class="mt-6">
                        <label for="milestones" class="block text-sm font-medium text-gray-700 mb-2">Milestones (JSON)</label>
                        <textarea name="milestones" id="milestones" rows="6" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                                  placeholder='[{"title": "Milestone 1", "target": 25, "deadline": "2025-07-01"}, {...}]'>{{ old('milestones', $goal->milestones) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter milestone data in JSON format</p>
                        @error('milestones')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('management.goals.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>Update Goal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
