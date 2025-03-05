<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Add New Property') }}</title>
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
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Add New Property') }}</h1>
                <a href="{{ route('properties.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Properties') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Title') }}</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full p-2 border rounded-md @error('title') border-red-500 @enderror" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reference_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Reference ID') }}</label>
                            <input type="text" id="reference_id" name="reference_id" value="{{ old('reference_id') }}" class="w-full p-2 border rounded-md @error('reference_id') border-red-500 @enderror">
                            @error('reference_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Type') }}</label>
                            <select id="type" name="type" class="w-full p-2 border rounded-md @error('type') border-red-500 @enderror" required>
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                <option value="house" {{ old('type') == 'house' ? 'selected' : '' }}>{{ __('House') }}</option>
                                <option value="land" {{ old('type') == 'land' ? 'selected' : '' }}>{{ __('Land') }}</option>
                                <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                                <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                            <select id="status" name="status" class="w-full p-2 border rounded-md @error('status') border-red-500 @enderror" required>
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="for_sale" {{ old('status') == 'for_sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                                <option value="for_rent" {{ old('status') == 'for_rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>{{ __('Rented') }}</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price') }}</label>
                            <div class="flex">
                                <input type="number" id="price" name="price" value="{{ old('price') }}" class="w-2/3 p-2 border rounded-l-md @error('price') border-red-500 @enderror" required>
                                <select id="currency" name="currency" class="w-1/3 p-2 border border-l-0 rounded-r-md @error('currency') border-red-500 @enderror">
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                    <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>AED</option>
                                </select>
                            </div>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Featured Image') }}</label>
                            <input type="file" id="featured_image" name="featured_image" class="w-full p-2 border rounded-md @error('featured_image') border-red-500 @enderror">
                            @error('featured_image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Additional Images') }}</label>
                            <input type="file" id="images" name="images[]" multiple class="w-full p-2 border rounded-md @error('images') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">{{ __('You can select multiple images') }}</p>
                            @error('images')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Property Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Property Details') }}</h3>
                        
                        <div class="mb-4">
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bedrooms') }}</label>
                            <input type="number" id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}" min="0" class="w-full p-2 border rounded-md @error('bedrooms') border-red-500 @enderror">
                            @error('bedrooms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bathrooms') }}</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" min="0" step="0.5" class="w-full p-2 border rounded-md @error('bathrooms') border-red-500 @enderror">
                            @error('bathrooms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="area" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Area (m²)') }}</label>
                            <input type="number" id="area" name="area" value="{{ old('area') }}" min="0" class="w-full p-2 border rounded-md @error('area') border-red-500 @enderror" required>
                            @error('area')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="year_built" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Year Built') }}</label>
                            <input type="number" id="year_built" name="year_built" value="{{ old('year_built') }}" min="1900" max="{{ date('Y') }}" class="w-full p-2 border rounded-md @error('year_built') border-red-500 @enderror">
                            @error('year_built')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" class="w-full p-2 border rounded-md @error('location') border-red-500 @enderror" required>
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="5" class="w-full p-2 border rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Features & Amenities') }}</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_pool" name="features[]" value="pool" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('pool', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_pool" class="ml-2 block text-sm text-gray-700">{{ __('Swimming Pool') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_garage" name="features[]" value="garage" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('garage', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_garage" class="ml-2 block text-sm text-gray-700">{{ __('Garage') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_garden" name="features[]" value="garden" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('garden', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_garden" class="ml-2 block text-sm text-gray-700">{{ __('Garden') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_balcony" name="features[]" value="balcony" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('balcony', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_balcony" class="ml-2 block text-sm text-gray-700">{{ __('Balcony') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_ac" name="features[]" value="air_conditioning" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('air_conditioning', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_ac" class="ml-2 block text-sm text-gray-700">{{ __('Air Conditioning') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_heating" name="features[]" value="heating" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('heating', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_heating" class="ml-2 block text-sm text-gray-700">{{ __('Heating') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_elevator" name="features[]" value="elevator" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('elevator', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_elevator" class="ml-2 block text-sm text-gray-700">{{ __('Elevator') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_security" name="features[]" value="security" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('security', old('features', [])) ? 'checked' : '' }}>
                            <label for="feature_security" class="ml-2 block text-sm text-gray-700">{{ __('Security System') }}</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Agent Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="agent_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Agent Name') }}</label>
                                <input type="text" id="agent_name" name="agent_name" value="{{ old('agent_name') }}" class="w-full p-2 border rounded-md @error('agent_name') border-red-500 @enderror">
                                @error('agent_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="agent_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Agent Phone') }}</label>
                                <input type="text" id="agent_phone" name="agent_phone" value="{{ old('agent_phone') }}" class="w-full p-2 border rounded-md @error('agent_phone') border-red-500 @enderror">
                                @error('agent_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <label for="agent_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Agent Email') }}</label>
                                <input type="email" id="agent_email" name="agent_email" value="{{ old('agent_email') }}" class="w-full p-2 border rounded-md @error('agent_email') border-red-500 @enderror">
                                @error('agent_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Assigned To') }}</label>
                                <select id="assigned_to" name="assigned_to" class="w-full p-2 border rounded-md @error('assigned_to') border-red-500 @enderror">
                                    <option value="">{{ __('Not Assigned') }}</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Create Property') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>