<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Create Report') }}</title>
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
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Create Report') }}</h1>
                <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Reports') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('reports.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-md @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3" class="w-full p-2 border rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="data_source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data Source') }}</label>
                            <select id="data_source" name="data_source" class="w-full p-2 border rounded-md @error('data_source') border-red-500 @enderror" required>
                                <option value="">{{ __('Select Data Source') }}</option>
                                <option value="leads" {{ old('data_source') == 'leads' ? 'selected' : '' }}>{{ __('Leads') }}</option>
                                <option value="properties" {{ old('data_source') == 'properties' ? 'selected' : '' }}>{{ __('Properties') }}</option>
                                <option value="both" {{ old('data_source') == 'both' ? 'selected' : '' }}>{{ __('Combined') }}</option>
                            </select>
                            @error('data_source')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="access_level" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Access Level') }}</label>
                            <select id="access_level" name="access_level" class="w-full p-2 border rounded-md @error('access_level') border-red-500 @enderror" required>
                                <option value="private" {{ old('access_level') == 'private' ? 'selected' : '' }}>{{ __('Private') }}</option>
                                <option value="team" {{ old('access_level') == 'team' ? 'selected' : '' }}>{{ __('Team') }}</option>
                                <option value="public" {{ old('access_level') == 'public' ? 'selected' : '' }}>{{ __('Public') }}</option>
                            </select>
                            @error('access_level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Report Configuration -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Report Configuration') }}</h3>

                        <div id="columns-section" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select Columns') }}</label>
                            <div id="leads-columns" class="grid grid-cols-2 gap-2 mb-4">
                                @foreach($leadColumns as $value => $label)
                                    <label class="flex items-center p-2 border rounded-md hover:bg-gray-50">
                                        <input type="checkbox" name="columns[]" value="{{ $value }}" 
                                               class="rounded border-gray-300 text-blue-600 mr-2"
                                               {{ in_array($value, old('columns', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div id="properties-columns" class="grid grid-cols-2 gap-2 hidden">
                                @foreach($propertyColumns as $value => $label)
                                    <label class="flex items-center p-2 border rounded-md hover:bg-gray-50">
                                        <input type="checkbox" name="columns[]" value="{{ $value }}" 
                                               class="rounded border-gray-300 text-blue-600 mr-2"
                                               {{ in_array($value, old('columns', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Visualization Type') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="visualization[type]" value="table" class="sr-only" checked>
                                    <i class="fas fa-table text-gray-700 text-2xl"></i>
                                    <span class="text-gray-900 font-medium">{{ __('Table') }}</span>
                                    <div class="w-full h-1 bg-blue-600 rounded-full"></div>
                                </label>

                                <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="visualization[type]" value="chart" class="sr-only">
                                    <i class="fas fa-chart-bar text-gray-700 text-2xl"></i>
                                    <span class="text-gray-900 font-medium">{{ __('Chart') }}</span>
                                    <div class="w-full h-1 bg-gray-200 rounded-full"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t pt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-save mr-2"></i> {{ __('Create Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle data source change
        document.getElementById('data_source').addEventListener('change', function() {
            const leadsColumns = document.getElementById('leads-columns');
            const propertiesColumns = document.getElementById('properties-columns');
            
            switch(this.value) {
                case 'leads':
                    leadsColumns.classList.remove('hidden');
                    propertiesColumns.classList.add('hidden');
                    break;
                case 'properties':
                    leadsColumns.classList.add('hidden');
                    propertiesColumns.classList.remove('hidden');
                    break;
                case 'both':
                    leadsColumns.classList.remove('hidden');
                    propertiesColumns.classList.remove('hidden');
                    break;
                default:
                    leadsColumns.classList.add('hidden');
                    propertiesColumns.classList.add('hidden');
            }
        });

        // Handle visualization type selection
        document.querySelectorAll('input[name="visualization[type]"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove highlight from all
                document.querySelectorAll('input[name="visualization[type]"]').forEach(r => {
                    r.closest('label').querySelector('div').classList.remove('bg-blue-600');
                    r.closest('label').querySelector('div').classList.add('bg-gray-200');
                });
                
                // Add highlight to selected
                if (this.checked) {
                    this.closest('label').querySelector('div').classList.remove('bg-gray-200');
                    this.closest('label').querySelector('div').classList.add('bg-blue-600');
                }
            });
        });
    </script>
</body>
</html>