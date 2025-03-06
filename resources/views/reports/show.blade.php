@extends('layouts.app')

@section('content')
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ $report->name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back') }}
                </a>
                @if($report->created_by === auth()->id())
                <a href="{{ route('reports.edit', $report->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-edit mr-2"></i> {{ __('Edit') }}
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Report Details') }}</h2>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-500 block">{{ __('Created by') }}</span>
                    <div class="flex items-center mt-1">
                        <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs mr-2">
                            {{ substr($report->creator->name, 0, 1) }}
                        </div>
                        <span>{{ $report->creator->name }}</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-500 block">{{ __('Created at') }}</span>
                    <span class="font-medium">{{ $report->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-500 block">{{ __('Data Source') }}</span>
                    <span class="inline-block px-2 py-1 text-xs rounded-full 
                        {{ $report->data_source == 'leads' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $report->data_source == 'properties' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $report->data_source == 'both' ? 'bg-purple-100 text-purple-700' : '' }}
                    ">
                        {{ __($report->data_source) }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-500 block">{{ __('Access Level') }}</span>
                    <span class="flex items-center">
                        <i class="fas fa-{{ $report->is_public ? 'globe' : ($report->access_level == 'team' ? 'users' : 'lock') }} mr-1"></i>
                        {{ __($report->access_level) }}
                    </span>
                </div>
                
                @if($report->description)
                <div class="mb-4">
                    <span class="text-sm text-gray-500 block">{{ __('Description') }}</span>
                    <p class="text-gray-700 mt-1">{{ $report->description }}</p>
                </div>
                @endif
            </div>
            
            <!-- Export Options -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Export Options') }}</h2>
                <div class="space-y-2">
                    <a href="{{ route('reports.export', ['report' => $report->id, 'format' => 'xlsx']) }}" class="flex items-center p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-file-excel text-green-600 mr-3"></i>
                        <span>{{ __('Export to Excel') }}</span>
                    </a>
                    <a href="{{ route('reports.export', ['report' => $report->id, 'format' => 'csv']) }}" class="flex items-center p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-file-csv text-blue-600 mr-3"></i>
                        <span>{{ __('Export to CSV') }}</span>
                    </a>
                    <a href="{{ route('reports.export', ['report' => $report->id, 'format' => 'pdf']) }}" class="flex items-center p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                        <span>{{ __('Export to PDF') }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Share Options -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Share Report') }}</h2>
                <form action="{{ route('reports.share', $report->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Share Type') }}</label>
                        <select name="share_type" class="w-full border-gray-300 rounded-md" required>
                            <option value="email">{{ __('Email') }}</option>
                            <option value="whatsapp">{{ __('WhatsApp') }}</option>
                            <option value="pdf">{{ __('PDF Download') }}</option>
                            <option value="excel">{{ __('Excel Download') }}</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Recipient') }}</label>
                        <input type="text" name="recipient" class="w-full border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Email or phone number for WhatsApp') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                        <input type="text" name="subject" class="w-full border-gray-300 rounded-md">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Message') }}</label>
                        <textarea name="message" rows="3" class="w-full border-gray-300 rounded-md"></textarea>
                    </div>
                    
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" name="scheduled" id="scheduled" value="1" class="rounded border-gray-300">
                        <label for="scheduled" class="ml-2">{{ __('Schedule recurring delivery') }}</label>
                    </div>
                    
                    <div class="mb-4 hidden" id="frequency-options">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Frequency') }}</label>
                        <select name="frequency" class="w-full border-gray-300 rounded-md">
                            <option value="daily">{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        {{ __('Share Report') }}
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Report Content goes here -->
                @if(isset($reportData['data']) && count($reportData['data']) > 0)
                    <!-- Basic report format -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach(array_keys(reset($reportData['data'])) as $header)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $header }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData['data'] as $row)
                                    <tr>
                                        @foreach($row as $value)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $value }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(isset($reportData['leads']) && isset($reportData['properties']))
                    <!-- Combined report format -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Leads Data') }}</h3>
                        
                        <div class="overflow-x-auto mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @if(!empty($reportData['leads']['data']))
                                            @foreach(array_keys(reset($reportData['leads']['data'])) as $header)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $header }}
                                                </th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($reportData['leads']['data'] ?? [] as $row)
                                        <tr>
                                            @foreach($row as $value)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('No lead data available') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-4">{{ __('Properties Data') }}</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @if(!empty($reportData['properties']['data']))
                                            @foreach(array_keys(reset($reportData['properties']['data'])) as $header)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $header }}
                                                </th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($reportData['properties']['data'] ?? [] as $row)
                                        <tr>
                                            @foreach($row as $value)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('No property data available') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-chart-bar text-4xl mb-3 opacity-30"></i>
                        <p>{{ __('No data available for this report.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduledCheckbox = document.getElementById('scheduled');
        const frequencyOptions = document.getElementById('frequency-options');
        
        scheduledCheckbox.addEventListener('change', function() {
            if (this.checked) {
                frequencyOptions.classList.remove('hidden');
            } else {
                frequencyOptions.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
