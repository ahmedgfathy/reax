<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isManager = $user->team && ($user->team->leader_id === $user->id || in_array('manager', $user->getRoles()));

        // Get events based on user role
        $events = Event::when(!$isManager, function($query) use ($user) {
                    return $query->where('created_by', $user->id)
                               ->orWhereJsonContains('attendees', (string)$user->id); // Convert to string
                })
                ->when($isManager && $user->team, function($query) use ($user) {
                    return $query->whereIn('created_by', $user->team->members->pluck('id'))
                               ->orWhereJsonContains('attendees', (string)$user->id); // Convert to string
                })
                ->get();

        // Map events to calendar format
        $calendarEvents = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d H:i:s'),
                'end' => $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
                'className' => $this->getEventClass($event->event_type),
                'description' => $event->description ?? '',
                'allDay' => !$event->end_date
            ];
        })->toArray();

        \Log::info('Calendar Events:', ['count' => count($calendarEvents), 'events' => $calendarEvents]);

        return view('calendar.index', ['events' => $calendarEvents]);
    }

    private function getEventClass($type)
    {
        return match($type) {
            'meeting' => 'bg-blue-100 text-blue-800',
            'reminder' => 'bg-yellow-100 text-yellow-800',
            'follow_up' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
