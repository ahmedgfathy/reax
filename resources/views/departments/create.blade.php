<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ __('Create Department') }}</h2>
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('departments.index') }}" class="hover:text-gray-700">{{ __('Departments') }}</a>
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
            <form action="{{ route('departments.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-form.input 
                                name="name" 
                                :label="__('Department Name')" 
                                required 
                            />
                        </div>

                        <!-- Code -->
                        <div>
                            <x-form.input 
                                name="code" 
                                :label="__('Department Code')" 
                                required 
                            />
                        </div>

                        <!-- Parent Department -->
                        <div>
                            <x-form.select 
                                name="parent_id" 
                                :label="__('Parent Department')"
                                :options="$departments->pluck('name', 'id')"
                            />
                        </div>

                        <!-- Manager Name -->
                        <div>
                            <x-form.input 
                                name="manager_name" 
                                :label="__('Manager Name')"
                            />
                        </div>

                        <!-- Manager Phone -->
                        <div>
                            <x-form.input 
                                name="manager_phone" 
                                :label="__('Manager Phone')"
                                type="tel"
                            />
                        </div>

                        <!-- Manager Email -->
                        <div>
                            <x-form.input 
                                name="manager_email" 
                                :label="__('Manager Email')"
                                type="email"
                            />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                name="description" 
                                :label="__('Description')"
                                rows="3"
                            />
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                name="notes" 
                                :label="__('Notes')"
                                rows="2"
                            />
                        </div>

                        <!-- Is Active -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Department is active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('departments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        {{ __('Create Department') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
