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

    <div class="bg-white rounded-2xl border border-gray-100 overflow-visible">
        <table class="w-full text-sm" style="position: relative;">
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
                        <div class="relative inline-block" x-data="{ open: false }" style="position: static;">
                            <button @click="open = !open" @click.outside="open = false"
                                    class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="fixed w-36 bg-white rounded-xl shadow-xl border border-gray-100 py-1 origin-top-right"
                                 style="z-index: 9999;"
                                 :style="`position: fixed; top: ${$el.parentElement.querySelector('button').getBoundingClientRect().bottom + 4}px; left: ${$el.parentElement.querySelector('button').getBoundingClientRect().right - 144}px;`">
                                <a href="{{ route('admin.residents.show', $resident) }}"
                                   class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('admin.residents.edit', $resident) }}"
                                   class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <hr class="my-1 border-gray-100">
                                <button @click="open = false; $dispatch('open-delete-modal', { id: {{ $resident->id }}, name: '{{ addslashes($resident->full_name) }}' })"
                                        class="flex items-center gap-2 w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6V4h8v2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 6l-1 14H6L5 6"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
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
    {{ $residents->withQueryString()->links() }}
</div>

<div x-data="deleteModal()" @open-delete-modal.window="open($event.detail)" x-show="show"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/30 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="show = false" style="display:none">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-center w-12 h-12 bg-red-50 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 text-center">Delete Resident?</h3>
        <p class="text-sm text-gray-500 text-center mt-1 mb-6">
            Are you sure you want to delete <span class="font-medium text-gray-800" x-text="residentName"></span>? This action cannot be undone.
        </p>
        <div class="flex gap-3">
            <button @click="show = false"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <form method="POST" :action="formAction" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-500 text-danger text-sm font-medium rounded-xl hover:bg-red-600 transition-colors">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function deleteModal() {
    return {
        show: false,
        residentName: '',
        formAction: '',
        open(detail) {
            this.residentName = detail.name;
            this.formAction = `/admin/residents/${detail.id}`;
            this.show = true;
        }
    }
}
</script>
@endsection