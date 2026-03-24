@extends('layouts.app')
@section('title', 'Events Calendar')
@section('page-title', 'Events Calendar')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="overflow-x-auto">
                <div id="calendar" class="min-w-[640px] lg:min-w-0"></div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Add New Event</h3>
            <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Title *</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="event_date" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                    <input type="time" name="event_time"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="color" name="color" value="#3b82f6"
                           class="w-full h-10 px-2 py-1 border border-gray-200 rounded-lg cursor-pointer">
                </div>
                <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Add Event
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Upcoming Events</h3>
            <div class="space-y-3">
                @foreach($events->where('event_date', '>=', now()->toDateString())->take(5) as $event)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background-color: {{ $event->color }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400">{{ $event->event_date->format('M d, Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs" onclick="return confirm('Delete this event?')">×</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css'>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<div id="calendar-tooltip"
     class="hidden fixed max-w-xs rounded-lg border border-gray-200 bg-white shadow-xl px-3 py-2 text-xs text-gray-700 z-[9999] pointer-events-none">
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const tooltipEl = document.getElementById('calendar-tooltip');

    const escapeHtml = (value) => {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    const buildEventDetails = (event) => {
        const props = event.extendedProps || {};
        const details = [];

        if (props.event_date) details.push(`<div><span class="font-semibold">Date:</span> ${escapeHtml(props.event_date)}</div>`);
        if (props.event_time) details.push(`<div><span class="font-semibold">Time:</span> ${escapeHtml(props.event_time)}</div>`);
        if (props.location) details.push(`<div><span class="font-semibold">Location:</span> ${escapeHtml(props.location)}</div>`);
        if (props.description) details.push(`<div><span class="font-semibold">Details:</span> ${escapeHtml(props.description)}</div>`);

        return details.join('');
    };

    const positionTooltip = (mouseEvent) => {
        if (!tooltipEl || !mouseEvent) return;

        const offsetX = 14;
        const offsetY = 14;
        const maxLeft = window.innerWidth - tooltipEl.offsetWidth - 8;
        const maxTop = window.innerHeight - tooltipEl.offsetHeight - 8;

        const left = Math.max(8, Math.min(mouseEvent.clientX + offsetX, maxLeft));
        const top = Math.max(8, Math.min(mouseEvent.clientY + offsetY, maxTop));

        tooltipEl.style.left = `${left}px`;
        tooltipEl.style.top = `${top}px`;
    };

    const showTooltip = (event, mouseEvent) => {
        if (!tooltipEl) return;

        const details = buildEventDetails(event);
        if (!details) return;

        tooltipEl.innerHTML = `
            <div class="font-semibold text-gray-900 mb-1">${escapeHtml(event.title)}</div>
            <div class="space-y-1">${details}</div>
        `;
        tooltipEl.classList.remove('hidden');
        positionTooltip(mouseEvent);
    };

    const hideTooltip = () => {
        if (!tooltipEl) return;
        tooltipEl.classList.add('hidden');
    };

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: @json($eventsJson),
        height: 'auto',
        eventDidMount: function(info) {
            info.el.addEventListener('mouseenter', (event) => showTooltip(info.event, event));
            info.el.addEventListener('mousemove', positionTooltip);
            info.el.addEventListener('mouseleave', hideTooltip);
        },
        eventClick: function(info) {
            const props = info.event.extendedProps || {};
            const plainDetails = [
                props.event_date ? `Date: ${props.event_date}` : null,
                props.event_time ? `Time: ${props.event_time}` : null,
                props.location ? `Location: ${props.location}` : null,
                props.description ? `Details: ${props.description}` : null,
            ].filter(Boolean).join('\n');

            alert(plainDetails ? `${info.event.title}\n\n${plainDetails}` : info.event.title);
        }
    });

    calendarEl.addEventListener('mouseleave', hideTooltip);
    calendar.render();
});
</script>
@endsection
