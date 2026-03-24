@extends('layouts.app')
@section('title', 'Certificate Requests')
@section('page-title', 'Certificate Requests')

@section('content')
<div class="space-y-5">
    <form method="GET" class="flex gap-3 flex-wrap">
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <select name="type" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            <option value="barangay_clearance">Barangay Clearance</option>
            <option value="proof_of_residency">Proof of Residency</option>
            <option value="certificate_of_indigency">Certificate of Indigency</option>
            <option value="barangay_permit">Barangay Permit</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium">Filter</button>
        <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">Reset</a>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto">
        <table class="datatable w-full text-sm min-w-[820px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Request #</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Resident</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Certificate</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600 text-xs">{{ $req->request_number }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $req->resident->full_name }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $req->certificate_label }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $req->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($req->status === 'ready_for_pickup' ? 'bg-blue-50 text-blue-700' :
                               ($req->status === 'in_progress' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700')) }}">
                            {{ ucwords(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $req->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.certificates.show', $req) }}" class="text-blue-600 hover:underline text-xs">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">No certificate requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
