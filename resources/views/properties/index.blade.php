<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Properties') }}</h2>
                    <!-- Breadcrumbs -->
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-700">{{ __('Properties') }}</span>
                        @if(request()->has('type'))
                            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-gray-700">{{ __(ucfirst(request('type'))) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @foreach([
            ['label' => 'Total Properties', 'value' => $stats['total'], 'icon' => 'fa-building', 'color' => 'blue'],
            ['label' => 'Available', 'value' => $stats['available'], 'icon' => 'fa-check-circle', 'color' => 'green'],
            ['label' => 'Sold', 'value' => $stats['sold'], 'icon' => 'fa-dollar-sign', 'color' => 'yellow'],
            ['label' => 'Rented', 'value' => $stats['rented'], 'icon' => 'fa-key', 'color' => 'purple']
        ] as $stat)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __($stat['label']) }}</p>
                        <h3 class="text-2xl font-bold mt-2 text-{{ $stat['color'] }}-600">
                            {{ number_format($stat['value']) }}
                        </h3>
                    </div>
                    <div class="bg-{{ $stat['color'] }}-50 p-4 rounded-lg">
                        <i class="fas {{ $stat['icon'] }} text-xl text-{{ $stat['color'] }}-500"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-6">
            <form action="{{ route('properties.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Smart Search -->
                    <div class="col-span-full lg:col-span-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="{{ __('Search properties...') }}">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Property Type Filter -->
                    <div class="relative">
                        <select name="type" class="form-select w-full py-3 pl-4 pr-10 appearance-none bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ __(ucfirst($type)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" class="form-select w-full py-3 pl-4 pr-10 appearance-none bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('All Status') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ __(ucfirst($status)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="relative">
                        <select name="price_range" class="form-select w-full py-3 pl-4 pr-10 appearance-none bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('Price Range') }}</option>
                            <option value="0-100000">{{ __('Under 100,000') }}</option>
                            <option value="100000-500000">{{ __('100,000 - 500,000') }}</option>
                            <option value="500000-1000000">{{ __('500,000 - 1,000,000') }}</option>
                            <option value="1000000+">{{ __('Over 1,000,000') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t mt-4">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                            <i class="fas fa-filter mr-2"></i>
                            {{ __('Apply Filters') }}
                        </button>
                        @if(request()->hasAny(['search', 'type', 'status', 'price_range']))
                            <a href="{{ route('properties.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-lg shadow-sm hover:from-gray-700 hover:to-gray-800 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleExportModal()" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg shadow-sm hover:from-green-700 hover:to-green-800 transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>
                            {{ __('Export') }}
                        </button>
                        <button type="button" onclick="toggleImportModal()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg shadow-sm hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            {{ __('Import') }}
                        </button>
                        <a href="{{ route('properties.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            {{ __('Add Property') }}
                        </a>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($properties as $property)
            @include('properties.partials.property-card', ['property' => $property])
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <i class="fas fa-home text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No Properties Found') }}</h3>
                    <p class="text-gray-500">{{ __('Try adjusting your search or filter criteria') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($properties->hasPages())
        <div class="mt-6">
            {{ $properties->links() }}
        </div>
    @endif

    <!-- Import/Export Modals -->
    <!-- ...existing modal code... -->
</x-app-layout>
