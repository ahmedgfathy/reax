<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Edit Opportunity') }}: {{ $opportunity->title }}
            </h2>
            <a href="{{ route('opportunities.show', $opportunity) }}" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('opportunities.update', $opportunity) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                                    {{ __('Opportunity Title') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                    value="{{ old('title', $opportunity->title) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lead -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="lead_id">
                                    {{ __('Associated Lead') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="lead_id" id="lead_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">{{ __('Select Lead') }}</option>
                                    @foreach($leads as $lead)
                                        <option value="{{ $lead->id }}" {{ old('lead_id', $opportunity->lead_id) == $lead->id ? 'selected' : '' }}>
                                            {{ $lead->first_name }} {{ $lead->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lead_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Property -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="property_id">
                                    {{ __('Related Property') }}
                                </label>
                                <select name="property_id" id="property_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">{{ __('Select Property') }}</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}" {{ old('property_id', $opportunity->property_id) == $property->id ? 'selected' : '' }}>
                                            {{ $property->property_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Value and Probability -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="value">
                                        {{ __('Value') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="value" id="value"
                                            value="{{ old('value', $opportunity->value) }}"
                                            class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="probability">
                                        {{ __('Probability (%)') }}
                                    </label>
                                    <input type="number" name="probability" id="probability"
                                        value="{{ old('probability', $opportunity->probability) }}"
                                        min="0" max="100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Status and Stage -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="status">
                                        {{ __('Status') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach(['pending', 'negotiation', 'won', 'lost'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $opportunity->status) == $status ? 'selected' : '' }}>
                                                {{ __(ucfirst($status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="stage">
                                        {{ __('Stage') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="stage" id="stage"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach(['initial', 'qualified', 'proposal', 'negotiation'] as $stage)
                                            <option value="{{ $stage }}" {{ old('stage', $opportunity->stage) == $stage ? 'selected' : '' }}>
                                                {{ __(ucfirst($stage)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Assigned To and Priority -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="assigned_to">
                                        {{ __('Assigned To') }}
                                    </label>
                                    <select name="assigned_to" id="assigned_to"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">{{ __('Select User') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $opportunity->assigned_to) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="priority">
                                        {{ __('Priority') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="priority" id="priority"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach(['low', 'medium', 'high'] as $priority)
                                            <option value="{{ $priority }}" {{ old('priority', $opportunity->priority) == $priority ? 'selected' : '' }}>
                                                {{ __(ucfirst($priority)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Expected Close Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="expected_close_date">
                                    {{ __('Expected Close Date') }}
                                </label>
                                <input type="date" name="expected_close_date" id="expected_close_date"
                                    value="{{ old('expected_close_date', $opportunity->expected_close_date?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="description">
                                    {{ __('Description') }}
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $opportunity->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-end gap-4">
                        <button type="button" onclick="history.back()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Update Opportunity') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
