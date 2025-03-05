<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lead->first_name }} {{ $lead->last_name }} | {{ __('Lead Details') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-white print:bg-white {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }} p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header with Logo -->
        <div class="border-b pb-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ __('Lead Report') }}</h1>
                    <p class="text-gray-500">{{ __('Generated on') }}: {{ now()->format('F d, Y') }}</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-blue-600">RealEstate CRM</h2>
                    <p class="text-gray-500">{{ __('Lead ID') }}: {{ $lead->id }}</p>
                </div>
            </div>
        </div>
        
        <!-- Lead Summary -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ $lead->first_name }} {{ $lead->last_name }}</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Contact Information') }}</h3>
                    <table class="w-full">
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Email') }}:</td>
                            <td>{{ $lead->email ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Phone') }}:</td>
                            <td>{{ $lead->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Source') }}:</td>
                            <td>{{ $lead->source ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Added on') }}:</td>
                            <td>{{ $lead->created_at->format('F d, Y') }}</td>
                        </tr>
                    </table>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Lead Details') }}</h3>
                    <table class="w-full">
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Status') }}:</td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $lead->status == 'contacted' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $lead->status == 'qualified' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $lead->status == 'proposal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $lead->status == 'negotiation' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $lead->status == 'won' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $lead->status == 'lost' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Property Interest') }}:</td>
                            <td>{{ $lead->interestedProperty ? $lead->interestedProperty->name : 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Budget') }}:</td>
                            <td>{{ $lead->budget ? number_format($lead->budget, 2) : 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">{{ __('Assigned To') }}:</td>
                            <td>{{ $lead->assignedUser ? $lead->assignedUser->name : 'Not assigned' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Notes Section -->
        @if($lead->notes)
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Notes') }}</h3>
            <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                <p class="whitespace-pre-line">{{ $lead->notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Events Section -->
        @if(count($lead->events) > 0)
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Events & Scheduled Activities') }}</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left text-gray-600">{{ __('Type') }}</th>
                        <th class="p-2 text-left text-gray-600">{{ __('Title') }}</th>
                        <th class="p-2 text-left text-gray-600">{{ __('Date') }}</th>
                        <th class="p-2 text-left text-gray-600">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->events as $event)
                    <tr class="border-b">
                        <td class="p-2">{{ ucfirst($event->event_type) }}</td>
                        <td class="p-2">{{ $event->title }}</td>
                        <td class="p-2">{{ $event->event_date->format('M d, Y g:i A') }}</td>
                        <td class="p-2">
                            <span class="{{ $event->is_completed ? 'text-green-600' : 'text-blue-600' }}">
                                {{ $event->is_completed ? __('Completed') : __('Scheduled') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Activity History Section -->
        <div>
            <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Complete Activity History') }}</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left text-gray-600">{{ __('Date & Time') }}</th>
                        <th class="p-2 text-left text-gray-600">{{ __('Activity') }}</th>
                        <th class="p-2 text-left text-gray-600">{{ __('User') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->activityLogs as $log)
                    <tr class="border-b">
                        <td class="p-2 text-sm whitespace-nowrap">{{ $log->created_at->format('M d, Y g:i A') }}</td>
                        <td class="p-2">
                            <div class="text-sm">{{ $log->description }}</div>
                            
                            @if($log->action === 'added_note' && isset($log->details['note']))
                                <div class="mt-1 text-xs bg-gray-50 p-1 rounded">
                                    {{ $log->details['note'] }}
                                </div>
                            @endif
                            
                            @if($log->action === 'updated_lead' && isset($log->details['changes']))
                                <div class="mt-1 text-xs">
                                    <ul class="list-disc list-inside">
                                        @foreach($log->details['changes'] as $field => $change)
                                            <li>
                                                <strong>{{ ucwords(str_replace('_', ' ', $field)) }}</strong>: 
                                                {{ $change['old'] ?? 'empty' }} → {{ $change['new'] ?? 'empty' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td class="p-2 text-sm">{{ $log->user ? $log->user->name : __('System') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="mt-12 pt-6 border-t text-center text-gray-500 text-sm">
            <p>{{ __('This report was generated for internal use only.') }}</p>
            <p>{{ now()->format('Y') }} © RealEstate CRM</p>
        </div>
    </div>
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            // Uncomment the next line if you want the print dialog to show automatically
            // window.print();
        };
    </script>
</body>
</html>
