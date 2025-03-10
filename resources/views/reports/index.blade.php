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
        
        <!-- Card Layout for Reports -->
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

            @if(isset($reports) && count($reports) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($reports as $report)
                        <!-- Report Card -->
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100">
                            <!-- Card Header with Report Type Icon -->
                            <div class="bg-blue-50 p-4 flex items-center justify-between border-b border-gray-100">
                                <div class="flex items-center">
                                    @if($report->entity_type == 'lead')
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                                        </div>
                                    @elseif($report->entity_type == 'property')
                                        <div class="bg-green-100 p-2 rounded-lg">
                                            <i class="fas fa-home text-green-600 text-xl"></i>
                                        </div>
                                    @elseif($report->entity_type == 'user')
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <i class="fas fa-users text-purple-600 text-xl"></i>
                                        </div>
                                    @else
                                        <div class="bg-gray-100 p-2 rounded-lg">
                                            <i class="fas fa-file-alt text-gray-600 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <h3 class="font-semibold text-gray-800">{{ $report->name }}</h3>
                                        <span class="text-xs text-gray-500">{{ ucfirst($report->entity_type) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Schedule Badge -->
                                @if($report->schedule)
                                <div>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $report->schedule == 'daily' ? 'bg-green-100 text-green-800' : 
                                          ($report->schedule == 'weekly' ? 'bg-blue-100 text-blue-800' : 
                                          'bg-purple-100 text-purple-800') }}">
                                        {{ ucfirst($report->schedule) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Card Body -->
                            <div class="p-4">
                                <!-- Report Details -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-500">{{ __('Created At') }}:</span>
                                        <span class="text-gray-700">{{ $report->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">{{ __('Last Run') }}:</span>
                                        <span class="text-gray-700">{{ $report->last_run_at ? $report->last_run_at->format('Y-m-d') : __('Never') }}</span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                                    <div>
                                        <span class="text-xs text-gray-500">{{ __('ID') }}: {{ $report->id }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <!-- Fixed: View button routes to show instead of results -->
                                        <a href="{{ route('reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-800 p-1" title="{{ __('View Report') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('reports.edit', $report->id) }}" class="text-indigo-600 hover:text-indigo-800 p-1" title="{{ __('Edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this report?') }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <!-- Download Button - Using show route with optional download parameter -->
                                        <a href="{{ route('reports.show', [$report->id, 'download' => true]) }}" class="text-green-600 hover:text-green-800 p-1" title="{{ __('Download') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Footer - Run Report Button with form submission -->
                            <div class="bg-gray-50 p-3 border-t border-gray-100">
                                <!-- Use the show route instead of a separate run route -->
                                <a href="{{ route('reports.show', [$report->id, 'run' => true]) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    <i class="fas fa-play mr-2"></i> {{ __('Run Report') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if(method_exists($reports, 'links'))
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-chart-bar text-5xl mb-3"></i>
                        <h3 class="text-xl font-semibold">{{ __('No reports found') }}</h3>
                        <p>{{ __('Create a new report to get started') }}</p>
                    </div>
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> {{ __('Create Report') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>
