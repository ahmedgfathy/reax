<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Report') }} - {{ $report->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Report Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $report->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ ucfirst($report->entity_type) }} {{ __('Report') }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.edit', $report->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-edit mr-2"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('reports.show', [$report->id, 'download' => true]) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-download mr-2"></i> {{ __('Download') }}
                    </a>
                    <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Report Metadata Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-500">{{ __('Report Type') }}</span>
                    <p class="font-medium">{{ ucfirst($report->entity_type) }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('Schedule') }}</span>
                    <p class="font-medium">{{ ucfirst($report->schedule ?? __('Not scheduled')) }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('Created At') }}</span>
                    <p class="font-medium">{{ $report->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">{{ __('Last Run') }}</span>
                    <p class="font-medium">{{ $report->last_run_at ? $report->last_run_at->format('Y-m-d H:i') : __('Never') }}</p>
                </div>
            </div>
        </div>

        <!-- Report Filter Options -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Report Filters') }}</h2>
            
            <form action="{{ route('reports.show', $report->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <input type="hidden" name="run" value="1">
                
                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date From') }}</label>
                    <input type="date" name="date_from" id="date_from" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('date_from') }}">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date To') }}</label>
                    <input type="date" name="date_to" id="date_to" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('date_to') }}">
                </div>
                
                <!-- Additional filters based on report type -->
                @if($report->entity_type == 'lead')
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>{{ __('Proposal') }}</option>
                        <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>{{ __('Won') }}</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
                    </select>
                </div>
                @elseif($report->entity_type == 'property')
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Type') }}</label>
                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                        <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                        <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>{{ __('Land') }}</option>
                    </select>
                </div>
                @endif
                
                <div class="md:self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-play mr-2"></i> {{ __('Run Report') }}
                    </button>
                </div>
            </form>
        </div>

        @if(request('run'))
        <!-- Report Results -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('Report Results') }}</h2>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded-md flex items-center text-sm">
                        <i class="fas fa-print mr-2"></i> {{ __('Print') }}
                    </button>
                    <button onclick="exportToCSV()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded-md flex items-center text-sm">
                        <i class="fas fa-file-csv mr-2"></i> {{ __('Export CSV') }}
                    </button>
                </div>
            </div>

            <!-- Data Visualizations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Chart 1: Pie Chart -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h3 class="text-md font-medium text-gray-700 mb-4">{{ __('Distribution by Type') }}</h3>
                    <div class="h-64">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
                
                <!-- Chart 2: Line Chart  -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h3 class="text-md font-medium text-gray-700 mb-4">{{ __('Trend Over Time') }}</h3>
                    <div class="h-64">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if($report->entity_type == 'lead')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Phone') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Email') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Created') }}</th>
                            @elseif($report->entity_type == 'property')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Price') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Location') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                            @elseif($report->entity_type == 'user')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Email') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Role') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Created') }}</th>
                            @else
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Created') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reportData ?? [] as $item)
                            <tr class="hover:bg-gray-50">
                                @if($report->entity_type == 'lead')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['id'] ?? 'N/A' }}
                                        @else
                                            {{ $item->id ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if(is_array($item))
                                            {{ isset($item['first_name']) ? ($item['first_name'] . ' ' . ($item['last_name'] ?? '')) : 'N/A' }}
                                        @else
                                            {{ isset($item->first_name) ? ($item->first_name . ' ' . ($item->last_name ?? '')) : 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(is_array($item))
                                            {{ $item['phone'] ?? 'N/A' }}
                                        @else
                                            {{ $item->phone ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(is_array($item))
                                            {{ $item['email'] ?? 'N/A' }}
                                        @else
                                            {{ $item->email ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = '';
                                            if(is_array($item)) {
                                                $status = $item['status'] ?? 'unknown';
                                            } else {
                                                $status = $item->status ?? 'unknown';
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $status == 'new' ? 'bg-blue-100 text-blue-800' : 
                                                ($status == 'qualified' ? 'bg-green-100 text-green-800' : 
                                                ($status == 'won' ? 'bg-purple-100 text-purple-800' : 
                                                'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $formattedDate = 'N/A';
                                            try {
                                                if(is_array($item)) {
                                                    $createdAt = $item['created_at'] ?? null;
                                                    if(is_string($createdAt) && !empty($createdAt)) {
                                                        $dateObj = new \DateTime($createdAt);
                                                        $formattedDate = $dateObj->format('Y-m-d');
                                                    }
                                                } else {
                                                    if(isset($item->created_at)) {
                                                        $formattedDate = $item->created_at->format('Y-m-d');
                                                    }
                                                }
                                            } catch(\Exception $e) {
                                                $formattedDate = 'Invalid date';
                                            }
                                        @endphp
                                        {{ $formattedDate }}
                                    </td>
                                @elseif($report->entity_type == 'property')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['id'] ?? 'N/A' }}
                                        @else
                                            {{ $item->id ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['name'] ?? 'N/A' }}
                                        @else
                                            {{ $item->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $type = 'N/A';
                                            if(is_array($item)) {
                                                $type = isset($item['type']) ? ucfirst($item['type']) : 'N/A';
                                            } else {
                                                $type = isset($item->type) ? ucfirst($item->type) : 'N/A';
                                            }
                                        @endphp
                                        {{ $type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $price = 0;
                                            $currency = 'USD';
                                            if(is_array($item)) {
                                                $price = $item['price'] ?? 0;
                                                $currency = $item['currency'] ?? 'USD';
                                            } else {
                                                $price = $item->price ?? 0;
                                                $currency = $item->currency ?? 'USD';
                                            }
                                        @endphp
                                        {{ number_format($price) }} {{ $currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $location = 'N/A';
                                            if(is_array($item)) {
                                                $location = $item['location'] ?? $item['area'] ?? 'N/A';
                                            } else {
                                                $location = $item->location ?? $item->area ?? 'N/A';
                                            }
                                        @endphp
                                        {{ $location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = 'unknown';
                                            if(is_array($item)) {
                                                $status = $item['status'] ?? 'unknown';
                                            } else {
                                                $status = $item->status ?? 'unknown';
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $status == 'available' ? 'bg-green-100 text-green-800' : 
                                                ($status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($status == 'sold' ? 'bg-blue-100 text-blue-800' : 
                                                'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                @elseif($report->entity_type == 'user')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['id'] ?? 'N/A' }}
                                        @else
                                            {{ $item->id ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['name'] ?? 'N/A' }}
                                        @else
                                            {{ $item->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(is_array($item))
                                            {{ $item['email'] ?? 'N/A' }}
                                        @else
                                            {{ $item->email ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(is_array($item))
                                            {{ $item['role'] ?? 'User' }}
                                        @else
                                            {{ $item->role ?? 'User' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $formattedDate = 'N/A';
                                            try {
                                                if(is_array($item)) {
                                                    $createdAt = $item['created_at'] ?? null;
                                                    if(is_string($createdAt) && !empty($createdAt)) {
                                                        $dateObj = new \DateTime($createdAt);
                                                        $formattedDate = $dateObj->format('Y-m-d');
                                                    }
                                                } else {
                                                    if(isset($item->created_at)) {
                                                        $formattedDate = $item->created_at->format('Y-m-d');
                                                    }
                                                }
                                            } catch(\Exception $e) {
                                                $formattedDate = 'Invalid date';
                                            }
                                        @endphp
                                        {{ $formattedDate }}
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['id'] ?? 'N/A' }}
                                        @else
                                            {{ $item->id ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if(is_array($item))
                                            {{ $item['name'] ?? 'N/A' }}
                                        @else
                                            {{ $item->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $type = 'N/A';
                                            if(is_array($item)) {
                                                $type = isset($item['type']) ? ucfirst($item['type']) : 'N/A';
                                            } else {
                                                $type = isset($item->type) ? ucfirst($item->type) : 'N/A';
                                            }
                                        @endphp
                                        {{ $type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $formattedDate = 'N/A';
                                            try {
                                                if(is_array($item)) {
                                                    $createdAt = $item['created_at'] ?? null;
                                                    if(is_string($createdAt) && !empty($createdAt)) {
                                                        $dateObj = new \DateTime($createdAt);
                                                        $formattedDate = $dateObj->format('Y-m-d');
                                                    }
                                                } else {
                                                    if(isset($item->created_at)) {
                                                        $formattedDate = $item->created_at->format('Y-m-d');
                                                    }
                                                }
                                            } catch(\Exception $e) {
                                                $formattedDate = 'Invalid date';
                                            }
                                        @endphp
                                        {{ $formattedDate }}
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $report->entity_type == 'lead' ? 6 : ($report->entity_type == 'property' ? 6 : ($report->entity_type == 'user' ? 5 : 4)) }}" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No data available for the selected filters') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if needed) -->
            @if(isset($reportData) && is_object($reportData) && method_exists($reportData, 'links'))
                <div class="mt-4">
                    {{ $reportData->links() }}
                </div>
            @endif

            <!-- Report Summary Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-md font-medium text-gray-800 mb-2">{{ __('Total Records') }}</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        @php
                            $count = is_object($reportData) && method_exists($reportData, 'count') ? $reportData->count() : count($reportData ?? []);
                        @endphp
                        {{ $count }}
                    </p>
                </div>
                
                @if($report->entity_type == 'lead')
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-md font-medium text-gray-800 mb-2">{{ __('Qualified Leads') }}</h3>
                        <p class="text-2xl font-bold text-green-600">
                            @php
                                $qualifiedCount = 0;
                                if (is_object($reportData) && method_exists($reportData, 'where')) {
                                    $qualifiedCount = $reportData->where('status', 'qualified')->count();
                                } else {
                                    foreach ($reportData ?? [] as $item) {
                                        $status = is_array($item) ? ($item['status'] ?? '') : ($item->status ?? '');
                                        if ($status === 'qualified') {
                                            $qualifiedCount++;
                                        }
                                    }
                                }
                            @endphp
                            {{ $qualifiedCount }}
                        </p>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-md font-medium text-gray-800 mb-2">{{ __('Conversion Rate') }}</h3>
                        <p class="text-2xl font-bold text-purple-600">
                            @php
                                $totalCount = is_object($reportData) && method_exists($reportData, 'count') ? $reportData->count() : count($reportData ?? []);
                                $wonCount = 0;
                                
                                if (is_object($reportData) && method_exists($reportData, 'where')) {
                                    $wonCount = $reportData->where('status', 'won')->count();
                                } else {
                                    foreach ($reportData ?? [] as $item) {
                                        $status = is_array($item) ? ($item['status'] ?? '') : ($item->status ?? '');
                                        if ($status === 'won') {
                                            $wonCount++;
                                        }
                                    }
                                }
                                
                                $conversionRate = $totalCount > 0 ? ($wonCount / $totalCount) * 100 : 0;
                            @endphp
                            {{ number_format($conversionRate, 1) }}%
                        </p>
                    </div>
                @elseif($report->entity_type == 'property')
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-md font-medium text-gray-800 mb-2">{{ __('Available Properties') }}</h3>
                        <p class="text-2xl font-bold text-green-600">
                            @php
                                $availableCount = 0;
                                if (is_object($reportData) && method_exists($reportData, 'where')) {
                                    $availableCount = $reportData->where('status', 'available')->count();
                                } else {
                                    foreach ($reportData ?? [] as $item) {
                                        $status = is_array($item) ? ($item['status'] ?? '') : ($item->status ?? '');
                                        if ($status === 'available') {
                                            $availableCount++;
                                        }
                                    }
                                }
                            @endphp
                            {{ $availableCount }}
                        </p>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-md font-medium text-gray-800 mb-2">{{ __('Average Price') }}</h3>
                        <p class="text-2xl font-bold text-purple-600">
                            @php
                                $totalPrice = 0;
                                $count = 0;
                                
                                if (is_object($reportData) && method_exists($reportData, 'avg')) {
                                    $avgPrice = $reportData->avg('price');
                                } else {
                                    foreach ($reportData ?? [] as $item) {
                                        $price = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
                                        $totalPrice += $price;
                                        $count++;
                                    }
                                    $avgPrice = $count > 0 ? $totalPrice / $count : 0;
                                }
                            @endphp
                            {{ number_format($avgPrice, 0) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @else
        <!-- No Report Run Yet Message -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-chart-line text-5xl mb-3"></i>
                <h3 class="text-xl font-semibold">{{ __('Run this report to see results') }}</h3>
                <p>{{ __('Use the filters above and click on "Run Report" to generate results') }}</p>
            </div>
            <button onclick="document.querySelector('form').submit()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-play mr-2"></i> {{ __('Run Report Now') }}
            </button>
        </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up charts if report has been run
        @if(request('run') && isset($reportData) && (!empty($reportData)) && (is_object($reportData) ? method_exists($reportData, 'count') ? $reportData->count() > 0 : count($reportData) > 0 : count($reportData) > 0))
            // Distribution chart data setup
            const distributionCtx = document.getElementById('distributionChart').getContext('2d');
            
            @if($report->entity_type == 'lead')
                // Lead distribution by status
                @php
                    $statusCounts = [];
                    if (is_object($reportData) && method_exists($reportData, 'groupBy')) {
                        $statusGroups = $reportData->groupBy('status');
                        foreach ($statusGroups as $status => $items) {
                            $statusCounts[$status] = $items->count();
                        }
                    } else {
                        foreach ($reportData ?? [] as $item) {
                            $status = is_array($item) ? ($item['status'] ?? 'unknown') : ($item->status ?? 'unknown');
                            if (!isset($statusCounts[$status])) {
                                $statusCounts[$status] = 0;
                            }
                            $statusCounts[$status]++;
                        }
                    }
                @endphp
                const statuses = {!! json_encode($statusCounts) !!};
                new Chart(distributionCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(statuses).map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                        datasets: [{
                            data: Object.values(statuses),
                            backgroundColor: [
                                '#3B82F6', // blue
                                '#10B981', // green
                                '#8B5CF6', // purple
                                '#F59E0B', // yellow
                                '#EF4444'  // red
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            @elseif($report->entity_type == 'property')
                // Property distribution by type
                @php
                    $typeCounts = [];
                    if (is_object($reportData) && method_exists($reportData, 'groupBy')) {
                        $typeGroups = $reportData->groupBy('type');
                        foreach ($typeGroups as $type => $items) {
                            $typeCounts[$type] = $items->count();
                        }
                    } else {
                        foreach ($reportData ?? [] as $item) {
                            $type = is_array($item) ? ($item['type'] ?? 'unknown') : ($item->type ?? 'unknown');
                            if (!isset($typeCounts[$type])) {
                                $typeCounts[$type] = 0;
                            }
                            $typeCounts[$type]++;
                        }
                    }
                @endphp
                const types = {!! json_encode($typeCounts) !!};
                new Chart(distributionCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(types).map(type => type.charAt(0).toUpperCase() + type.slice(1)),
                        datasets: [{
                            data: Object.values(types),
                            backgroundColor: [
                                '#3B82F6', // blue
                                '#10B981', // green
                                '#8B5CF6', // purple
                                '#F59E0B', // yellow
                                '#EF4444'  // red
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            @endif
            
            // Trend chart data setup
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            
            // Group by month
            @php
                $monthData = [];
                if (is_object($reportData) && method_exists($reportData, 'groupBy')) {
                    $monthGroups = $reportData->groupBy(function($item) {
                        return $item->created_at->format('Y-m');
                    });
                    foreach ($monthGroups as $month => $items) {
                        $monthData[$month] = $items->count();
                    }
                } else {
                    foreach ($reportData ?? [] as $item) {
                        if (is_array($item)) {
                            $createdAt = isset($item['created_at']) ? $item['created_at'] : null;
                            if (is_string($createdAt)) {
                                $createdAt = new DateTime($createdAt);
                            }
                        } else {
                            $createdAt = $item->created_at ?? null;
                        }
                        
                        if ($createdAt) {
                            $month = $createdAt->format('Y-m');
                            if (!isset($monthData[$month])) {
                                $monthData[$month] = 0;
                            }
                            $monthData[$month]++;
                        }
                    }
                }
            @endphp
            const monthData = {!! json_encode($monthData) !!};
            
            // Sort months chronologically
            const sortedMonths = Object.keys(monthData).sort();
            const monthLabels = sortedMonths.map(month => {
                const [year, m] = month.split('-');
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return `${monthNames[parseInt(m) - 1]} ${year}`;
            });
            
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: '{{ __("Count") }}',
                        data: sortedMonths.map(month => monthData[month]),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        @endif
    });
    
    // Function to export table to CSV
    function exportToCSV() {
        // Get table headers
        const table = document.querySelector('table');
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => th.textContent.trim());
        
        // Get table rows
        const rows = Array.from(table.querySelectorAll('tbody tr'))
            .map(row => Array.from(row.querySelectorAll('td'))
                .map(td => td.textContent.trim()));
        
        // Create CSV content
        const csvContent = [
            headers.join(','),
            ...rows.map(row => row.join(','))
        ].join('\n');
        
        // Create download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', '{{ Str::slug($report->name) }}_report.csv');
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>
</body>
</html>
