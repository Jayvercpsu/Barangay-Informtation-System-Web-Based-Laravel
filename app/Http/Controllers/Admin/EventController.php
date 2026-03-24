<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date')->get();
        $eventsJson = $events->map(fn($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'start' => $e->event_date->format('Y-m-d'),
            'color' => $e->color,
            'extendedProps' => [
                'description' => $e->description,
                'location' => $e->location,
                'event_date' => $e->event_date->format('M d, Y'),
                'event_time' => $e->event_time ? Carbon::parse($e->event_time)->format('h:i A') : null,
            ],
        ]);
        return view('admin.events.index', compact('events', 'eventsJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        Event::create($request->all());
        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $event->update($request->all());
        return back()->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }
}
