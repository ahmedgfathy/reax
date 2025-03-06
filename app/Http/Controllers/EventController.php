<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lead;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:meeting,call,email,birthday,follow_up,other',
            'event_date' => 'required|date',
        ]);
        
        $validated['user_id'] = auth()->id();
        
        $event = Event::create($validated);
        
        // Get lead name for better context in the activity log
        $lead = Lead::find($request->lead_id);
        $leadName = $lead ? $lead->full_name : 'Unknown';
        
        // Generate a detailed description based on event type
        $eventTypeMap = [
            'meeting' => 'Meeting',
            'call' => 'Call',
            'email' => 'Email follow-up',
            'birthday' => 'Birthday reminder',
            'follow_up' => 'Follow-up',
            'other' => 'Event'
        ];
        
        $eventTypeName = $eventTypeMap[$event->event_type] ?? 'Event';
        $description = "Scheduled {$eventTypeName}: \"{$event->title}\" for {$leadName} on " . 
                      $event->event_date->format('M d, Y g:i A');
        
        // Log this activity with detailed information
        ActivityLog::log(
            $request->lead_id, 
            'created_event', 
            $description,
            [
                'event_id' => $event->id,
                'event_date' => $event->event_date,
                'event_type' => $event->event_type,
                'title' => $event->title,
                'description' => $event->description
            ]
        );
        
        return redirect()->back()->with('success', $eventTypeName . ' scheduled successfully!');
    }
    
    /**
     * Update the status of an event (mark as completed)
     */
    public function complete(Request $request, Event $event)
    {
        $validated = $request->validate([
            'completion_notes' => 'nullable|string',
        ]);
        
        // Save original data before updating
        $originalData = [
            'is_completed' => $event->is_completed,
            'completion_notes' => $event->completion_notes,
        ];
        
        $event->update([
            'is_completed' => true,
            'completion_notes' => $validated['completion_notes'] ?? null
        ]);
        
        // Get lead name for better context
        $lead = $event->lead;
        $leadName = $lead ? $lead->full_name : 'Unknown';
        
        // Generate a detailed description based on event type
        $eventTypeMap = [
            'meeting' => 'Meeting',
            'call' => 'Call',
            'email' => 'Email follow-up',
            'birthday' => 'Birthday greeting',
            'follow_up' => 'Follow-up',
            'other' => 'Event'
        ];
        
        $eventTypeName = $eventTypeMap[$event->event_type] ?? 'Event';
        $description = "Completed {$eventTypeName}: \"{$event->title}\" for {$leadName}";
        
        if (!empty($validated['completion_notes'])) {
            $description .= " with notes";
        }
        
        // Log this activity with detailed information
        ActivityLog::log(
            $event->lead_id, 
            'completed_event', 
            $description,
            [
                'event_id' => $event->id,
                'event_date' => $event->event_date,
                'event_type' => $event->event_type,
                'title' => $event->title,
                'description' => $event->description,
                'completion_notes' => $event->completion_notes,
                'original_data' => $originalData,
            ]
        );
        
        return redirect()->back()->with('success', $eventTypeName . ' marked as completed!');
    }
    
    /**
     * Delete an event
     */
    public function destroy(Event $event)
    {
        $leadId = $event->lead_id;
        $eventTitle = $event->title;
        $eventType = $event->event_type;
        
        // Save event data before deleting for the activity log
        $eventData = [
            'id' => $event->id,
            'lead_id' => $event->lead_id,
            'user_id' => $event->user_id,
            'title' => $event->title,
            'description' => $event->description,
            'event_type' => $event->event_type,
            'event_date' => $event->event_date,
            'is_completed' => $event->is_completed,
            'completion_notes' => $event->completion_notes,
        ];
        
        $event->delete();
        
        // Get lead name for better context
        $lead = Lead::find($leadId);
        $leadName = $lead ? $lead->full_name : 'Unknown';
        
        // Generate a detailed description based on event type
        $eventTypeMap = [
            'meeting' => 'Meeting',
            'call' => 'Call',
            'email' => 'Email follow-up',
            'birthday' => 'Birthday reminder',
            'follow_up' => 'Follow-up',
            'other' => 'Event'
        ];
        
        $eventTypeName = $eventTypeMap[$eventType] ?? 'Event';
        $description = "Deleted {$eventTypeName}: \"{$eventTitle}\" for {$leadName}";
        
        // Log this activity with detailed information about the deleted event
        ActivityLog::log(
            $leadId, 
            'deleted_event', 
            $description,
            [
                'event_data' => $eventData
            ]
        );
        
        return redirect()->back()->with('success', $eventTypeName . ' deleted successfully!');
    }
}
