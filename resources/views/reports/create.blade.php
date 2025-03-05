@extends('layouts.app')

@section('content')
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Create Report') }}</h1>
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <form action="{{ route('reports.store') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Basic Info Section -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Basic Information') }}</h2>
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Report Name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                               required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                        <textarea id="description" name="description" rows="2" 
                                  class="w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="data_source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data Source') }}</label>
                        <select id="data_source" name="data_source" 
                                class="w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                required>
                            <option value="leads" {{ old('data_source') == 'leads' ? 'selected' : '' }}>{{ __('Leads') }}</option>
                            <option value="properties" {{ old('data_source') == 'properties' ? 'selected' : '' }}>{{ __('Properties') }}</option>
                            <option value="both" {{ old('data_source') == 'both' ? 'selected' : '' }}>{{ __('Combined (Leads & Properties)') }}</option>
                        </select>
                        @error('data_source')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Columns Selection Section -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Select Columns') }}</h2>
                
                <div id="leads-columns" class="mb-4">
                    <h3 class="text-md font-medium text-gray-700 mb-2">{{ __('Leads Columns') }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($leadColumns as $value => $label)
                            <label class="flex items-center">
                                <input type="checkbox" name="columns[]" value="{{ $value }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       {{ in_array($value, old('columns', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div id="properties-columns" class="mb-4 hidden">
                    <h3 class="text-md font-medium text-gray-700 mb-2">{{ __('Properties Columns') }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($propertyColumns as $value => $label)
                            <label class="flex items-center">
                                <input type="checkbox" name="columns[]" value="{{ $value }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       {{ in_array($value, old('columns', [])) ? 'checked' : '' }}
                                       disabled>
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                @error('columns')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Filters Section -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Filters (Optional)') }}</h2>
                
                <div id="filters-container">
                    <!-- Filters will be added dynamically -->
                    <div class="filter-row flex flex-wrap md:flex-nowrap items-end space-x-2 mb-3">
                        <div class="w-full md:w-1/3 mb-2 md:mb-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Field') }}</label>
                            <select name="filters[0][field]" class="filter-field w-full border-gray-300 rounded-md">
                                <option value="">{{ __('Select Field') }}</option>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 mb-2 md:mb-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Operator') }}</label>
                            <select name="filters[0][operator]" class="w-full border-gray-300 rounded-md">
                                <option value="=">{{ __('Equals') }}</option>
                                <option value="!=">{{ __('Not Equals') }}</option>
                                <option value="contains">{{ __('Contains') }}</option>
                                <option value=">">{{ __('Greater Than') }}</option>
                                <option value="<">{{ __('Less Than') }}</option>
                                <option value="between">{{ __('Between') }}</option>
                                <option value="in">{{ __('In List') }}</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 mb-2 md:mb-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Value') }}</label>
                            <input type="text" name="filters[0][value]" class="w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <button type="button" class="remove-filter p-2 text-red-600 hover:text-red-800" title="{{ __('Remove Filter') }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="button" id="add-filter" class="text-sm text-blue-600 hover:underline flex items-center">
                        <i class="fas fa-plus mr-1"></i> {{ __('Add Filter') }}
                    </button>
                </div>
            </div>
            
            <!-- Visualization Section -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Visualization') }}</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <label class="bg-white border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                        <input type="radio" name="visualization[type]" value="table" class="sr-only" checked>
                        <span class="h-16 w-16 flex items-center justify-center text-blue-600">
                            <i class="fas fa-table text-3xl"></i>
                        </span>
                        <span class="mt-2 font-medium">{{ __('Table') }}</span>
                        <span class="mt-1 text-xs text-gray-500">{{ __('Standard tabular format') }}</span>
                        <div class="w-full h-1 bg-blue-600 rounded-full mt-2"></div>
                    </label>
                    
                    <label class="bg-white border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                        <input type="radio" name="visualization[type]" value="bar" class="sr-only">
                        <span class="h-16 w-16 flex items-center justify-center text-blue-600">
                            <i class="fas fa-chart-bar text-3xl"></i>
                        </span>
                        <span class="mt-2 font-medium">{{ __('Bar Chart') }}</span>
                        <span class="mt-1 text-xs text-gray-500">{{ __('Compare values side by side') }}</span>
                        <div class="w-full h-1 bg-gray-200 rounded-full mt-2"></div>
                    </label>
                    
                    <label class="bg-white border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                        <input type="radio" name="visualization[type]" value="pie" class="sr-only">
                        <span class="h-16 w-16 flex items-center justify-center text-blue-600">
                            <i class="fas fa-chart-pie text-3xl"></i>
                        </span>
                        <span class="mt-2 font-medium">{{ __('Pie Chart') }}</span>
                        <span class="mt-1 text-xs text-gray-500">{{ __('Show percentages of a whole') }}</span>
                        <div class="w-full h-1 bg-gray-200 rounded-full mt-2"></div>
                    </label>
                    
                    <label class="bg-white border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                        <input type="radio" name="visualization[type]" value="line" class="sr-only">
                        <span class="h-16 w-16 flex items-center justify-center text-blue-600">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </span>
                        <span class="mt-2 font-medium">{{ __('Line Chart') }}</span>
                        <span class="mt-1 text-xs text-gray-500">{{ __('Show trends over time') }}</span>
                        <div class="w-full h-1 bg-gray-200 rounded-full mt-2"></div>
                    </label>
                </div>
            </div>
            
            <!-- Sharing Settings -->
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sharing Settings') }}</h2>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_public" name="is_public" value="1" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               {{ old('is_public') ? 'checked' : '' }}>
                        <label for="is_public" class="ml-2 text-sm text-gray-700">{{ __('Make this report public') }}</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Public reports are visible to all users in your organization') }}</p>
                </div>
                
                <div>
                    <label for="access_level" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Access Level') }}</label>
                    <select id="access_level" name="access_level" 
                            class="w-full md:w-1/3 border-gray-300 rounded-md">
                        <option value="private" {{ old('access_level') == 'private' ? 'selected' : '' }}>{{ __('Private (Only me)') }}</option>
                        <option value="team" {{ old('access_level') == 'team' ? 'selected' : '' }}>{{ __('Team (My department)') }}</option>
                        <option value="public" {{ old('access_level') == 'public' ? 'selected' : '' }}>{{ __('Public (Everyone)') }}</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-6 flex justify-end">
            <button type="button" id="preview-report" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md mr-2">
                <i class="fas fa-eye mr-2"></i> {{ __('Preview') }}
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                <i class="fas fa-save mr-2"></i> {{ __('Save Report') }}
            </button>
        </div>
    </form>
    
    <!-- Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold">{{ __('Report Preview') }}</h3>
                <button id="close-preview" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto" style="max-height: calc(90vh - 60px);">
                <div id="preview-content" class="min-h-[300px]">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-4xl mb-4"></i>
                            <p>{{ __('Loading preview...') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data source change handler
        const dataSourceSelect = document.getElementById('data_source');
        const leadsColumns = document.getElementById('leads-columns');
        const propertiesColumns = document.getElementById('properties-columns');
        
        dataSourceSelect.addEventListener('change', function() {
            updateAvailableColumns();
        });
        
        function updateAvailableColumns() {
            const selectedSource = dataSourceSelect.value;
            
            // Reset all checkboxes
            document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
                checkbox.disabled = true;
                checkbox.checked = false;
            });
            
            // Show/hide columns sections based on selected data source
            if (selectedSource === 'leads' || selectedSource === 'both') {
                leadsColumns.classList.remove('hidden');
                document.querySelectorAll('#leads-columns input[name="columns[]"]').forEach(checkbox => {
                    checkbox.disabled = false;
                });
            } else {
                leadsColumns.classList.add('hidden');
            }
            
            if (selectedSource === 'properties' || selectedSource === 'both') {
                propertiesColumns.classList.remove('hidden');
                document.querySelectorAll('#properties-columns input[name="columns[]"]').forEach(checkbox => {
                    checkbox.disabled = false;
                });
            } else {
                propertiesColumns.classList.add('hidden');
            }
            
            // Update filter fields
            updateFilterFields();
        }
        
        // Filter handling
        const filtersContainer = document.getElementById('filters-container');
        const addFilterButton = document.getElementById('add-filter');
        let filterCount = 1;
        
        addFilterButton.addEventListener('click', function() {
            addFilter();
        });
        
        function addFilter() {
            const templateRow = filtersContainer.querySelector('.filter-row').cloneNode(true);
            
            // Update field names
            const inputs = templateRow.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, '[' + filterCount + ']'));
                }
                // Clear value
                if (input.tagName === 'INPUT') {
                    input.value = '';
                }
            });
            
            // Setup remove button
            const removeButton = templateRow.querySelector('.remove-filter');
            removeButton.addEventListener('click', function() {
                templateRow.remove();
            });
            
            filtersContainer.appendChild(templateRow);
            
            // Update fields
            updateFilterFields();
            
            filterCount++;
        }
        
        // Populate filter fields based on data source
        function updateFilterFields() {
            const selectedSource = dataSourceSelect.value;
            const filterFields = document.querySelectorAll('.filter-field');
            
            filterFields.forEach(field => {
                // Clear existing options except the first one
                while (field.options.length > 1) {
                    field.remove(1);
                }
                
                // Add options based on selected data source
                if (selectedSource === 'leads' || selectedSource === 'both') {
                    // Add lead fields
                    const leadOptions = {
                        'first_name': '{{ __("First Name") }}',
                        'last_name': '{{ __("Last Name") }}',
                        'email': '{{ __("Email") }}',
                        'phone': '{{ __("Phone") }}',
                        'status': '{{ __("Status") }}',
                        'source': '{{ __("Source") }}',
                        'budget': '{{ __("Budget") }}',
                        'created_at': '{{ __("Created Date") }}',
                        // Add more fields as needed
                    };
                    
                    Object.entries(leadOptions).forEach(([value, label]) => {
                        const option = new Option(label, value);
                        field.add(option);
                    });
                }
                
                if (selectedSource === 'properties' || selectedSource === 'both') {
                    // Add property fields
                    const propertyOptions = {
                        'name': '{{ __("Name") }}',
                        'type': '{{ __("Type") }}',
                        'unit_for': '{{ __("For (Sale/Rent)") }}',
                        'price': '{{ __("Price") }}',
                        'area': '{{ __("Area") }}',
                        'location': '{{ __("Location") }}',
                        'created_at': '{{ __("Created Date") }}',
                        // Add more fields as needed
                    };
                    
                    Object.entries(propertyOptions).forEach(([value, label]) => {
                        const option = new Option(label, value);
                        field.add(option);
                    });
                }
            });
        }
        
        // Visualization type selection
        const visualizationRadios = document.querySelectorAll('input[name="visualization[type]"]');
        
        visualizationRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset all indicator bars
                document.querySelectorAll('input[name="visualization[type]"]').forEach(r => {
                    const container = r.closest('label');
                    const indicator = container.querySelector('div');
                    indicator.classList.remove('bg-blue-600');
                    indicator.classList.add('bg-gray-200');
                });
                
                // Highlight selected
                if (this.checked) {
                    const container = this.closest('label');
                    const indicator = container.querySelector('div');
                    indicator.classList.remove('bg-gray-200');
                    indicator.classList.add('bg-blue-600');
                }
            });
        });
        
        // Preview functionality
        const previewButton = document.getElementById('preview-report');
        const previewModal = document.getElementById('preview-modal');
        const closePreviewButton = document.getElementById('close-preview');
        const previewContent = document.getElementById('preview-content');
        
        previewButton.addEventListener('click', function() {
            // Show modal
            previewModal.classList.remove('hidden');
            previewContent.innerHTML = '<div class="flex items-center justify-center h-full"><div class="text-center"><i class="fas fa-spinner fa-spin text-blue-600 