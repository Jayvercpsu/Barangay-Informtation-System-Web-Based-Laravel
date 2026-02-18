@extends('layouts.app')
@section('title', 'Certificate Request Details')
@section('page-title', 'Certificate Request Details')

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Request Number</p>
                <p class="font-mono font-semibold text-blue-600">{{ $certificateRequest->request_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Resident</p>
                <p class="font-medium text-gray-800">{{ $certificateRequest->resident->full_name }}</p>
                <p class="text-xs text-gray-500">{{ $certificateRequest->resident->resident_id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Certificate Type</p>
                <p class="text-gray-700 font-medium">{{ $certificateRequest->certificate_label }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Contact Number</p>
                <p class="text-gray-700">{{ $certificateRequest->resident->contact_number_1 }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-gray-500 mb-1">Purpose</p>
                <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $certificateRequest->purpose }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
        <form method="POST" action="{{ route('admin.certificates.status', $certificateRequest) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ $certificateRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $certificateRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="ready_for_pickup" {{ $certificateRequest->status === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="completed" {{ $certificateRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ $certificateRequest->admin_notes }}</textarea>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg text-sm text-blue-700">
                Setting status to <strong>Ready for Pickup</strong> will automatically send an email and SMS notification to the resident.
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Update Status
            </button>
        </form>
    </div>
</div>
@endsection