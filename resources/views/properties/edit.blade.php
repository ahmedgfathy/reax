<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ __('Edit Property') }}</h2>
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('properties.index') }}" class="hover:text-gray-700">{{ __('Properties') }}</a>
                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-700">{{ $property->property_name }}</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('properties.show', $property) }}" class="btn-secondary">
                    <i class="fas fa-eye mr-2"></i>
                    {{ __('View Property') }}
                </a>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-form.input 
                        name="property_name" 
                        :label="__('Property Name')" 
                        :value="old('property_name', $property->property_name)"
                        required 
                    />

                    <x-form.input 
                        name="property_number" 
                        :label="__('Reference Number')"
                        :value="$property->property_number"
                        disabled
                    />

                    <x-form.select 
                        name="unit_for" 
                        :label="__('Purpose')"
                        :options="['sale' => __('For Sale'), 'rent' => __('For Rent')]"
                        :selected="old('unit_for', $property->unit_for)"
                        required
                    />

                    <x-form.select 
                        name="category" 
                        :label="__('Category')"
                        :options="['residential' => __('Residential'), 'commercial' => __('Commercial'), 'administrative' => __('Administrative')]"
                        :selected="old('category', $property->category)"
                    />

                    <x-form.select 
                        name="location_type" 
                        :label="__('Location Type')"
                        :options="['inside' => __('Inside'), 'outside' => __('Outside')]"
                        :selected="old('location_type', $property->location_type)"
                    />

                    <x-form.select 
                        name="type" 
                        :label="__('Property Type')"
                        :options="['Apartment' => __('Apartment'), 'House' => __('House'), 'Villa' => __('Villa'), 'Condo' => __('Condo'), 'Penthouse' => __('Penthouse')]"
                        :selected="old('type', $property->type)"
                        required
                    />

                    <x-form.input 
                        name="location" 
                        :label="__('Location')" 
                        :value="old('location', $property->location)"
                        required 
                    />

                    <x-form.textarea 
                        name="description" 
                        :label="__('Description')" 
                        :value="old('description', $property->description)"
                    />
                </div>
            </div>
        </div>

        <!-- Areas and Specifications -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Areas and Specifications') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-form.input 
                        name="price" 
                        :label="__('Price')" 
                        :value="old('price', $property->price)"
                        type="number"
                        required 
                    />

                    <x-form.select 
                        name="currency" 
                        :label="__('Currency')"
                        :options="['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP']"
                        :selected="old('currency', $property->currency)"
                        required
                    />

                    <x-form.input 
                        name="size" 
                        :label="__('Size (m²)')" 
                        :value="old('size', $property->size)"
                        type="number"
                        required 
                    />

                    <x-form.input 
                        name="rooms" 
                        :label="__('Rooms')" 
                        :value="old('rooms', $property->rooms)"
                        type="number"
                        required 
                    />

                    <x-form.input 
                        name="bathrooms" 
                        :label="__('Bathrooms')" 
                        :value="old('bathrooms', $property->bathrooms)"
                        type="number"
                        required 
                    />

                    <x-form.input 
                        name="owner_name" 
                        :label="__('Owner Name')" 
                        :value="old('owner_name', $property->owner_name)"
                    />

                    <x-form.input 
                        name="image" 
                        :label="__('Image URL')" 
                        :value="old('image', $property->image)"
                        type="url"
                        placeholder="https://example.com/image.jpg"
                    />

                    @if($property->image)
                        <div class="mt-2">
                            <img src="{{ $property->image }}" alt="{{ $property->name }}" class="w-40 h-auto rounded-md">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Features and Description -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Features and Description') }}</h3>
                <div class="mb-4">
                    <x-form.select 
                        name="features" 
                        :label="__('Features')" 
                        :options="array_combine($features, $features)"
                        :selected="old('features', $property->features ?? [])"
                        multiple
                    />
                </div>

                <div class="mb-4">
                    <x-form.select 
                        name="amenities" 
                        :label="__('Amenities')" 
                        :options="array_combine($amenities, $amenities)"
                        :selected="old('amenities', $property->amenities ?? [])"
                        multiple
                    />
                </div>
            </div>
        </div>

        <!-- Media Upload -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Property Media') }}</h3>
                <div class="space-y-4">
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
        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center sticky bottom-0 border-t">
            <button type="button" onclick="history.back()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Back') }}
            </button>
            <div class="flex space-x-2">
                <button type="submit" name="action" value="save" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Save Changes') }}
                </button>
                <button type="submit" name="action" value="save_and_continue" class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    {{ __('Save & Continue') }}
                </button>
            </div>
        </div>
    </form>
</x-app-layout>
