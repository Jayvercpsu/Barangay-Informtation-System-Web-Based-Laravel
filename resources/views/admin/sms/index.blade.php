@extends('layouts.app')
@section('title', 'Bulk SMS')
@section('page-title', 'Bulk SMS Sender')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.sms.send') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Recipients</label>
                <select name="filter_type" id="filter_type" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="toggleBlock(this.value)">
                    <option value="">Select recipients</option>
                    <option value="all" {{ old('filter_type') === 'all' ? 'selected' : '' }}>All Residents</option>
                    <option value="block" {{ old('filter_type') === 'block' ? 'selected' : '' }}>Specific Block</option>
                    <option value="pwd" {{ old('filter_type') === 'pwd' ? 'selected' : '' }}>PWD Residents Only</option>
                    <option value="senior" {{ old('filter_type') === 'senior' ? 'selected' : '' }}>Senior Residents (60+) Only</option>
                </select>
                @error('filter_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div id="block_select" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Block</label>
                <select name="block_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a block</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}" {{ (string) old('block_id') === (string) $block->id ? 'selected' : '' }}>
                            Block {{ $block->block_number }} - {{ $block->name }} ({{ $block->area_description }})
                        </option>
                    @endforeach
                </select>
                @error('block_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message (max 160 characters)</label>
                <textarea name="message" rows="4" maxlength="160" required
                          placeholder="Type your announcement here..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                          id="sms_message">{{ old('message') }}</textarea>
                <p class="text-xs text-gray-400 mt-1"><span id="char_count">0</span>/160 characters</p>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="p-4 bg-amber-50 border border-amber-100 rounded-lg text-sm text-amber-700">
                <strong>Note:</strong> SMS will be sent via {{ strtoupper((string) config('sms.provider', 'philsms')) }} gateway.
                {{ config('sms.test_mode') ? 'Currently in TEST MODE - messages are logged only.' : 'Live mode active.' }}
            </div>

            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Send Bulk SMS
            </button>
        </form>
    </div>
</div>

<script>
function toggleBlock(value) {
    document.getElementById('block_select').classList.toggle('hidden', value !== 'block');
}

function updateCharCount() {
    const msg = document.getElementById('sms_message');
    document.getElementById('char_count').textContent = msg.value.length;
}

document.getElementById('filter_type').value && toggleBlock(document.getElementById('filter_type').value);
updateCharCount();
document.getElementById('sms_message').addEventListener('input', updateCharCount);
</script>

@if(session('sms_debug'))
<script>
    const debug = @json(session('sms_debug'));
    console.log('%c SMS DEBUG ', 'background:#1d4ed8;color:white;font-weight:bold;padding:4px 8px;');
    console.log('Residents found:', debug.residents_found);
    console.log('Target cellphone numbers:', debug.targets_found ?? (debug.numbers ? debug.numbers.length : 0));
    console.log('Successfully sent:', debug.sent_count);
    console.log('Failed:', debug.failed_count ?? 0);
    console.log('Numbers targeted:', debug.numbers);
    console.group('SMS Per-Number Results');
    debug.logs.forEach((log, i) => {
        console.group(`#${i + 1} -> ${log.number}`);
        console.log('Normalized:', log.normalized);
        console.log('Success:', log.success);
        console.log('HTTP Status:', log.http_status);
        console.log('Gateway Response:', log.gateway_response);
        console.groupEnd();
    });
    console.groupEnd();
</script>
@endif
@endsection
