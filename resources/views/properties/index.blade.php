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

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4">
            <form method="GET" action="{{ route('properties.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full px-4 py-2 pl-10 pr-4 rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="{{ __('Search properties...') }}">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Region/Area Filter -->
                    <div class="relative">
                        <select name="region" class="w-full px-4 py-2 rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Regions') }}</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ __(ucfirst($region)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- User Filter -->
                    <div class="relative">
                        <select name="user_id" class="w-full px-4 py-2 rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Users') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- More Filters Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="w-full px-4 py-2 rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white text-left flex justify-between items-center">
                            <span>{{ __('More Filters') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute z-50 mt-2 w-full bg-white rounded-lg shadow-lg border p-4 space-y-4">
                            <!-- Price Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price Range') }}</label>
                                <select name="price_range" class="w-full rounded-md border-gray-300">
                                    <option value="">{{ __('Any Price') }}</option>
                                    <option value="0-100000" {{ request('price_range') == '0-100000' ? 'selected' : '' }}>0 - 100,000</option>
                                    <option value="100000-500000" {{ request('price_range') == '100000-500000' ? 'selected' : '' }}>100,000 - 500,000</option>
                                    <option value="500000+" {{ request('price_range') == '500000+' ? 'selected' : '' }}>500,000+</option>
                                </select>
                            </div>
                            
                            <!-- Property Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Type') }}</label>
                                <select name="type" class="w-full rounded-md border-gray-300">
                                    <option value="">{{ __('All Types') }}</option>
                                    @foreach($propertyTypes as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ __(ucfirst($type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                                <select name="status" class="w-full rounded-md border-gray-300">
                                    <option value="">{{ __('All Status') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ __(ucfirst($status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i>{{ __('Apply Filters') }}
                        </button>
                        @if(request()->hasAny(['search', 'type', 'status', 'price_range', 'region', 'user_id']))
                            <a href="{{ route('properties.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                <i class="fas fa-times mr-2"></i>{{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="exportProperties()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-download mr-2"></i>{{ __('Export') }}
                        </button>
                        <button type="button" onclick="importProperties()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-upload mr-2"></i>{{ __('Import') }}
                        </button>
                        <a href="{{ route('properties.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>{{ __('Add Property') }}
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
