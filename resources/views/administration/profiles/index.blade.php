@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Profiles Management') }}</h1>
            <p class="text-gray-600">{{ __('Create and manage user profiles with specific permissions and access levels') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('administration.role-management.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                {{ __('Role Management') }}
            </a>
            <a href="{{ route('administration.profiles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                {{ __('Create Profile') }}
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profiles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($profiles as $profile)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <!-- Profile Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $profile->display_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $profile->name }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($profile->level === 'administration') bg-red-100 text-red-800
                            @elseif($profile->level === 'manager') bg-blue-100 text-blue-800
                            @elseif($profile->level === 'team_leader') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $profile->level)) }}
                        </span>
                    </div>
                    
                    @if($profile->description)
                        <p class="text-sm text-gray-600 mt-2">{{ $profile->description }}</p>
                    @endif
                </div>
                
                <!-- Profile Stats -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $profile->users->count() }}</div>
                            <div class="text-xs text-gray-500">{{ __('Users') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $profile->permissions->count() }}</div>
                            <div class="text-xs text-gray-500">{{ __('Permissions') }}</div>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="flex items-center justify-center mb-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $profile->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $profile->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('administration.profiles.show', $profile) }}" 
                           class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                            {{ __('View') }}
                        </a>
                        <a href="{{ route('administration.profiles.edit', $profile) }}" 
                           class="flex-1 bg-gray-600 text-white text-center px-3 py-2 rounded-md text-sm hover:bg-gray-700 transition-colors">
                            {{ __('Edit') }}
                        </a>
                        @if($profile->users->count() === 0)
                            <form action="{{ route('administration.profiles.destroy', $profile) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Are you sure you want to delete this profile?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($profiles->hasPages())
        <div class="mt-8">
            {{ $profiles->links() }}
        </div>
    @endif

    <!-- Empty State -->
    @if($profiles->count() === 0)
        <div class="text-center py-16">
            <div class="mx-auto h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No profiles found') }}</h3>
            <p class="text-gray-600 mb-6">{{ __('Get started by creating your first user profile.') }}</p>
            <a href="{{ route('administration.profiles.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors">
                {{ __('Create First Profile') }}
            </a>
        </div>
    @endif
</div>
@endsection
