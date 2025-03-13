<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Create Contact') }}
            </h2>
            <a href="{{ route('contacts.index') }}" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Contacts') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('contacts.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- First Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2" for="first_name">
                                            {{ __('First Name') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="first_name" id="first_name" 
                                            value="{{ old('first_name') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('first_name')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2" for="last_name">
                                            {{ __('Last Name') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="last_name" id="last_name" 
                                            value="{{ old('last_name') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('last_name')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                                        {{ __('Title/Position') }}
                                    </label>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Company -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="company_id">
                                        {{ __('Company') }}
                                    </label>
                                    <select name="company_id" id="company_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">{{ __('Select Company') }}</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Contact Information') }}</h3>
                                
                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="email">
                                        {{ __('Email Address') }}
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Phone -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="phone">
                                        {{ __('Phone') }}
                                    </label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Mobile -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="mobile">
                                        {{ __('Mobile') }}
                                    </label>
                                    <input type="text" name="mobile" id="mobile"
                                        value="{{ old('mobile') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Type -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="type">
                                        {{ __('Contact Type') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="prospect" {{ old('type') == 'prospect' ? 'selected' : '' }}>{{ __('Prospect') }}</option>
                                        <option value="client" {{ old('type') == 'client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                                        <option value="partner" {{ old('type') == 'partner' ? 'selected' : '' }}>{{ __('Partner') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="notes">
                            {{ __('Notes') }}
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-end gap-4">
                        <button type="button" onclick="history.back()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Create Contact') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
