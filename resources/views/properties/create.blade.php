<x-app-layout>
    <x-slot name="header">{{ __('Create New Property') }}</x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
                            <input type="text" id="property_name" name="property_name" value="{{ old('property_name') }}" 
                                   class="form-input rounded-md shadow-sm w-full" required>
                            @error('property_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Compound Name -->
                        <div>
                            <label for="compound_name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Compound Name') }}
                            </label>
                            <input type="text" id="compound_name" name="compound_name" value="{{ old('compound_name') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Property Number -->
                        <div>
                            <label for="property_number" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Property Number') }}
                            </label>
                            <input type="text" id="property_number" name="property_number" value="{{ old('property_number') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Unit Number -->
                        <div>
                            <label for="unit_no" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Unit Number') }}
                            </label>
                            <input type="text" id="unit_no" name="unit_no" value="{{ old('unit_no') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Unit For -->
                        <div>
                            <label for="unit_for" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Unit For') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="unit_for" name="unit_for" class="form-select rounded-md shadow-sm w-full" required>
                                <option value="">{{ __('Select...') }}</option>
                                <option value="sale" {{ old('unit_for') == 'sale' ? 'selected' : '' }}>{{ __('Sale') }}</option>
                                <option value="rent" {{ old('unit_for') == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                            </select>
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Property Type') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" class="form-select rounded-md shadow-sm w-full" required>
                                <option value="">{{ __('Select...') }}</option>
                                @foreach(['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land'] as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ __(ucfirst($type)) }}
                                    </option>
                                @endforeach
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
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ __(ucfirst($category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Status') }}
                            </label>
                            <select id="status" name="status" class="form-select rounded-md shadow-sm w-full">
                                @foreach(['available', 'sold', 'rented', 'reserved'] as $status)
                                    <option value="{{ $status }}" {{ old('status', 'available') == $status ? 'selected' : '' }}>
                                        {{ __(ucfirst($status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Phase -->
                        <div>
                            <label for="phase" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Phase') }}
                            </label>
                            <input type="text" id="phase" name="phase" value="{{ old('phase') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Building -->
                        <div>
                            <label for="building" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Building') }}
                            </label>
                            <input type="text" id="building" name="building" value="{{ old('building') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Floor -->
                        <div>
                            <label for="floor" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Floor') }}
                            </label>
                            <input type="text" id="floor" name="floor" value="{{ old('floor') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Location Type -->
                        <div>
                            <label for="location_type" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Location Type') }}
                            </label>
                            <select id="location_type" name="location_type" class="form-select rounded-md shadow-sm w-full">
                                <option value="inside" {{ old('location_type') == 'inside' ? 'selected' : '' }}>{{ __('Inside') }}</option>
                                <option value="outside" {{ old('location_type') == 'outside' ? 'selected' : '' }}>{{ __('Outside') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Areas and Specifications Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Areas and Specifications') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Total Area -->
                        <div>
                            <label for="total_area" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Total Area (m²)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" id="total_area" name="total_area" value="{{ old('total_area') }}" 
                                   class="form-input rounded-md shadow-sm w-full" required>
                        </div>

                        <!-- Unit Area -->
                        <div>
                            <label for="unit_area" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Unit Area (m²)') }}
                            </label>
                            <input type="number" step="0.01" id="unit_area" name="unit_area" value="{{ old('unit_area') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Land Area -->
                        <div>
                            <label for="land_area" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Land Area (m²)') }}
                            </label>
                            <input type="number" step="0.01" id="land_area" name="land_area" value="{{ old('land_area') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Garden Area -->
                        <div>
                            <label for="garden_area" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Garden Area (m²)') }}
                            </label>
                            <input type="number" step="0.01" id="garden_area" name="garden_area" value="{{ old('garden_area') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Space Earth -->
                        <div>
                            <label for="space_earth" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Space Earth (m²)') }}
                            </label>
                            <input type="number" step="0.01" id="space_earth" name="space_earth" value="{{ old('space_earth') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Rooms -->
                        <div>
                            <label for="rooms" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Rooms') }}
                            </label>
                            <input type="number" id="rooms" name="rooms" value="{{ old('rooms') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Bathrooms') }}
                            </label>
                            <input type="number" id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Finished Status -->
                        <div>
                            <label for="finished" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Finished Status') }}
                            </label>
                            <select id="finished" name="finished" class="form-select rounded-md shadow-sm w-full">
                                <option value="1" {{ old('finished') == '1' ? 'selected' : '' }}>{{ __('Finished') }}</option>
                                <option value="0" {{ old('finished') == '0' ? 'selected' : '' }}>{{ __('Unfinished') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Pricing Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Total Price -->
                        <div>
                            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Total Price') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" id="total_price" name="total_price" value="{{ old('total_price') }}" 
                                   class="form-input rounded-md shadow-sm w-full" required>
                        </div>

                        <!-- Price Per Meter -->
                        <div>
                            <label for="price_per_meter" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Price Per Meter') }}
                            </label>
                            <input type="number" step="0.01" id="price_per_meter" name="price_per_meter" value="{{ old('price_per_meter') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Currency') }}
                            </label>
                            <select id="currency" name="currency" class="form-select rounded-md shadow-sm w-full">
                                @foreach(['EGP', 'USD', 'EUR'] as $currency)
                                    <option value="{{ $currency }}" {{ old('currency', 'EGP') == $currency ? 'selected' : '' }}>
                                        {{ $currency }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rental Period (if applicable) -->
                        <div class="rent-fields hidden">
                            <label for="rent_from" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Rent From') }}
                            </label>
                            <input type="date" id="rent_from" name="rent_from" value="{{ old('rent_from') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <div class="rent-fields hidden">
                            <label for="rent_to" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Rent To') }}
                            </label>
                            <input type="date" id="rent_to" name="rent_to" value="{{ old('rent_to') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Owner Information Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Owner Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Property Offered By -->
                        <div>
                            <label for="property_offered_by" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Property Offered By') }}
                            </label>
                            <select id="property_offered_by" name="property_offered_by" class="form-select rounded-md shadow-sm w-full">
                                @foreach(['owner', 'agent', 'company'] as $offeredBy)
                                    <option value="{{ $offeredBy }}" {{ old('property_offered_by') == $offeredBy ? 'selected' : '' }}>
                                        {{ __(ucfirst($offeredBy)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Owner Name -->
                        <div>
                            <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Owner Name') }}
                            </label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Owner Mobile -->
                        <div>
                            <label for="owner_mobile" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Owner Mobile') }}
                            </label>
                            <input type="text" id="owner_mobile" name="owner_mobile" value="{{ old('owner_mobile') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Owner Tel -->
                        <div>
                            <label for="owner_tel" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Owner Telephone') }}
                            </label>
                            <input type="text" id="owner_tel" name="owner_tel" value="{{ old('owner_tel') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Contact Status -->
                        <div>
                            <label for="contact_status" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Contact Status') }}
                            </label>
                            <select id="contact_status" name="contact_status" class="form-select rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select...') }}</option>
                                @foreach(['contacted', 'pending', 'no_answer'] as $contactStatus)
                                    <option value="{{ $contactStatus }}" {{ old('contact_status') == $contactStatus ? 'selected' : '' }}>
                                        {{ __(ucfirst($contactStatus)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Information Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Sales Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Handler -->
                        <div>
                            <label for="handler_id" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Handler') }}
                            </label>
                            <select id="handler_id" name="handler_id" class="form-select rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select...') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('handler_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sales Person -->
                        <div>
                            <label for="sales_person_id" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Sales Person') }}
                            </label>
                            <select id="sales_person_id" name="sales_person_id" class="form-select rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select...') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('sales_person_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sales Category -->
                        <div>
                            <label for="sales_category" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Sales Category') }}
                            </label>
                            <input type="text" id="sales_category" name="sales_category" value="{{ old('sales_category') }}" 
                                   class="form-input rounded-md shadow-sm w-full">
                        </div>

                        <!-- Project -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Project') }}
                            </label>
                            <select id="project_id" name="project_id" class="form-select rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select...') }}</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Sales Notes -->
                    <div class="mt-4">
                        <label for="sales_notes" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Sales Notes') }}
                        </label>
                        <textarea id="sales_notes" name="sales_notes" rows="3" 
                                  class="form-textarea rounded-md shadow-sm w-full">{{ old('sales_notes') }}</textarea>
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
                                <option value="{{ $feature }}" {{ in_array($feature, old('features', [])) ? 'selected' : '' }}>
                                    {{ $feature }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Hold Ctrl/Cmd to select multiple features') }}</p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-4">
                        <label for="amenities" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Amenities') }}
                        </label>
                        <select id="amenities" name="amenities[]" multiple class="form-multiselect rounded-md shadow-sm w-full">
                            @foreach($amenities as $amenity)
                                <option value="{{ $amenity }}" {{ in_array($amenity, old('amenities', [])) ? 'selected' : '' }}>
                                    {{ $amenity }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Hold Ctrl/Cmd to select multiple amenities') }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Description') }}
                        </label>
                        <textarea id="description" name="description" rows="5" 
                                  class="form-textarea rounded-md shadow-sm w-full">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Media Upload Card -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Property Media') }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Featured Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Featured Image') }}
                            </label>
                            <input type="file" name="featured_image" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0 file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Additional Images') }}
                            </label>
                            <input type="file" name="additional_images[]" accept="image/*" multiple 
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
                <a href="{{ route('properties.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ __('Create Property') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle rental fields visibility
            const unitForSelect = document.getElementById('unit_for');
            const rentFields = document.querySelectorAll('.rent-fields');
            
            function toggleRentFields() {
                const isRent = unitForSelect.value === 'rent';
                rentFields.forEach(field => {
                    field.classList.toggle('hidden', !isRent);
                });
            }
            
            unitForSelect.addEventListener('change', toggleRentFields);
            toggleRentFields(); // Initial state

            // Auto-calculate price per meter
            const totalAreaInput = document.getElementById('total_area');
            const totalPriceInput = document.getElementById('total_price');
            const pricePerMeterInput = document.getElementById('price_per_meter');
            
            function calculatePricePerMeter() {
                const totalArea = parseFloat(totalAreaInput.value) || 0;
                const totalPrice = parseFloat(totalPriceInput.value) || 0;
                
                if (totalArea > 0 && totalPrice > 0) {
                    const pricePerMeter = totalPrice / totalArea;
                    pricePerMeterInput.value = pricePerMeter.toFixed(2);
                }
            }
            
            totalAreaInput.addEventListener('input', calculatePricePerMeter);
            totalPriceInput.addEventListener('input', calculatePricePerMeter);
        });
    </script>
    @endpush
</x-app-layout>