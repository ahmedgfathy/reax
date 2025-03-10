<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Reports Management') }}</title>
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
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Reports') }}</h1>
            
            <a href="{{ route('reports.create') }}" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create Report') }}
            </a>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4">
                <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" id="search" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('search') }}">
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Entity Type') }}</label>
                        <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="lead" {{ request('type') == 'lead' ? 'selected' : '' }}>{{ __('Leads') }}</option>
                            <option value="property" {{ request('type') == 'property' ? 'selected' : '' }}>{{ __('Properties') }}</option>
                            <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>{{ __('Users') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="schedule" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Schedule') }}</label>
                        <select name="schedule" id="schedule" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ __('All Schedules') }}</option>
                            <option value="daily" {{ request('schedule') == 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                            <option value="weekly" {{ request('schedule') == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                            <option value="monthly" {{ request('schedule') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                            <option value="none" {{ request('schedule') == 'none' ? 'selected' : '' }}>{{ __('No Schedule') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">{{ __('Filter') }}</button>
                        <a href="{{ route('reports.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Main content area for reports -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Report Name') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Entity Type') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Schedule') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Created At') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($reports) && count($reports) > 0)
                            @foreach($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ ucfirst($report->entity_type) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ ucfirst($report->schedule ?? 'None') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $report->created_at->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('reports.edit', $report->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to delete this report?') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    {{ __('No reports found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                @if(isset($reports) && method_exists($reports, 'links'))
                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>
