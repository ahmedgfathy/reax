<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Team Details') }} - {{ $team->name }}
                </h2>
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                    <span class="px-2">/</span>
                    <a href="{{ route('teams.index') }}" class="hover:text-gray-700">{{ __('Teams') }}</a>
                    <span class="px-2">/</span>
                    <span class="text-gray-700">{{ __('Details') }}</span>
                </div>
            </div>ß
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Team Details Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Basic Information') }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Team Name') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Team Code') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->code }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Department') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $team->department ? $team->department->name : __('None') }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Created Date') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->created_at->format('M d, Y') }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Team Leader -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Team Leader') }}</h3>
                            @if($team->leader)
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $team->leader->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $team->leader->email }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">{{ __('No leader assigned') }}</p>
                            @endif
                        </div>

                        <!-- Permissions -->
                        <div class="space-y-4 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Team Permissions') }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('External Sharing') }}</dt>
                                    <dd class="mt-1 text-sm">
                                        @if($team->can_share_externally)
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                {{ __('Enabled') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ __('Disabled') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Public Listings') }}</dt>
                                    <dd class="mt-1 text-sm">
                                        @if($team->public_listing_allowed)
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                {{ __('Allowed') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ __('Not Allowed') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('teams.members.assign', $team) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Assign Members') }}
                </a>
                <a href="{{ route('teams.edit', $team) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    {{ __('Edit Team') }}
                </a>
                <form action="{{ route('teams.destroy', $team) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                            onclick="return confirm('{{ __('Are you sure you want to delete this team?') }}')">
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('Delete Team') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
