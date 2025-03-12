<!-- comments -->
<x-app-layout>
    <x-slot name="header">{{ __('Properties Management') }}</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-800/50 rounded-full p-3">
                    <i class="fas fa-home text-blue-100 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-blue-100">{{ __('Total Properties') }}</h3>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-800/50 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-100 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-green-100">{{ __('Available') }}</h3>
                    <p class="text-3xl font-bold text-white">{{ $stats['available'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-purple-800/50 rounded-full p-3">
                    <i class="fas fa-star text-purple-100 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-purple-100">{{ __('Featured') }}</h3>
                    <p class="text-3xl font-bold text-white">{{ $stats['featured'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm" 
                       placeholder="{{ __('Search properties...') }}">
            </div>
            <div>
                <select name="type" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach(['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land'] as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ __(ucfirst($type)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">{{ __('All Status') }}</option>
                    @foreach(['available', 'sold', 'rented', 'reserved'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ __(ucfirst($status)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="unit_for" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">{{ __('Sale/Rent') }}</option>
                    <option value="sale" {{ request('unit_for') == 'sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                    <option value="rent" {{ request('unit_for') == 'rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>{{ __('Search') }}
                </button>
                <a href="{{ route('properties.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>{{ __('Add Property') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Properties Grid -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($properties as $property)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border hover:shadow-md transition-all duration-300">
                        <!-- Property Image with Overlay -->
                        <div class="relative h-48 overflow-hidden group">
                            <img src="{{ $property->media->where('is_featured', true)->first()?->file_path ?? 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914' }}" 
                                 alt="{{ $property->property_name }}"
                                 class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-500"
                                 data-property-type="{{ $property->type }}"
                                 loading="lazy">
                            
                            <!-- Overlay with Quick Actions -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ number_format($property->total_area) }} m²
                                    </span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('properties.show', $property) }}" 
                                           class="p-2 bg-white/20 hover:bg-white/30 rounded-full text-white transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('properties.edit', $property) }}" 
                                           class="p-2 bg-white/20 hover:bg-white/30 rounded-full text-white transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badges -->
                            <div class="absolute top-4 left-4 right-4 flex justify-between items-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $property->unit_for === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $property->unit_for === 'sale' ? __('For Sale') : __('For Rent') }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $property->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $property->status === 'sold' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $property->status === 'rented' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $property->status === 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ __(ucfirst($property->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="p-4">
                            <!-- Title and Price -->
                            <div class="mb-3">
                                <h3 class="font-semibold text-gray-800 mb-1 truncate">
                                    <a href="{{ route('properties.show', $property) }}" class="hover:text-blue-600">
                                        {{ $property->property_name }}
                                    </a>
                                </h3>
                                <p class="text-lg font-bold text-blue-600">
                                    {{ number_format($property->total_price) }} {{ $property->currency }}
                                </p>
                            </div>

                            <!-- Location -->
                            @if($property->compound_name)
                                <p class="text-sm text-gray-600 mb-3 flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-1"></i>
                                    <span class="truncate">{{ $property->compound_name }}</span>
                                </p>
                            @endif

                            <!-- Features -->
                            <div class="flex items-center justify-between text-sm text-gray-600 border-t pt-3">
                                @if($property->rooms)
                                    <div class="flex items-center">
                                        <i class="fas fa-bed w-4 mr-1"></i>
                                        {{ $property->rooms }}
                                    </div>
                                @endif
                                
                                @if($property->bathrooms)
                                    <div class="flex items-center">
                                        <i class="fas fa-bath w-4 mr-1"></i>
                                        {{ $property->bathrooms }}
                                    </div>
                                @endif

                                <div class="flex items-center">
                                    <i class="fas fa-ruler w-4 mr-1"></i>
                                    {{ number_format($property->total_area) }} m²
                                </div>

                                <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                                    {{ __(ucfirst($property->type)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-home text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500">{{ __('No properties found matching your criteria.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Image loading fallback script -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fallback images for different property types
        const fallbackImages = {
            apartment: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
            villa: 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde',
            office: 'https://images.unsplash.com/photo-1497366216548-37526070297c',
            retail: 'https://images.unsplash.com/photo-1582407947304-fd86f028f716',
            default: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750'
        };

        // Handle image loading errors
        document.querySelectorAll('.property-image').forEach(img => {
            img.onerror = function() {
                const propertyType = this.dataset.propertyType || 'default';
                this.src = fallbackImages[propertyType] || fallbackImages.default;
            };
        });
    });
</script>
@endpush
