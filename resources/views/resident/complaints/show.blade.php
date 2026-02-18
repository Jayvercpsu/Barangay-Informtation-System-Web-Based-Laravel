@extends('layouts.app')
@section('title', 'Complaint Details')
@section('page-title', 'Complaint Details')

@section('content')
<div class="max-w-2xl space-y-5">

    <a href="{{ route('resident.complaints.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Complaints
    </a>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">

        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs text-gray-400 mb-1">Complaint Number</p>
                <p class="text-lg font-mono font-bold text-blue-600">{{ $complaint->complaint_number }}</p>
            </div>
            <span class="text-sm px-3 py-1.5 rounded-full font-medium flex-shrink-0
                {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700 border border-green-100' :
                   ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-amber-50 text-amber-700 border border-amber-100') }}">
                {{ ucfirst($complaint->status) }}
            </span>
        </div>

        <div class="border-t border-gray-50 pt-5">
            <p class="text-xs text-gray-400 mb-2">Description</p>
            <p class="text-gray-800 text-sm leading-relaxed bg-gray-50 rounded-xl p-4">{{ $complaint->description }}</p>
        </div>

        @if($complaint->image_path)
        <div>
            <p class="text-xs text-gray-400 mb-2">Attached Image</p>
            <img src="{{ Storage::url($complaint->image_path) }}"
                 alt="Complaint image"
                 class="max-w-full rounded-xl border border-gray-100 shadow-sm">
        </div>
        @endif

        @if($complaint->admin_notes)
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
            <p class="text-xs font-medium text-blue-600 mb-1">Admin Response</p>
            <p class="text-sm text-blue-800">{{ $complaint->admin_notes }}</p>
        </div>
        @endif

    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Status Timeline</h3>
        <ol class="relative border-l border-gray-200 space-y-6 ml-3">

            <li class="ml-5">
                <div class="absolute -left-1.5 w-3 h-3 rounded-full bg-green-500 border-2 border-white shadow"></div>
                <p class="text-sm font-medium text-gray-800">Complaint Submitted</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $complaint->created_at->format('M d, Y — h:i A') }}</p>
            </li>

            <li class="ml-5">
                <div class="absolute -left-1.5 w-3 h-3 rounded-full border-2 border-white shadow
                    {{ $complaint->acknowledged_at ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                <p class="text-sm font-medium {{ $complaint->acknowledged_at ? 'text-gray-800' : 'text-gray-400' }}">
                    Acknowledged by Admin
                </p>
                @if($complaint->acknowledged_at)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $complaint->acknowledged_at->format('M d, Y — h:i A') }}</p>
                @else
                    <p class="text-xs text-gray-300 mt-0.5">Pending</p>
                @endif
            </li>

            <li class="ml-5">
                <div class="absolute -left-1.5 w-3 h-3 rounded-full border-2 border-white shadow
                    {{ $complaint->completed_at ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                <p class="text-sm font-medium {{ $complaint->completed_at ? 'text-gray-800' : 'text-gray-400' }}">
                    Resolved / Completed
                </p>
                @if($complaint->completed_at)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $complaint->completed_at->format('M d, Y — h:i A') }}</p>
                @else
                    <p class="text-xs text-gray-300 mt-0.5">Pending</p>
                @endif
            </li>

        </ol>
    </div>

    <div class="text-center pt-2">
        <p class="text-xs text-gray-400">
            Need help? Visit the Barangay Hall or file a new complaint if this issue is unresolved.
        </p>
    </div>

</div>
@endsection