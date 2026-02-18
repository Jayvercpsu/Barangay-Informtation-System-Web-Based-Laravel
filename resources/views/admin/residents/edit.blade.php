@extends('layouts.app')
@section('title', 'Edit Resident')
@section('page-title', 'Edit Resident')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">Resident ID: <span class="font-mono font-bold">{{ $resident->resident_id }}</span></p>
        </div>

        <form method="POST" action="{{ route('admin.residents.update', $resident) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $resident->user->email) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                <input type="text" name="address" value="{{ old('address', $resident->address) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number 1 *</label>
                    <input type="text" name="contact_number_1" value="{{ old('contact_number_1', $resident->contact_number_1) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number 2</label>
                    <input type="text" name="contact_number_2" value="{{ old('contact_number_2', $resident->contact_number_2) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate *</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $resident->birthdate->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $resident->occupation) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Block Assignment *</label>
                <select name="block_id" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}" {{ $resident->block_id == $block->id ? 'selected' : '' }}>
                            Block {{ $block->block_number }} — {{ $block->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_pwd" id="is_pwd" value="1"
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                       {{ $resident->is_pwd ? 'checked' : '' }}>
                <label for="is_pwd" class="text-sm text-gray-700">Person with Disability (PWD)</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Update Resident
                </button>
                <a href="{{ route('admin.residents.show', $resident) }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection