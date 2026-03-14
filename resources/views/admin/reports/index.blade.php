@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Resident Reports')

@section('content')
<div class="space-y-5">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="block_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Blocks</option>
            @foreach($blocks as $block)
                <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>
                    Block {{ $block->block_number }} — {{ $block->name }}
                </option>
            @endforeach
        </select>
        <select name="filter" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">No Special Filter</option>
            <option value="senior" {{ request('filter') === 'senior' ? 'selected' : '' }}>Senior Citizens (60+)</option>
            <option value="pwd" {{ request('filter') === 'pwd' ? 'selected' : '' }}>PWD Residents</option>
            <option value="no_occupation" {{ request('filter') === 'no_occupation' ? 'selected' : '' }}>No Occupation</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium">Generate Report</button>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">Reset</a>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Shown</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['senior'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Seniors</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['pwd'] }}</p>
            <p class="text-xs text-gray-500 mt-1">PWD</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $stats['no_occupation'] }}</p>
            <p class="text-xs text-gray-500 mt-1">No Occupation</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm min-w-[860px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Resident ID</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Full Name</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Block</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Age</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Occupation</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Tags</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($residents as $resident)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-blue-600 text-xs">{{ $resident->resident_id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $resident->full_name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $resident->block ? 'Block ' . $resident->block->block_number : '—' }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $resident->age }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $resident->occupation ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-1 flex-wrap">
                            @if($resident->is_pwd)
                                <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full">PWD</span>
                            @endif
                            @if($resident->isSenior())
                                <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Senior</span>
                            @endif
                            @if(!$resident->occupation)
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">No Occupation</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">No residents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
