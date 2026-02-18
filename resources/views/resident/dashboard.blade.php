@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Resident Dashboard')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden">
                @if($resident->profile_photo)
                    <img src="{{ Storage::url($resident->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($resident->first_name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $resident->full_name }}</h2>
                <p class="text-sm text-gray-500">Resident ID: <span class="font-mono font-semibold text-blue-600">{{ $resident->resident_id }}</span></p>
                <p class="text-sm text-gray-500">{{ $resident->block ? 'Block ' . $resident->block->block_number . ' — ' . $resident->block->name : 'No block assigned' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Total Complaints</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $resident->complaints->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Certificate Requests</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $resident->certificateRequests->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-lg font-semibold text-green-600 mt-1">Active Resident</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Recent Complaints</h3>
                <a href="{{ route('resident.complaints.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>
            @forelse($complaints as $complaint)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $complaint->complaint_number }}</p>
                        <p class="text-xs text-gray-400">{{ $complaint->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700' :
                           ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No complaints filed yet.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Certificate Requests</h3>
                <a href="{{ route('resident.certificates.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>
            @forelse($certRequests as $req)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $req->certificate_label }}</p>
                        <p class="text-xs text-gray-400">{{ $req->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $req->status === 'completed' ? 'bg-green-50 text-green-700' :
                           ($req->status === 'ready_for_pickup' ? 'bg-blue-50 text-blue-700' :
                           ($req->status === 'in_progress' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700')) }}">
                        {{ ucwords(str_replace('_', ' ', $req->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No certificate requests yet.</p>
            @endforelse
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('resident.complaints.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-medium hover:bg-amber-600 transition-colors">
            File a Complaint
        </a>
        <a href="{{ route('resident.certificates.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
            Request Certificate
        </a>
    </div>
</div>
@endsection