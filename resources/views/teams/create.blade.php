<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ __('Create Team') }}</h2>
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('teams.index') }}" class="hover:text-gray-700">{{ __('Teams') }}</a>
                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-700">{{ __('Create') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('teams.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-form.input 
                                name="name" 
                                :label="__('Team Name')" 
                                required 
                            />
                        </div>

                        <!-- Code -->
                        <div>
                            <x-form.input 
                                name="code" 
                                :label="__('Team Code')" 
                                required 
                            />
                        </div>

                        <!-- Team Leader -->
                        <div>
                            <x-form.select 
                                name="leader_id" 
                                :label="__('Team Leader')"
                                :options="$users->pluck('name', 'id')"
                                required
                            />
                        </div>

                        <!-- Department -->
                        <div>
                            <x-form.select 
                                name="department_id" 
                                :label="__('Department')"
                                :options="$departments->pluck('name', 'id')"
                            />
                        </div>

                        <!-- Permissions -->
                        <div class="md:col-span-2 space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_share_externally" value="1" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Allow external sharing') }}</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="public_listing_allowed" value="1" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Allow public listings') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('teams.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        {{ __('Create Team') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
