<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lead->first_name }} {{ $lead->last_name }} | {{ __('Lead Details') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $lead->first_name }} {{ $lead->last_name }}</h1>
                    <p class="text-gray-500">{{ __('Added on') }}: {{ $lead->created_at->format('M d, Y') }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('leads.print', $lead->id) }}" target="_blank" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-print mr-2"></i> {{ __('Print') }}
                    </a>
                    <a href="{{ route('leads.edit', $lead->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-edit mr-2"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Leads') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-100 border-b">
        <div class="container mx-auto py-2 px-6">
            <div class="flex items-center space-x-3 overflow-x-auto pb-1">
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}" class="bg-white text-blue-600 py-1 px-3 rounded-md border border-gray-200 shadow-sm flex items-center whitespace-nowrap">
                    <i class="fas fa-envelope mr-2"></i> {{ __('Email') }}
                </a>
                @endif
                
                @if($lead->phone)
                <a href="tel:{{ $lead->phone }}" class="bg-white text-green-600 py-1 px-3 rounded-md border border-gray-200 shadow-sm flex items-center whitespace-nowrap">
                    <i class="fas fa-phone mr-2"></i> {{ __('Call') }}
                </a>
                
                <!-- WhatsApp Button -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="bg-white text-green-500 py-1 px-3 rounded-md border border-gray-200 shadow-sm flex items-center whitespace-nowrap">
                    <i class="fab fa-whatsapp mr-2 text-lg"></i> {{ __('WhatsApp') }}
                </a>
                @endif
                
                <button onclick="document.getElementById('schedule-event-modal').classList.remove('hidden')" 
                        class="bg-white text-indigo-600 py-1 px-3 rounded-md border border-gray-200 shadow-sm flex items-center whitespace-nowrap">
                    <i class="fas fa-calendar mr-2"></i> {{ __('Schedule Event') }}
                </button>
                
                <button onclick="document.getElementById('add-note-modal').classList.remove('hidden')" 
                        class="bg-white text-purple-600 py-1 px-3 rounded-md border border-gray-200 shadow-sm flex items-center whitespace-nowrap">
                    <i class="fas fa-sticky-note mr-2"></i> {{ __('Add Note') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Sidebar - Activity History -->
            <div class="w-full md:w-1/4 order-2 md:order-1">
                <!-- Upcoming Events Section - Now displayed first -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fas fa-calendar text-gray-600 mr-2"></i> {{ __('Upcoming Events') }}
                    </h2>
                    
                    @php
                        $upcoming_events = $lead->events()
                            ->where('status', '!=', 'completed')
                            ->orderBy('start_date', 'asc')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @forelse($upcoming_events as $event)
                        <div class="mb-4 pb-4 border-b last:border-b-0 last:mb-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $event->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="far fa-clock mr-1"></i> 
                                        {{ $event->start_date->format('M d, Y g:i A') }}
                                    </p>
                                    <p class="text-xs mt-1">
                                        <span class="px-2 py-0.5 rounded-full text-xs 
                                            {{ $event->event_type === 'meeting' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $event->event_type === 'call' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $event->event_type === 'email' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $event->event_type === 'birthday' ? 'bg-pink-100 text-pink-700' : '' }}
                                            {{ $event->event_type === 'follow_up' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $event->event_type === 'other' ? 'bg-gray-100 text-gray-700' : '' }}
                                        ">
                                            {{ ucfirst($event->event_type) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="flex space-x-1">
                                    <button onclick="showCompleteEventModal({{ $event->id }}, '{{ $event->title }}')" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete this event?') }}')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($event->description)
                                <p class="text-xs text-gray-600 mt-2">{{ $event->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">{{ __('No upcoming events') }}</p>
                    @endforelse
                    
                    <div class="mt-4 text-center">
                        <button onclick="document.getElementById('schedule-event-modal').classList.remove('hidden')" class="text-sm text-blue-600 hover:underline">
                            {{ __('Add New Event') }}
                        </button>
                    </div>
                </div>

                <!-- Activity History Section - Now displayed second -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fas fa-history text-gray-600 mr-2"></i> {{ __('Activity History') }}
                    </h2>
                    
                    <!-- Added a fixed height container with scrolling -->
                    <div class="overflow-y-auto max-h-[500px] pr-2">
                        <div class="timeline relative pl-8 before:absolute before:left-3 before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-200 space-y-6">
                            @forelse($activityLogs as $log)
                                <div class="relative">
                                    <!-- Timeline dot -->
                                    <div class="absolute -left-8 w-6 h-6 flex items-center justify-center rounded-full">
                                        <i class="fas fa-history text-xs"></i>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-800">{{ $log->description }}</p>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $log->created_at->format('M d, Y g:i A') }}
                                            @if($log->user)
                                                by {{ $log->user->name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">{{ __('No activity yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="w-full md:w-3/4 order-1 md:order-2">
                <!-- Lead Information -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex flex-wrap md:flex-nowrap">
                            <!-- Personal Information -->
                            <div class="w-full md:w-1/2 md:pr-4 mb-6 md:mb-0">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                                    <i class="fas fa-user text-gray-600 mr-2"></i> {{ __('Personal Information') }}
                                </h2>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-user text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Name') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-envelope text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Email') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">
                                        @if($lead->email)
                                            <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline">{{ $lead->email }}</a>
                                        @else
                                            {{ __('Not provided') }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-phone text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Phone') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">
                                        @if($lead->phone)
                                            <div class="flex items-center space-x-2">
                                                <a href="tel:{{ $lead->phone }}" class="text-blue-600 hover:underline">{{ $lead->phone }}</a>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="inline-flex items-center text-green-500 hover:text-green-700">
                                                    <i class="fab fa-whatsapp text-lg"></i>
                                                    <span class="ml-1 text-xs">{{ __('WhatsApp') }}</span>
                                                </a>
                                            </div>
                                        @else
                                            {{ __('Not provided') }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-sign-in-alt text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Source') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">{{ $lead->source ?? __('Unknown') }}</p>
                                </div>
                            </div>
                            
                            <!-- Lead Information -->
                            <div class="w-full md:w-1/2 md:pl-4">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                                    <i class="fas fa-briefcase text-gray-600 mr-2"></i> {{ __('Lead Information') }}
                                </h2>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-chart-line text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Status') }}</span>
                                    </div>
                                    <div class="pl-8">
                                        <span class="px-3 py-1 text-xs rounded-full 
                                            {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $lead->status == 'contacted' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                            {{ $lead->status == 'qualified' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $lead->status == 'proposal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $lead->status == 'negotiation' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $lead->status == 'won' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $lead->status == 'lost' ? 'bg-red-100 text-red-700' : '' }}
                                        ">
                                            {{ __(ucfirst($lead->status)) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-home text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Property Interest') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">
                                        @if ($lead->interestedProperty)
                                            <a href="{{ route('properties.show', $lead->property_interest) }}" class="text-blue-600 hover:underline">{{ $lead->interestedProperty->name }}</a>
                                        @else
                                            {{ __('Not specified') }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-dollar-sign text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Budget') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">{{ $lead->budget ? number_format($lead->budget, 2) : __('Not specified') }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-user-tie text-blue-600 mr-2 w-6"></i>
                                        <span class="text-sm font-medium text-gray-600">{{ __('Assigned To') }}</span>
                                    </div>
                                    <p class="text-gray-800 pl-8">{{ $lead->assignedUser ? $lead->assignedUser->name : __('Not assigned') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes Section -->
                        @if($lead->notes)
                        <div class="mt-6 pt-4 border-t">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Notes') }}</h2>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700 whitespace-pre-line">{{ $lead->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 flex justify-between">
                    <div>
                        <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this lead?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete Lead') }}
                            </button>
                        </form>
                    </div>
                    <div>
                        <a href="{{ route('leads.edit', $lead->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md">
                            <i class="fas fa-edit mr-2"></i> {{ __('Edit Lead') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Event Modal -->
    <div id="schedule-event-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Schedule Event') }}</h3>
                <button onclick="document.getElementById('schedule-event-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('events.store') }}" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="mb-4">
                    <label for="event_title" class="block text-sm font-medium text-gray-700">{{ __('Event Title') }}</label>
                    <input type="text" name="title" id="event_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="event_date" class="block text-sm font-medium text-gray-700">{{ __('Event Date') }}</label>
                    <input type="text" name="start_date" id="event_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm flatpickr" required>
                </div>
                <div class="mb-4">
                    <label for="event_type" class="block text-sm font-medium text-gray-700">{{ __('Event Type') }}</label>
                    <select name="event_type" id="event_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="meeting">{{ __('Meeting') }}</option>
                        <option value="call">{{ __('Call') }}</option>
                        <option value="email">{{ __('Email') }}</option>
                        <option value="birthday">{{ __('Birthday') }}</option>
                        <option value="follow_up">{{ __('Follow Up') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="event_description" class="block text-sm font-medium text-gray-700">{{ __('Event Description') }}</label>
                    <textarea name="description" id="event_description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('schedule-event-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div id="add-note-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Add Note') }}</h3>
                <button onclick="document.getElementById('add-note-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('leads.add-note', $lead->id) }}" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="mb-4">
                    <label for="note_content" class="block text-sm font-medium text-gray-700">{{ __('Note') }}</label>
                    <textarea name="note" id="note_content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('add-note-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Complete Event Modal -->
    <div id="complete-event-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">{{ __('Complete Event') }}</h3>
                <button onclick="document.getElementById('complete-event-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="complete-event-form" method="POST" class="p-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="event_id" id="complete_event_id">
                <div class="mb-4">
                    <label for="complete_event_title" class="block text-sm font-medium text-gray-700">{{ __('Event Title') }}</label>
                    <input type="text" id="complete_event_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
                </div>
                <div class="mb-4">
                    <label for="complete_event_notes" class="block text-sm font-medium text-gray-700">{{ __('Completion Notes') }}</label>
                    <textarea name="completion_notes" id="complete_event_notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('complete-event-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('Complete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.flatpickr', {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
            });
        });

        function showCompleteEventModal(eventId, eventTitle) {
            document.getElementById('complete_event_id').value = eventId;
            document.getElementById('complete_event_title').value = eventTitle;
            document.getElementById('complete-event-form').action = "/events/" + eventId + "/complete";
            document.getElementById('complete-event-modal').classList.remove('hidden');
        }
    </script>

    @include('components.layouts.alert-scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add modern confirmation for delete
            document.querySelector('form[action$="{{ $lead->id }}"]').addEventListener('submit', function(event) {
                event.preventDefault();
                
                window.confirmDelete({
                    title: '{{ __("Delete Lead") }}',
                    text: '{{ __("Are you sure you want to delete") }} {{ $lead->first_name }} {{ $lead->last_name }}? {{ __("This action cannot be undone.") }}',
                    icon: 'error',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: '{{ __("Deleting...") }}',
                            html: '{{ __("Please wait") }}',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
