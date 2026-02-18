@extends('layouts.app')
@section('title', 'Residents')
@section('page-title', 'Residents Management')

@section('content')
<div class="space-y-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or ID..."
               class="flex-1 min-w-48 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="block_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Blocks</option>
            @foreach($blocks as $block)
                <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>
                    Block {{ $block->block_number }} — {{ $block->name }}
                </option>
            @endforeach
        </select>
        <select name="filter" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Residents</option>
            <option value="pwd" {{ request('filter') === 'pwd' ? 'selected' : '' }}>PWD</option>
            <option value="senior" {{ request('filter') === 'senior' ? 'selected' : '' }}>Senior (60+)</option>
            <option value="no_occupation" {{ request('filter') === 'no_occupation' ? 'selected' : '' }}>No Occupation</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium">Search</button>
        <a href="{{ route('admin.residents.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">Reset</a>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Resident ID</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Full Name</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Block</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Contact</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Age</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Tags</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($residents as $resident)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600 text-xs">{{ $resident->resident_id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $resident->full_name }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $resident->block ? 'Block ' . $resident->block->block_number : '—' }}</td>
                    <td class="px-5 py-3 text-gray-600 text-xs">{{ $resident->contact_number_1 }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $resident->age }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-1">
                            @if($resident->is_pwd) <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full">PWD</span> @endif
                            @if($resident->isSenior()) <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Senior</span> @endif
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.residents.show', $resident) }}" class="text-blue-600 hover:underline text-xs mr-3">View</a>
                        <a href="{{ route('admin.residents.edit', $resident) }}" class="text-gray-500 hover:underline text-xs">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">No residents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $residents->links() }}
</div>
@endsection