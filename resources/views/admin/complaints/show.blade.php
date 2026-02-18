@extends('layouts.app')
@section('title', 'Complaint Details')
@section('page-title', 'Complaint Details')

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <p class="text-xs text-gray-500 mb-1">Complaint Number</p>
                <p class="font-mono font-semibold text-blue-600">{{ $complaint->complaint_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Filed By</p>
                <p class="font-medium text-gray-800">{{ $complaint->resident->full_name }}</p>
                <p class="text-xs text-gray-500">{{ $complaint->resident->resident_id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Block</p>
                <p class="text-gray-700">{{ $complaint->resident->block ? 'Block ' . $complaint->resident->block->block_number . ' — ' . $complaint->resident->block->name : '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Date Filed</p>
                <p class="text-gray-700">{{ $complaint->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mb-5">
            <p class="text-xs text-gray-500 mb-2">Description</p>
            <p class="text-gray-800 bg-gray-50 p-4 rounded-lg">{{ $complaint->description }}</p>
        </div>

        @if($complaint->image_path)
        <div class="mb-5">
            <p class="text-xs text-gray-500 mb-2">Attached Image</p>
            <img src="{{ Storage::url($complaint->image_path) }}" class="max-w-sm rounded-lg border border-gray-100">
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
        <form method="POST" action="{{ route('admin.complaints.status', $complaint) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="submitted" {{ $complaint->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="acknowledged" {{ $complaint->status === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                    <option value="completed" {{ $complaint->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ $complaint->admin_notes }}</textarea>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Update Status
            </button>
        </form>
    </div>
</div>
@endsection