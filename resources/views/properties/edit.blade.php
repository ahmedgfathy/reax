<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Edit Property') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Property') }}: {{ $property->name }}</h1>
                <a href="{{ route('properties.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Properties') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('properties.update', $property->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $property->name) }}" class="w-full p-2 border rounded-md @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
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

                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $property->location) }}" class="w-full p-2 border rounded-md @error('location') border-red-500 @enderror" required>
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="4" class="w-full p-2 border rounded-md @error('description') border-red-500 @enderror">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Property Details') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price') }}</label>
                                <input type="number" id="price" name="price" value="{{ old('price', $property->price) }}" class="w-full p-2 border rounded-md @error('price') border-red-500 @enderror" required>
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
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
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="size" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Size (m²)') }}</label>
                                <input type="number" id="size" name="size" value="{{ old('size', $property->size) }}" class="w-full p-2 border rounded-md @error('size') border-red-500 @enderror" required>
                                @error('size')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="rooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rooms') }}</label>
                                <input type="number" id="rooms" name="rooms" value="{{ old('rooms', $property->rooms) }}" class="w-full p-2 border rounded-md @error('rooms') border-red-500 @enderror" required>
                                @error('rooms')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bathrooms') }}</label>
                                <input type="number" id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" class="w-full p-2 border rounded-md @error('bathrooms') border-red-500 @enderror" required>
                                @error('bathrooms')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Owner Name') }}</label>
                                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $property->owner_name) }}" class="w-full p-2 border rounded-md @error('owner_name') border-red-500 @enderror">
                                @error('owner_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
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
                
                <div class="mt-6 border-t pt-4 flex justify-end space-x-3">
                    <a href="{{ route('properties.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Update Property') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
