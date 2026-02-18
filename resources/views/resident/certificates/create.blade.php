@extends('layouts.app')
@section('title', 'Request Certificate')
@section('page-title', 'Request a Certificate')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <form method="POST" action="{{ route('resident.certificates.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Type *</label>
                <select name="certificate_type" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select certificate type</option>
                    <option value="barangay_clearance" {{ old('certificate_type') === 'barangay_clearance' ? 'selected' : '' }}>Barangay Clearance</option>
                    <option value="proof_of_residency" {{ old('certificate_type') === 'proof_of_residency' ? 'selected' : '' }}>Proof of Residency</option>
                    <option value="certificate_of_indigency" {{ old('certificate_type') === 'certificate_of_indigency' ? 'selected' : '' }}>Certificate of Indigency</option>
                    <option value="barangay_permit" {{ old('certificate_type') === 'barangay_permit' ? 'selected' : '' }}>Barangay Permit</option>
                </select>
                @error('certificate_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Purpose *</label>
                <textarea name="purpose" rows="4" required
                          placeholder="State the purpose for this certificate request..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('purpose') }}</textarea>
                @error('purpose') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Submit Request
                </button>
                <a href="{{ route('resident.certificates.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection