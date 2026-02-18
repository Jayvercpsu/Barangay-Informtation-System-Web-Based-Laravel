@extends('layouts.app')
@section('title', 'Resident Details')
@section('page-title', 'Resident Details')

@section('content')
<div class="max-w-5xl space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.residents.index') }}"
           class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Residents
        </a>
        <a href="{{ route('admin.residents.edit', $resident) }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            Edit Resident
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-start gap-5">
            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($resident->profile_photo)
                    <img src="{{ Storage::url($resident->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-3xl font-bold text-blue-600">
                        {{ strtoupper(substr($resident->first_name, 0, 1)) }}
                    </span>
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between flex-wrap gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $resident->full_name }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $resident->user->email }}</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <span class="text-xs font-mono bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full font-semibold">
                            {{ $resident->resident_id }}
                        </span>
                        @if($resident->is_pwd)
                            <span class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-full font-medium">PWD</span>
                        @endif
                        @if($resident->isSenior())
                            <span class="text-xs bg-green-50 text-green-700 px-3 py-1.5 rounded-full font-medium">Senior Citizen</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Block</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $resident->block ? 'Block ' . $resident->block->block_number . ' — ' . $resident->block->name : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Age</p>
                        <p class="text-sm font-medium text-gray-700">{{ $resident->age }} years old</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Birthdate</p>
                        <p class="text-sm font-medium text-gray-700">{{ $resident->birthdate->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Occupation</p>
                        <p class="text-sm font-medium text-gray-700">{{ $resident->occupation ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Contact Information</h3>
            <div class="space-y-3">
                <div class="flex items-start justify-between py-2 border-b border-gray-50">
                    <span class="text-xs text-gray-400 w-32 flex-shrink-0">Address</span>
                    <span class="text-sm text-gray-700 text-right">{{ $resident->address }}</span>
                </div>
                <div class="flex items-start justify-between py-2 border-b border-gray-50">
                    <span class="text-xs text-gray-400 w-32 flex-shrink-0">Contact #1</span>
                    <span class="text-sm text-gray-700">{{ $resident->contact_number_1 }}</span>
                </div>
                <div class="flex items-start justify-between py-2 border-b border-gray-50">
                    <span class="text-xs text-gray-400 w-32 flex-shrink-0">Contact #2</span>
                    <span class="text-sm text-gray-700">{{ $resident->contact_number_2 ?? '—' }}</span>
                </div>
                <div class="flex items-start justify-between py-2">
                    <span class="text-xs text-gray-400 w-32 flex-shrink-0">Email</span>
                    <span class="text-sm text-gray-700">{{ $resident->user->email }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Activity Summary</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-3 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Total Complaints</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $resident->complaints->count() }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Certificate Requests</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $resident->certificateRequests->count() }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600">Member Since</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ $resident->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Complaints History</h3>
            <a href="{{ route('admin.complaints.index', ['search' => $resident->resident_id]) }}"
               class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        @if($resident->complaints->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Number</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Description</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Date Filed</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($resident->complaints->sortByDesc('created_at') as $complaint)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono text-blue-600 text-xs">{{ $complaint->complaint_number }}</td>
                    <td class="px-6 py-3 text-gray-600 max-w-xs truncate">{{ $complaint->description }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-blue-600 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-6 py-10 text-center text-sm text-gray-400">No complaints filed by this resident.</div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Certificate Requests</h3>
            <a href="{{ route('admin.certificates.index', ['search' => $resident->resident_id]) }}"
               class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        @if($resident->certificateRequests->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Number</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Certificate Type</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Purpose</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Date</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($resident->certificateRequests->sortByDesc('created_at') as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono text-blue-600 text-xs">{{ $req->request_number }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $req->certificate_label }}</td>
                    <td class="px-6 py-3 text-gray-500 max-w-xs truncate text-xs">{{ $req->purpose }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $req->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($req->status === 'ready_for_pickup' ? 'bg-blue-50 text-blue-700' :
                               ($req->status === 'in_progress' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700')) }}">
                            {{ ucwords(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $req->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.certificates.show', $req) }}" class="text-blue-600 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-6 py-10 text-center text-sm text-gray-400">No certificate requests from this resident.</div>
        @endif
    </div>

</div>
@endsection