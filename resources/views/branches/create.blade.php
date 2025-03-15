<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Add New Branch') }}
            </h2>
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <span class="px-2">/</span>
                <a href="{{ route('management.index') }}" class="hover:text-gray-700">{{ __('Management') }}</a>
                <span class="px-2">/</span>
                <a href="{{ route('branches.index') }}" class="hover:text-gray-700">{{ __('Branch Offices') }}</a>
                <span class="px-2">/</span>
                <span class="text-gray-700">{{ __('Add New') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-2 flex justify-end">
                <a href="{{ route('branches.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs mr-2"></i>
                    {{ __('Back to List') }}
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm">
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="p-3 border-b border-gray-200">
                        <!-- Basic Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="name">
                                        {{ __('Branch Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="code">
                                        {{ __('Branch Code') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Location Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700" for="address">
                                        {{ __('Address') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="city">
                                        {{ __('City') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="state">
                                        {{ __('State/Province') }}
                                    </label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="country">
                                        {{ __('Country') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="country" id="country" value="{{ old('country') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Contact Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="phone">
                                        {{ __('Phone') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        required>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="email">
                                        {{ __('Email') }}
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Manager Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Manager Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="manager_name">
                                        {{ __('Manager Name') }}
                                    </label>
                                    <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="manager_phone">
                                        {{ __('Manager Phone') }}
                                    </label>
                                    <input type="text" name="manager_phone" id="manager_phone" value="{{ old('manager_phone') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="manager_email">
                                        {{ __('Manager Email') }}
                                    </label>
                                    <input type="email" name="manager_email" id="manager_email" value="{{ old('manager_email') }}"
                                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700" for="notes">
                                {{ __('Additional Notes') }}
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Branch is active') }}</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Button - Aligned with form content -->
                    <div class="px-3 py-2 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                            <i class="fas fa-save text-xs mr-2"></i>
                            {{ __('Create Branch') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
