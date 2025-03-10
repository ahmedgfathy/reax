<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Properties Management') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    <!-- Header Menu -->
    @include('components.header-menu')

    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-wrap items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Properties') }}</h1>
            
            <div class="flex space-x-2 mt-4 md:mt-0">
                <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('Add Property') }}
                </a>
                
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center" onclick="document.getElementById('import-modal').classList.remove('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    {{ __('Import') }}
                </button>
                
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center" onclick="document.getElementById('export-modal').classList.remove('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ __('Export') }}
                </button>
            </div>
        </div>
        
        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4">
                <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" id="search" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('search') }}">
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }}</label>
                        <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">{{ __('Filter') }}</button>
                        <a href="{{ route('properties.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Properties Cards Grid -->
        <div class="mb-6">
            <!-- View Toggle Buttons (Optional) -->
            <div class="flex justify-end mb-4">
                <div class="inline-flex rounded-md shadow-sm">
                    <button type="button" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-l-lg">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button type="button" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-r-lg">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($properties as $property)
                        <!-- Property Card -->
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 relative">
                            <!-- Property Image -->
                            <div class="relative h-48">
                                <img class="w-full h-full object-cover" src="{{ $property->getImageUrlAttribute() }}" alt="{{ $property->name }}">
                                
                                <!-- Property Status Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $property->status == 'available' ? 'bg-green-500 text-white' : 
                                           ($property->status == 'pending' ? 'bg-yellow-500 text-white' : 
                                            'bg-blue-500 text-white') }}">
                                        {{ ucfirst($property->status ?? 'Unknown') }}
                                    </span>
                                </div>
                                
                                <!-- Property Type Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/80 backdrop-blur-sm text-gray-700">
                                        {{ $property->unit_for == 'rent' ? __('For Rent') : __('For Sale') }}
                                    </span>
                                </div>
                                
                                <!-- Featured Badge if applicable -->
                                @if($property->is_featured)
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white flex items-center">
                                        <i class="fas fa-star mr-1"></i> {{ __('Featured') }}
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Price Badge -->
                                <div class="absolute bottom-3 right-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                                        {{ number_format($property->price) }} {{ $property->currency }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Property Details -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $property->name }}</h3>
                                
                                <div class="flex items-center text-gray-500 text-sm mb-3">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span class="truncate">{{ $property->location ?? $property->area ?? __('Not specified') }}</span>
                                </div>
                                
                                <!-- Property Specs -->
                                <div class="flex justify-between text-sm text-gray-600 border-t border-gray-100 pt-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-bed mr-1"></i>
                                        <span>{{ $property->rooms ?? $property->bedrooms ?? '0' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-bath mr-1"></i>
                                        <span>{{ $property->bathrooms ?? '0' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-1"></i>
                                        <span>{{ $property->unit_area ?? $property->area_size ?? '0' }} m²</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Footer with Actions -->
                            <div class="bg-gray-50 p-3 border-t border-gray-100 flex justify-between items-center">
                                <div>
                                    <span class="text-xs text-gray-500">{{ __('ID') }}: {{ $property->id }}</span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('properties.show', $property->id) }}" class="text-blue-600 hover:text-blue-800" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('properties.edit', $property->id) }}" class="text-indigo-600 hover:text-indigo-800" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('{{ $property->id }}')" class="text-red-600 hover:text-red-800" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <button onclick="toggleFeatured('{{ $property->id }}', this)" class="text-yellow-500 hover:text-yellow-700" title="{{ $property->is_featured ? __('Remove from featured') : __('Add to featured') }}">
                                        <i class="fas {{ $property->is_featured ? 'fa-star' : 'fa-star-o' }}"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <form id="delete-form-{{ $property->id }}" action="{{ route('properties.destroy', $property->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-home text-5xl mb-3"></i>
                        <h3 class="text-xl font-semibold">{{ __('No properties found') }}</h3>
                        <p>{{ __('Try adjusting your search or filter criteria') }}</p>
                    </div>
                    <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> {{ __('Add New Property') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Delete Property') }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">{{ __('Are you sure you want to delete this property? This action cannot be undone.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" id="confirm-delete-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Delete') }}
                        </button>
                        <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Import Modal -->
        <div id="import-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('properties.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('Import Properties') }}</h3>
                            <div class="mb-4">
                                <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select CSV or Excel file') }}</label>
                                <input type="file" id="import_file" name="import_file" required class="w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">{{ __('Only CSV and Excel files are accepted. Maximum size 5MB.') }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Import') }}
                            </button>
                            <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Export Modal -->
        <div id="export-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('properties.export') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('Export Properties') }}</h3>
                            <div class="mb-4">
                                <label for="export_format" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select Format') }}</label>
                                <select id="export_format" name="format" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="csv">CSV</option>
                                    <option value="xlsx">Excel</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Export') }}
                            </button>
                            <button type="button" onclick="document.getElementById('export-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        let propertyToDelete = null;

        function confirmDelete(id) {
            propertyToDelete = id;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            propertyToDelete = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (propertyToDelete) {
                document.getElementById(`delete-form-${propertyToDelete}`).submit();
            }
        });

        function toggleFeatured(propertyId, button) {
            fetch(`/properties/${propertyId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the star icon
                    if (data.is_featured) {
                        button.querySelector('i').classList.remove('fa-star-o');
                        button.querySelector('i').classList.add('fa-star');
                        button.title = "{{ __('Remove from featured') }}";
                    } else {
                        button.querySelector('i').classList.remove('fa-star');
                        button.querySelector('i').classList.add('fa-star-o');
                        button.title = "{{ __('Add to featured') }}";
                    }
                    
                    // Flash a subtle notification
                    const notification = document.createElement('div');
                    notification.className = "fixed bottom-4 right-4 bg-green-500 text-white rounded-lg px-4 py-2 shadow-lg";
                    notification.textContent = data.is_featured ? 
                        "{{ __('Property added to featured list!') }}" : 
                        "{{ __('Property removed from featured list.') }}";
                    
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
