@extends('layouts.app')

@section('content')
<!-- Properties Header - Styled exactly like Leads Header -->
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Properties Management') }}</h1>
            <div class="flex space-x-2">
                <!-- Import Button - With inline SVG icon as backup -->
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <span class="import-icon-container mr-2">
                        <i class="fas fa-file-import"></i>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden fallback-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('Import') }}
                </button>
                
                <!-- Export Button - With inline SVG icon as backup -->
                <button onclick="document.getElementById('exportModal').classList.remove('hidden')"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <span class="export-icon-container mr-2">
                        <i class="fas fa-file-export"></i>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden fallback-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('Export') }}
                </button>
                
                <!-- Add Property Button - With inline SVG icon as backup -->
                <a href="{{ route('properties.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <span class="add-icon-container mr-2">
                        <i class="fas fa-plus"></i>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden fallback-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('Add Property') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <!-- Property Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search input -->
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" placeholder="{{ __('Search properties...') }}" 
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           title="{{ __('Search by name, address, price, owner name, contact or unit number') }}">
                </div>
            </div>
            
            <!-- Property type filter -->
            <div>
                <select name="type" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach($propertyTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ __(ucfirst($type)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status filter (rent/sale) -->
            <div>
                <select name="status" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="rent" {{ request('status') == 'rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                    <option value="sale" {{ request('status') == 'sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                </select>
            </div>
            
            <div class="md:col-span-3">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1">
                        <span class="text-sm font-medium text-gray-700">{{ __('Price Range:') }}</span>
                        <input type="number" name="min_price" placeholder="{{ __('Min') }}" 
                               value="{{ request('min_price') }}"
                               class="w-24 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="{{ __('Max') }}" 
                               value="{{ request('max_price') }}"
                               class="w-24 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <select name="per_page" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                            <option value="12" {{ request('per_page') == 12 || !request('per_page') ? 'selected' : '' }}>{{ __('Show 12') }}</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>{{ __('Show 24') }}</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>{{ __('Show 48') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md w-full flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i> {{ __('Filter') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Properties Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($properties as $property)
        <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow relative group">
            <!-- Card Container - Made clickable with relative and group classes -->
            <a href="{{ route('properties.show', $property->id) }}" class="block">
                <div class="relative">
                    <!-- Property Image -->
                    @php
                        $imageUrl = null;
                        // Try to find a featured media file
                        if ($property->mediaFiles && $property->mediaFiles->count() > 0) {
                            $featuredMedia = $property->mediaFiles->where('is_featured', true)->first();
                            if ($featuredMedia) {
                                $imageUrl = asset('storage/' . $featuredMedia->file_path);
                            } else {
                                // If no featured image, use the first one
                                $imageUrl = asset('storage/' . $property->mediaFiles->first()->file_path);
                            }
                        }

                        // If no media files, use a default image based on property type
                        if (!$imageUrl) {
                            $type = strtolower($property->type ?? 'default');
                            if ($type == 'apartment') {
                                $imageUrl = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                            } elseif ($type == 'villa') {
                                $imageUrl = 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                            } elseif ($type == 'office') {
                                $imageUrl = 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                            } else {
                                // Default fallback
                                $imageUrl = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                            }
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $property->name }}" class="w-full h-56 object-cover">
                    
                    <!-- Featured Heart Icon - Positioned outside the main link for independent clicking -->
                    <button 
                        data-property-id="{{ $property->id }}" 
                        class="featured-toggle absolute top-4 left-4 transition-transform hover:scale-110 z-10"
                        onclick="event.preventDefault();"
                    >
                        @if($property->is_featured)
                            <i class="fas fa-heart text-xl text-red-500 drop-shadow-md"></i>
                        @else
                            <i class="far fa-heart text-xl text-white drop-shadow-md"></i>
                        @endif
                    </button>
                    
                    <!-- Property Status Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="bg-{{ $property->unit_for == 'sale' ? 'blue' : 'green' }}-600 text-white px-3 py-1 rounded-full text-sm">
                            {{ $property->unit_for == 'sale' ? __('For Sale') : __('For Rent') }}
                        </span>
                    </div>
                    
                    <!-- Property Price -->
                    <div class="absolute bottom-4 right-4">
                        <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ number_format($property->price) }} {{ $property->currency ?? 'USD' }}
                        </span>
                    </div>
                </div>
                
                <div class="p-5">
                    <!-- Property Name - Consistent Height & Clickable -->
                    <h3 class="text-lg font-bold text-gray-900 mb-2 h-14">
                        <a href="{{ route('properties.show', $property->id) }}" class="hover:text-blue-600 line-clamp-2 block">
                            {{ $property->name }}
                        </a>
                    </h3>
                    
                    <!-- Location -->
                    <p class="text-gray-600 mb-3 flex items-center h-6 overflow-hidden">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600 flex-shrink-0"></i>
                        <span class="truncate">{{ $property->area ?? $property->location ?? __('Location not specified') }}</span>
                    </p>
                    
                    <!-- Owner Information - Added -->
                    <div class="mb-3 bg-gray-50 p-2 rounded-lg text-sm">
                        <p class="flex items-center text-gray-700 mb-1">
                            <i class="fas fa-user mr-2 text-gray-500 w-5"></i>
                            <span class="truncate">{{ $property->owner_name ?? __('Owner not specified') }}</span>
                        </p>
                        @if($property->owner_mobile)
                        <p class="flex items-center text-gray-700">
                            <i class="fas fa-phone mr-2 text-gray-500 w-5"></i>
                            <span>{{ $property->owner_mobile }}</span>
                        </p>
                        @endif
                    </div>
                    
                    <!-- Property Specs - Consistently Aligned -->
                    <div class="grid grid-cols-3 gap-2 text-gray-600 border-t pt-3 mb-2 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-bed mb-1"></i>
                            <span class="text-sm">{{ $property->rooms ?? $property->bedrooms ?? 0 }}</span>
                            <span class="text-xs text-gray-500">{{ __('Beds') }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fas fa-bath mb-1"></i>
                            <span class="text-sm">{{ $property->bathrooms ?? 0 }}</span>
                            <span class="text-xs text-gray-500">{{ __('Baths') }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fas fa-ruler-combined mb-1"></i>
                            <span class="text-sm">{{ $property->unit_area ?? $property->area_size ?? 0 }}</span>
                            <span class="text-xs text-gray-500">{{ __('m²') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            
            <!-- Action Buttons - Appear on hover, positioned outside main link -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-gray-900 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity flex justify-center space-x-4">
                <a href="{{ route('properties.edit', $property->id) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 p-2 rounded-full" title="{{ __('Edit') }}">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this property?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-white bg-red-500 hover:bg-red-600 p-2 rounded-full" title="{{ __('Delete') }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-8 text-center text-gray-500">
            <i class="fas fa-home text-4xl mb-3 opacity-30"></i>
            <p>{{ __('No properties found matching your criteria.') }}</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $properties->links() }}
    </div>
    
    <!-- Import Modal (Hidden by default) -->
    <div id="importModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Import Properties') }}</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
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
                            <p class="text-xs text-gray-500">
                                {{ __('CSV, Excel files up to 10MB') }}
                            </p>
                            <div id="file-name" class="text-sm text-gray-800 font-medium mt-2 hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Options') }}</label>
                    <div class="mt-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="header_row" name="header_row" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="header_row" class="ml-2 block text-sm text-gray-700">
                                {{ __('File contains header row') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-6">
                    <a href="{{ asset('templates/properties_import_template.xlsx') }}" download class="text-blue-600 hover:underline text-sm mr-4">
                        <i class="fas fa-download mr-1"></i> {{ __('Download template') }}
                    </a>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-file-import mr-2"></i> {{ __('Import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Modal (Hidden by default) -->
    <div id="exportModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Export Properties') }}</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
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
                            <div class="w-full h-1 bg-blue-600 rounded-full mt-1"></div>
                        </label>
                        
                        <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="format" value="xlsx" class="sr-only">
                            <i class="fas fa-file-excel text-gray-700 text-2xl"></i>
                            <span class="text-gray-900 font-medium">Excel</span>
                            <span class="text-xs text-gray-500">{{ __('XLSX format') }}</span>
                            <div class="w-full h-1 bg-gray-200 rounded-full mt-1"></div>
                        </label>
                        
                        <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="format" value="pdf" class="sr-only">
                            <i class="fas fa-file-pdf text-gray-700 text-2xl"></i>
                            <span class="text-gray-900 font-medium">PDF</span>
                            <span class="text-xs text-gray-500">{{ __('Portable format') }}</span>
                            <div class="w-full h-1 bg-gray-200 rounded-full mt-1"></div>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('What to export') }}</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="radio" id="export_all" name="export_scope" value="all" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="export_all" class="ml-2 block text-sm text-gray-700">
                                {{ __('All properties') }}
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="export_filtered" name="export_scope" value="filtered" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="export_filtered" class="ml-2 block text-sm text-gray-700">
                                {{ __('Current filtered results') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-6">
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-file-export mr-2"></i> {{ __('Export') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@include('components.layouts.alert-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to all featured toggle buttons
        const featuredButtons = document.querySelectorAll('.featured-toggle');
        
        featuredButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const propertyId = this.dataset.propertyId;
                const heartIcon = this.querySelector('i');
                
                // Send AJAX request to toggle featured status
                fetch(`/properties/${propertyId}/toggle-featured`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI based on response
                        if (data.is_featured) {
                            heartIcon.classList.remove('far');
                            heartIcon.classList.add('fas');
                            heartIcon.classList.add('text-red-500');
                            heartIcon.classList.remove('text-white');
                            // Show success notification
                            alert('Property added to featured list! It will now appear on the home page.');
                        } else {
                            heartIcon.classList.remove('fas');
                            heartIcon.classList.add('far');
                            heartIcon.classList.remove('text-red-500');
                            heartIcon.classList.add('text-white');
                            // Show removal notification
                            alert('Property removed from featured list.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });

        // File upload handling for import
        const fileInput = document.getElementById('file-upload');
        const fileNameDisplay = document.getElementById('file-name');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileNameDisplay.textContent = this.files[0].name;
                    fileNameDisplay.classList.remove('hidden');
                } else {
                    fileNameDisplay.classList.add('hidden');
                }
            });
        }

        // Handle format selection in export modal
        const formatRadios = document.querySelectorAll('input[name="format"]');
        if (formatRadios.length) {
            formatRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove highlight from all labels
                    document.querySelectorAll('input[name="format"]').forEach(r => {
                        r.closest('label').querySelector('div').classList.remove('bg-blue-600');
                        r.closest('label').querySelector('div').classList.add('bg-gray-200');
                    });
                    
                    // Add highlight to selected label
                    if (this.checked) {
                        this.closest('label').querySelector('div').classList.remove('bg-gray-200');
                        this.closest('label').querySelector('div').classList.add('bg-blue-600');
                    }
                });
            });
        }

        // Replace all delete form submissions with SweetAlert2
        document.querySelectorAll('form[action^="{{ route("properties.destroy", "") }}"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                const propertyName = this.closest('tr').querySelector('a[href^="{{ route("properties.show", "") }}"]').textContent.trim();
                
                window.confirmDelete({
                    title: '{{ __("Delete Property") }}',
                    text: `{{ __("Are you sure you want to delete") }} ${propertyName}? {{ __("This action cannot be undone.") }}`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: '{{ __("Deleting...") }}',
                            html: '{{ __("Please wait") }}',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
