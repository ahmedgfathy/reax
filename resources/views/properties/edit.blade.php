<x-app-layout>
    <x-slot name="header">{{ __('Edit Property') }}</x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Property Name -->
                        <div>
                            <label for="property_name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Property Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="property_name" name="property_name" 
                                   value="{{ old('property_name', $property->property_name) }}" 
                                   class="form-input rounded-md shadow-sm w-full" required>
                        </div>

                        <!-- Compound Name -->
                        <div>
                            <label for="compound_name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Compound Name') }}
                            </label>
                            <input type="text" id="compound_name" name="compound_name" 
                                   value="{{ old('compound_name', $property->compound_name) }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Property Number -->
                        <div>
                            <label for="property_number" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Property Number') }}
                            </label>
                            <input type="text" id="property_number" name="property_number" 
                                   value="{{ old('property_number', $property->property_number) }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Unit For -->
                        <div>
                            <label for="unit_for" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Unit For') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="unit_for" name="unit_for" class="form-select rounded-md shadow-sm w-full" required>
                                <option value="">{{ __('Select...') }}</option>
                                <option value="sale" {{ old('unit_for', $property->unit_for) == 'sale' ? 'selected' : '' }}>{{ __('Sale') }}</option>
                                <option value="rent" {{ old('unit_for', $property->unit_for) == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Category') }}
                            </label>
                            <select id="category" name="category" class="form-select rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select...') }}</option>
                                @foreach(['residential', 'commercial', 'administrative'] as $category)
                                    <option value="{{ $category }}" {{ old('category', $property->category) == $category ? 'selected' : '' }}>
                                        {{ __(ucfirst($category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Type -->
                        <div>
                            <label for="location_type" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Location Type') }}
                            </label>
                            <select id="location_type" name="location_type" class="form-select rounded-md shadow-sm w-full">
                                <option value="inside" {{ old('location_type', $property->location_type) == 'inside' ? 'selected' : '' }}>{{ __('Inside') }}</option>
                                <option value="outside" {{ old('location_type', $property->location_type) == 'outside' ? 'selected' : '' }}>{{ __('Outside') }}</option>
                            </select>
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Type') }}</label>
                            <select id="type" name="type" class="w-full p-2 border rounded-md @error('type') border-red-500 @enderror" required>
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="Apartment" {{ old('type', $property->type) == 'Apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                <option value="House" {{ old('type', $property->type) == 'House' ? 'selected' : '' }}>{{ __('House') }}</option>
                                <option value="Villa" {{ old('type', $property->type) == 'Villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                <option value="Condo" {{ old('type', $property->type) == 'Condo' ? 'selected' : '' }}>{{ __('Condo') }}</option>
                                <option value="Penthouse" {{ old('type', $property->type) == 'Penthouse' ? 'selected' : '' }}>{{ __('Penthouse') }}</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $property->location) }}" class="w-full p-2 border rounded-md @error('location') border-red-500 @enderror" required>
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="4" class="w-full p-2 border rounded-md @error('description') border-red-500 @enderror">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Areas and Specifications Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Areas and Specifications') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Copy all area fields from create.blade.php -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price') }}</label>
                            <input type="number" id="total_price" name="total_price" value="{{ old('total_price', $property->total_price) }}" class="w-full p-2 border rounded-md @error('total_price') border-red-500 @enderror" required>
                            @error('total_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Currency') }}</label>
                            <select id="currency" name="currency" class="w-full p-2 border rounded-md @error('currency') border-red-500 @enderror" required>
                                <option value="USD" {{ old('currency', $property->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $property->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency', $property->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                            @error('currency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Size (m²)') }}</label>
                            <input type="number" id="size" name="size" value="{{ old('size', $property->size) }}" class="w-full p-2 border rounded-md @error('size') border-red-500 @enderror" required>
                            @error('size')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rooms') }}</label>
                            <input type="number" id="rooms" name="rooms" value="{{ old('rooms', $property->rooms) }}" class="w-full p-2 border rounded-md @error('rooms') border-red-500 @enderror" required>
                            @error('rooms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bathrooms') }}</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" class="w-full p-2 border rounded-md @error('bathrooms') border-red-500 @enderror" required>
                            @error('bathrooms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Owner Name') }}</label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $property->owner_name) }}" class="w-full p-2 border rounded-md @error('owner_name') border-red-500 @enderror">
                            @error('owner_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image URL') }}</label>
                            <input type="url" id="image" name="image" value="{{ old('image', $property->image) }}" placeholder="https://example.com/image.jpg" class="w-full p-2 border rounded-md @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            @if($property->image)
                                <div class="mt-2">
                                    <img src="{{ $property->image }}" alt="{{ $property->name }}" class="w-40 h-auto rounded-md">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features and Description Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Features and Description') }}</h3>
                    
                    <!-- Features -->
                    <div class="mb-4">
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Features') }}
                        </label>
                        <select id="features" name="features[]" multiple class="form-multiselect rounded-md shadow-sm w-full">
                            @foreach($features as $feature)
                                <option value="{{ $feature }}" 
                                    {{ in_array($feature, old('features', json_decode($property->features) ?? [])) ? 'selected' : '' }}>
                                    {{ $feature }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-4">
                        <label for="amenities" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Amenities') }}
                        </label>
                        <select id="amenities" name="amenities[]" multiple class="form-multiselect rounded-md shadow-sm w-full">
                            @foreach($amenities as $amenity)
                                <option value="{{ $amenity }}" 
                                    {{ in_array($amenity, old('amenities', json_decode($property->amenities) ?? [])) ? 'selected' : '' }}>
                                    {{ $amenity }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Media Upload Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Property Media') }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Current Images -->
                        @if($property->media->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach($property->media as $media)
                            <div class="relative group">
                                <img src="{{ $media->file_path }}" class="w-full h-32 object-cover rounded">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full"
                                            onclick="deleteMedia({{ $media->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @if(!$media->is_featured)
                                    <button type="button" class="absolute bottom-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-md text-sm"
                                            onclick="setFeatured({{ $media->id }})">
                                        {{ __('Set as Featured') }}
                                    </button>
                                    @else
                                    <span class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded-md text-sm">
                                        {{ __('Featured') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- New Images Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Upload New Images') }}
                            </label>
                            <input type="file" name="images[]" multiple accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0 file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-sm text-gray-500 mt-1">{{ __('You can select multiple images') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('properties.show', $property) }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ __('Update Property') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function deleteMedia(mediaId) {
            if (confirm('{{ __("Are you sure you want to delete this image?") }}')) {
                fetch(`/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }

        function setFeatured(mediaId) {
            if (confirm('{{ __("Set this image as featured?") }}')) {
                fetch(`/media/${mediaId}/featured`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
    @endpush
</x-app-layout>
