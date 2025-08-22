<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">{{ $company->name }}</h2>
            <div class="flex space-x-4">
                <a href="{{ route('companies.edit', $company) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                </a>
                <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Company Details Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">{{ __('Company Information') }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Email') }}</label>
                                    <p class="mt-1">
                                        <a href="mailto:{{ $company->email }}" class="text-blue-600 hover:underline">
                                            {{ $company->email }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Phone') }}</label>
                                    <p class="mt-1">{{ $company->phone }}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm text-gray-500">{{ __('Address') }}</label>
                                    <p class="mt-1">{{ $company->address }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Status') }}</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $company->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Company Statistics -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">{{ __('Company Statistics') }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <i class="fas fa-users text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-500">{{ __('Total Users') }}</p>
                                            <p class="text-lg font-semibold">{{ $company->users->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-green-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <i class="fas fa-chart-line text-green-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-500">{{ __('Active Since') }}</p>
                                            <p class="text-lg font-semibold">{{ $company->created_at->format('M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
