<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $contact->first_name }} {{ $contact->last_name }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('contacts.edit', $contact) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-md hover:bg-yellow-200 transition-colors duration-200">
                    <i class="fas fa-edit text-xs mr-1.5"></i>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('contacts.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xs mr-1.5"></i>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">{{ __('Basic Information') }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Type') }}</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $contact->type == 'client' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $contact->type == 'prospect' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $contact->type == 'partner' ? 'bg-purple-100 text-purple-800' : '' }}">
                                            {{ ucfirst($contact->type) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Position') }}</label>
                                    <p class="mt-1">{{ $contact->position ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">{{ __('Contact Details') }}</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Email') }}</label>
                                    <p class="mt-1">
                                        <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">
                                            {{ $contact->email }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Phone') }}</label>
                                    <p class="mt-1">{{ $contact->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Mobile') }}</label>
                                    <p class="mt-1">{{ $contact->mobile ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($contact->notes)
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Notes') }}</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $contact->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
