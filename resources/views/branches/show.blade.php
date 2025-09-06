<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Branch Details') }}: {{ $branch->name }}
            </h2>
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <span class="px-2">/</span>
                <a href="{{ route('management.index') }}" class="hover:text-gray-700">{{ __('Management') }}</a>
                <span class="px-2">/</span>
                <a href="{{ route('branches.index') }}" class="hover:text-gray-700">{{ __('Branch Offices') }}</a>
                <span class="px-2">/</span>
                <span class="text-gray-700">{{ __('Details') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="mb-2 flex justify-end space-x-2">
                <a href="{{ route('branches.edit', $branch) }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-sm">
                    <i class="fas fa-edit text-xs mr-2"></i>
                    {{ __('Edit Branch') }}
                </a>
                <a href="{{ route('branches.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs mr-2"></i>
                    {{ __('Back to List') }}
                </a>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-3 border-b border-gray-200">
                    <!-- Basic Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Branch Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Branch Code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $branch->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Location Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Address') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->address }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('City') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->city }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('State') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->state ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Country') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->country }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Contact Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->email ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Manager Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Manager Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Manager Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Manager Phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Manager Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->manager_email ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($branch->notes)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Notes') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $branch->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
