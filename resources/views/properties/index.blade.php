@extends('layouts.app')

@section('content')
<!-- MODERN PROPERTIES PAGE - Updated Version 2.0 -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ __('Properties') }}
                        </h1>
                        <p class="text-gray-600 mt-1 text-sm">{{ __('Manage all properties in the system') }}</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <span class="mr-2">➕</span>{{ __('Add New Property') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @foreach([
                ['label' => 'Total Properties', 'value' => $stats['total'] ?? 0, 'icon' => '🏠', 'color' => 'blue'],
                ['label' => 'For Sale', 'value' => $stats['for_sale'] ?? 0, 'icon' => '🏷️', 'color' => 'green'],
                ['label' => 'For Rent', 'value' => $stats['for_rent'] ?? 0, 'icon' => '🔑', 'color' => 'yellow'],
                ['label' => 'Sold', 'value' => $stats['sold'] ?? 0, 'icon' => '🤝', 'color' => 'red']
            ] as $stat)
                            <div class="group bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-{{ $stat['color'] }}-500 to-{{ $stat['color'] }}-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-2xl">{{ $stat['icon'] }}</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">{{ __($stat['label']) }}</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Filters Section -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl p-6">
                <form method="GET" action="{{ route('properties.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Filters') }}</h3>
                        <button type="button" onclick="this.closest('form').classList.toggle('expanded')" class="text-gray-500 hover:text-gray-700">
                            <span class="text-2xl">🔍</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Search') }}</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('Property name or number...') }}"
                                   class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Property Type') }}</label>
                            <select id="type" 
                                    name="type" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                <option value="duplex" {{ request('type') === 'duplex' ? 'selected' : '' }}>{{ __('Duplex') }}</option>
                                <option value="studio" {{ request('type') === 'studio' ? 'selected' : '' }}>{{ __('Studio') }}</option>
                                <option value="townhouse" {{ request('type') === 'townhouse' ? 'selected' : '' }}>{{ __('Townhouse') }}</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>{{ __('Reserved') }}</option>
                                <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                                <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>{{ __('Rented') }}</option>
                                <option value="under_contract" {{ request('status') === 'under_contract' ? 'selected' : '' }}>{{ __('Under Contract') }}</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Price Range') }}</label>
                            <select id="price_range" 
                                    name="price_range" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('Any Price') }}</option>
                                <option value="0-500000" {{ request('price_range') === '0-500000' ? 'selected' : '' }}>{{ __('Under 500K') }}</option>
                                <option value="500000-1000000" {{ request('price_range') === '500000-1000000' ? 'selected' : '' }}>{{ __('500K - 1M') }}</option>
                                <option value="1000000-2000000" {{ request('price_range') === '1000000-2000000' ? 'selected' : '' }}>{{ __('1M - 2M') }}</option>
                                <option value="2000000-5000000" {{ request('price_range') === '2000000-5000000' ? 'selected' : '' }}>{{ __('2M - 5M') }}</option>
                                <option value="5000000-" {{ request('price_range') === '5000000-' ? 'selected' : '' }}>{{ __('Above 5M') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Additional Filters Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <!-- Region Filter -->
                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Region') }}</label>
                            <select id="region" 
                                    name="region" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Regions') }}</option>
                                <option value="cairo" {{ request('region') === 'cairo' ? 'selected' : '' }}>{{ __('Cairo') }}</option>
                                <option value="giza" {{ request('region') === 'giza' ? 'selected' : '' }}>{{ __('Giza') }}</option>
                                <option value="alexandria" {{ request('region') === 'alexandria' ? 'selected' : '' }}>{{ __('Alexandria') }}</option>
                                <option value="north-coast" {{ request('region') === 'north-coast' ? 'selected' : '' }}>{{ __('North Coast') }}</option>
                            </select>
                        </div>

                        <!-- Agent Filter -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Agent') }}</label>
                            <select id="user_id" 
                                    name="user_id" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Agents') }}</option>
                                @if(isset($users))
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- For Sale/Rent Filter -->
                        <div>
                            <label for="unit_for" class="block text-sm font-medium text-gray-700 mb-2">{{ __('For') }}</label>
                            <select id="unit_for" 
                                    name="unit_for" 
                                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('Sale & Rent') }}</option>
                                <option value="sale" {{ request('unit_for') === 'sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                                <option value="rent" {{ request('unit_for') === 'rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <span class="mr-2">🔍</span>{{ __('Apply Filters') }}
                        </button>
                        
                        @if(request()->hasAny(['search', 'type', 'status', 'price_range', 'region', 'user_id', 'unit_for']))
                            <a href="{{ route('properties.index') }}" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span class="mr-2">✖️</span>{{ __('Clear Filters') }}
                            </a>
                        @endif
                        
                        <div class="flex gap-3 ml-auto">
                            <button type="button" onclick="exportProperties()" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span class="mr-2">📥</span>{{ __('Export') }}
                            </button>
                            <button type="button" onclick="importProperties()" class="px-6 py-3 bg-yellow-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span class="mr-2">📤</span>{{ __('Import') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @forelse($properties as $property)
                @include('properties.partials.property-card', ['property' => $property])
            @empty
                <div class="col-span-full">
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-gray-400 to-gray-500 mb-6">
                            <span class="text-3xl">🏠</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('No Properties Found') }}</h3>
                        <p class="text-gray-600 text-lg mb-6">{{ __('Try adjusting your search or filter criteria') }}</p>
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <span class="mr-2">➕</span>{{ __('Add Your First Property') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl p-6">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function exportProperties() {
    // Get current filters
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Create export URL with current filters
    const exportUrl = '{{ route("properties.export") }}?' + params.toString();
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'properties-export.xlsx';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function importProperties() {
    // Create file input
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx,.xls,.csv';
    
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Create form data
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show loading
        const originalText = 'Import Properties';
        const importBtn = document.querySelector('[onclick="importProperties()"]');
        if (importBtn) {
            importBtn.innerHTML = '<span class="mr-2">⏳</span>Importing...';
            importBtn.disabled = true;
        }
        
        // Submit import
        fetch('{{ route("properties.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Properties imported successfully!');
                window.location.reload();
            } else {
                alert('Import failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Import failed: ' + error.message);
        })
        .finally(() => {
            if (importBtn) {
                importBtn.innerHTML = '<span class="mr-2">📤</span>' + originalText;
                importBtn.disabled = false;
            }
        });
    };
    
    input.click();
}
</script>
@endsection
