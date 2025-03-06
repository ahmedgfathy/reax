<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Map Import Fields') }}</title>
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
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Map CSV Import Fields') }}</h1>
                <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Leads') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Map CSV Fields to Database Fields') }}</h2>
            
            <p class="mb-6 text-gray-600">
                {{ __('Please match each column from your CSV file to the appropriate field in our system.') }}
            </p>
            
            <!-- Sample Data Preview -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">{{ __('Sample Data (First Row)') }}</h3>
                <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                @foreach($headers as $header)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $header }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($sampleData as $value)
                                    <td class="px-3 py-2 text-sm">
                                        {{ is_array($value) ? json_encode($value) : $value }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Field Mapping Form -->
            <form action="{{ route('leads.process-import') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="temp_file" value="{{ $tempFile }}">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('CSV Column') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Sample Value') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Map to Field') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($headers as $index => $header)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $header }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $sampleData[$index] ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <select name="field_mapping[{{ $index }}]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="">{{ __('Ignore this column') }}</option>
                                            <option value="first_name" {{ $suggestedMapping[$index] == 'first_name' ? 'selected' : '' }}>{{ __('First Name') }}</option>
                                            <option value="last_name" {{ $suggestedMapping[$index] == 'last_name' ? 'selected' : '' }}>{{ __('Last Name') }}</option>
                                            <option value="email" {{ $suggestedMapping[$index] == 'email' ? 'selected' : '' }}>{{ __('Email') }}</option>
                                            <option value="phone" {{ $suggestedMapping[$index] == 'phone' ? 'selected' : '' }}>{{ __('Phone') }}</option>
                                            <option value="mobile" {{ $suggestedMapping[$index] == 'mobile' ? 'selected' : '' }}>{{ __('Mobile') }}</option>
                                            <option value="status" {{ $suggestedMapping[$index] == 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                                            <option value="lead_status" {{ $suggestedMapping[$index] == 'lead_status' ? 'selected' : '' }}>{{ __('Lead Status Detail') }}</option>
                                            <option value="source" {{ $suggestedMapping[$index] == 'source' ? 'selected' : '' }}>{{ __('Source') }}</option>
                                            <option value="lead_source" {{ $suggestedMapping[$index] == 'lead_source' ? 'selected' : '' }}>{{ __('Lead Source Detail') }}</option>
                                            <option value="budget" {{ $suggestedMapping[$index] == 'budget' ? 'selected' : '' }}>{{ __('Budget') }}</option>
                                            <option value="notes" {{ $suggestedMapping[$index] == 'notes' ? 'selected' : '' }}>{{ __('Notes') }}</option>
                                            <option value="description" {{ $suggestedMapping[$index] == 'description' ? 'selected' : '' }}>{{ __('Description') }}</option>
                                            <option value="lead_class" {{ $suggestedMapping[$index] == 'lead_class' ? 'selected' : '' }}>{{ __('Lead Class') }}</option>
                                            <option value="type_of_request" {{ $suggestedMapping[$index] == 'type_of_request' ? 'selected' : '' }}>{{ __('Type of Request') }}</option>
                                            <option value="last_follow_up" {{ $suggestedMapping[$index] == 'last_follow_up' ? 'selected' : '' }}>{{ __('Last Follow-up Date') }}</option>
                                            <option value="agent_follow_up" {{ $suggestedMapping[$index] == 'agent_follow_up' ? 'selected' : '' }}>{{ __('Agent Follow-up') }}</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 text-right">
                    <a href="{{ route('leads.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Import Leads') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
