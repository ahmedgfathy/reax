<!-- comments -->
<x-app-layout>
    <x-slot name="header">{{ __('Properties Management') }}</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
        <!-- Total Properties Card -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-home text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Total Properties') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('All listed properties') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['total'] }}</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Total') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Properties Card -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-check-circle text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Available') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Currently available') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['available'] }}</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Active') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Properties Card -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-star text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Featured') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Featured properties') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['featured'] }}</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Featured') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <form action="{{ route('properties.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="{{ __('Search properties...') }}">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Property Type Filter -->
                    <div class="relative">
                        <select name="type" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach(['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land'] as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ __(ucfirst($type)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Status') }}</option>
                            @foreach(['available', 'sold', 'rented', 'reserved'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ __(ucfirst($status)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Sort By Filter -->
                    <div class="relative">
                        <select name="sort" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            {{ __('Apply Filters') }}
                        </button>
                        @if(request()->anyFilled(['search', 'type', 'status', 'sort']))
                            <a href="{{ route('properties.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <!-- Import Button -->
                        <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')" 
                                class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-file-import"></i>
                            {{ __('Import') }}
                        </button>
                        
                        <!-- Export Button -->
                        <button type="button" onclick="document.getElementById('export-modal').classList.remove('hidden')" 
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-file-export"></i>
                            {{ __('Export') }}
                        </button>
                        
                        <!-- Add Property Button -->
                        <a href="{{ route('properties.create') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Property') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
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
            <div class="mt-8 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    @if ($properties->previousPageUrl())
                        <a href="{{ $properties->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            {{ __('Previous') }}
                        </a>
                    @endif
                    @if ($properties->hasMorePages())
                        <a href="{{ $properties->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            {{ __('Next') }}
                        </a>
                    @endif
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ $properties->firstItem() ?? 0 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ $properties->lastItem() ?? 0 }}</span>
                            {{ __('of') }}
                            <span class="font-medium">{{ $properties->total() }}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($properties->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 rounded-l-md">
                                    <span class="sr-only">{{ __('Previous') }}</span>
                                    <i class="fas fa-chevron-left w-5 h-5"></i>
                                </span>
                            @else
                                <a href="{{ $properties->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-gray-600 ring-1 ring-inset ring-gray-300 rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">{{ __('Previous') }}</span>
                                    <i class="fas fa-chevron-left w-5 h-5"></i>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                                @if ($page == $properties->currentPage())
                                    <span class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($properties->hasMorePages())
                                <a href="{{ $properties->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-gray-600 ring-1 ring-inset ring-gray-300 rounded-r-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">{{ __('Next') }}</span>
                                    <i class="fas fa-chevron-right w-5 h-5"></i>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 rounded-r-md">
                                    <span class="sr-only">{{ __('Next') }}</span>
                                    <i class="fas fa-chevron-right w-5 h-5"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Import Modal -->
    <div id="import-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Import Properties') }}</h3>
                <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('properties.import') }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Upload CSV or Excel file') }}</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-file-excel text-gray-400 text-3xl mb-3"></i>
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>{{ __('Upload a file') }}</span>
                                <input id="file-upload" name="file" type="file" accept=".csv, .xls, .xlsx" class="sr-only" required>
                            </label>
                            <p class="pl-1">{{ __('or drag and drop') }}</p>
                            <p class="text-xs text-gray-500">{{ __('CSV, Excel files up to 10MB') }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-6">
                    <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" 
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-file-import mr-2"></i>{{ __('Import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="export-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Export Properties') }}</h3>
                <button onclick="document.getElementById('export-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('properties.export') }}" method="POST" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select export format') }}</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="format" value="csv" class="sr-only" checked>
                            <i class="fas fa-file-csv text-gray-700 text-2xl"></i>
                            <span class="text-gray-900 font-medium">CSV</span>
                            <span class="text-xs text-gray-500">{{ __('Comma separated') }}</span>
                        </label>
                        <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="format" value="xlsx" class="sr-only">
                            <i class="fas fa-file-excel text-gray-700 text-2xl"></i>
                            <span class="text-gray-900 font-medium">Excel</span>
                            <span class="text-xs text-gray-500">{{ __('XLSX format') }}</span>
                        </label>
                        <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="format" value="pdf" class="sr-only">
                            <i class="fas fa-file-pdf text-gray-700 text-2xl"></i>
                            <span class="text-gray-900 font-medium">PDF</span>
                            <span class="text-xs text-gray-500">{{ __('Portable format') }}</span>
                        </label>
                    </div>
                </div>
                <div class="text-right mt-6">
                    <button type="button" onclick="document.getElementById('export-modal').classList.add('hidden')" 
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-file-export mr-2"></i>{{ __('Export') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <!-- Image loading fallback script -->
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
</x-app-layout>
