@extends('layouts.app')
@section('title', 'My Complaints')
@section('page-title', 'My Complaints')

@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">{{ $complaints->total() }} total complaints</p>
        <a href="{{ route('resident.complaints.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            + File New Complaint
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Complaint #</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Description</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($complaints as $complaint)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600">{{ $complaint->complaint_number }}</td>
                    <td class="px-5 py-3 text-gray-700 max-w-xs truncate">{{ $complaint->description }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $complaint->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($complaint->status === 'acknowledged' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('resident.complaints.show', $complaint) }}"
                           class="text-blue-600 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">No complaints found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $complaints->links() }}
</div>
@endsection