@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
        @php
            $widgets = [
                ['label' => 'Total Residents', 'value' => $totalResidents, 'color' => 'text-blue-600'],
                ['label' => 'Total Complaints', 'value' => $totalComplaints, 'color' => 'text-amber-600'],
                ['label' => 'Pending Certificates', 'value' => $pendingCertificates, 'color' => 'text-purple-600'],
                ['label' => 'PWD Residents', 'value' => $totalPwd, 'color' => 'text-red-600'],
                ['label' => 'Senior Residents', 'value' => $totalSeniors, 'color' => 'text-green-600'],
            ];
        @endphp
        @foreach($widgets as $w)
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-500 mb-1">{{ $w['label'] }}</p>
            <p class="text-2xl font-bold {{ $w['color'] }}">{{ $w['value'] }}</p>
        </div>
        @endforeach
    </div>

    @if($birthdayToday->count() > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <h3 class="font-semibold text-amber-800 mb-3">🎂 Birthdays Today (Seniors & PWD)</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($birthdayToday as $r)
                <span class="bg-white text-amber-700 text-sm px-3 py-1 rounded-full border border-amber-200">
                    {{ $r->full_name }}
                    @if($r->is_pwd) <span class="text-xs">(PWD)</span> @endif
                    @if($r->isSenior()) <span class="text-xs">(Senior)</span> @endif
                </span>
            @endforeach
        </div>
    </div>
    @endif

    @if($upcomingBirthdays->count() > 0)
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <h3 class="font-semibold text-blue-800 mb-3">🎁 Upcoming Birthdays — Next 3 Days (Seniors & PWD)</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($upcomingBirthdays as $r)
                <span class="bg-white text-blue-700 text-sm px-3 py-1 rounded-full border border-blue-100">
                    {{ $r->full_name }} ({{ $r->birthdate->setYear(date('Y'))->format('M d') }})
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-blue-600 hover:underline">Manage</a>
            </div>
            @forelse($upcomingEvents as $event)
            <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                <div class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background-color: {{ $event->color }}"></div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $event->title }}</p>
                    <p class="text-xs text-gray-400">{{ $event->event_date->format('M d, Y') }} {{ $event->event_time ? '• ' . $event->event_time : '' }}</p>
                    @if($event->location) <p class="text-xs text-gray-400">{{ $event->location }}</p> @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-4 text-center">No upcoming events.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Recent Complaints</h3>
                <a href="{{ route('admin.complaints.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>
            @forelse($recentComplaints as $complaint)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $complaint->resident->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $complaint->complaint_number }} • {{ $complaint->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700' :
                       ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-4 text-center">No complaints yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
