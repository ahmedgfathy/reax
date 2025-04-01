<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">
                        {{ __('Dashboard') }}
                    </a>
                    <span class="px-2">/</span>
                    <span class="text-gray-700">{{ __('Calendar') }}</span>
                </div>
            </div>
            <div>
                <button onclick="openEventModal()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Add Event') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div id="calendar"></div>
    </div>

    <!-- Event Modal -->
    <div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Add New Event') }}</h3>
                <form id="eventForm" method="POST" action="{{ route('events.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                            <input type="text" name="title" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                            <select name="event_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="meeting">{{ __('Meeting') }}</option>
                                <option value="reminder">{{ __('Reminder') }}</option>
                                <option value="follow_up">{{ __('Follow Up') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Start Date & Time') }}</label>
                            <input type="datetime-local" name="start_date" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea name="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" onclick="closeEventModal()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                plugins: ['dayGrid', 'timeGrid'],
                events: {!! json_encode($events) !!},
                height: 800,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                eventClick: function(info) {
                    alert(info.event.title + '\n' + info.event.extendedProps.description);
                }
            });
            calendar.render();
        });

        function openEventModal() {
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }
    </script>
    @endpush

    <style>
        .fc { 
            height: 100%;
            background: white;
        }
        .fc-view-harness {
            background: white;
        }
        .fc-toolbar-title {
            font-size: 1.25em !important;
            font-weight: 600 !important;
        }
        .fc-header-toolbar {
            padding: 1rem;
        }
        .fc-day-today {
            background: #EFF6FF !important;
        }
        .fc-event {
            cursor: pointer;
            padding: 2px 4px;
        }
        #calendar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</x-app-layout>
