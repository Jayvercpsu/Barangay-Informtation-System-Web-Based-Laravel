@extends('layouts.app')
@section('title', 'Complaints Management')
@section('page-title', 'Complaints Management')

@section('content')
<div class="space-y-5">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search complaints..."
               class="flex-1 min-w-52 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium">Filter</button>
        <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">Reset</a>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm min-w-[860px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Complaint #</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Resident</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Block</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Description</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($complaints as $complaint)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600 text-xs">{{ $complaint->complaint_number }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $complaint->resident->full_name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $complaint->resident->block ? 'Block ' . $complaint->resident->block->block_number : '—' }}</td>
                    <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $complaint->description }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-blue-600 hover:underline text-xs">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">No complaints found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $complaints->links() }}
</div>
@endsection
