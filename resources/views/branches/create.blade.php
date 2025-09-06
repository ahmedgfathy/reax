<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Create Branch Office') }}
                </h2>
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">
                        {{ __('Dashboard') }}
                    </a>
                    <span class="px-2">/</span>
                    <a href="{{ route('management.index') }}" class="hover:text-gray-700">
                        {{ __('Management') }}
                    </a>
                    <span class="px-2">/</span>
                    <a href="{{ route('branches.index') }}" class="hover:text-gray-700">
                        {{ __('Branches') }}
                    </a>
                    <span class="px-2">/</span>
                    <span class="text-gray-700">{{ __('Create') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('branches.store') }}" method="POST" class="bg-white rounded-lg shadow-sm">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Branch Name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Branch Code') }}</label>
                            <input type="text" name="code" value="{{ old('code') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone') }}</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Address') }}</label>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('City') }}</label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('State') }}</label>
                            <input type="text" name="state" value="{{ old('state') }}"
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Country') }}</label>
                            <input type="text" name="country" value="{{ old('country') }}" required
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                    </div>

                    <!-- Manager Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Manager Name') }}</label>
                            <input type="text" name="manager_name" value="{{ old('manager_name') }}"
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Manager Phone') }}</label>
                            <input type="tel" name="manager_phone" value="{{ old('manager_phone') }}"
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Manager Email') }}</label>
                            <input type="email" name="manager_email" value="{{ old('manager_email') }}"
                                   class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Notes') }}</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-end space-x-3">
                    <a href="{{ route('branches.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Create Branch') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
