@extends('layouts.app')
@section('title', 'Certificate Requests')
@section('page-title', 'My Certificate Requests')

@section('content')
<div class="space-y-5">
    <div class="flex flex-wrap justify-between items-center gap-3">
        <p class="text-sm text-gray-500">{{ $requests->count() }} total requests</p>
        <a href="{{ route('resident.certificates.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            + New Request
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto">
        <table class="datatable w-full text-sm min-w-[700px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Request #</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Certificate Type</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Purpose</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600">{{ $req->request_number }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $req->certificate_label }}</td>
                    <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $req->purpose }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $req->status === 'completed' ? 'bg-green-50 text-green-700' :
                               ($req->status === 'ready_for_pickup' ? 'bg-blue-50 text-blue-700' :
                               ($req->status === 'in_progress' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700')) }}">
                            {{ ucwords(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $req->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">No certificate requests yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
