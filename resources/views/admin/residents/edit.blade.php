@extends('layouts.app')
@section('title', 'Edit Resident')
@section('page-title', 'Edit Resident')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

    .er-wrap * {
        font-family: 'DM Sans', sans-serif;
        box-sizing: border-box;
    }

    .er-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 8px 0 40px;
    }

    /* Page header */
    .er-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
    }
    .er-page-header-left h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f172a;
        letter-spacing: -0.4px;
        margin: 0 0 4px;
    }
    .er-page-header-left p {
        font-size: 0.82rem;
        color: #94a3b8;
        margin: 0;
        font-weight: 400;
    }

    /* Resident ID badge */
    .er-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 8px 14px;
        flex-shrink: 0;
    }
    .er-id-badge .label {
        font-size: 0.75rem;
        color: #0ea5e9;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .er-id-badge .value {
        font-family: 'DM Mono', monospace;
        font-size: 0.85rem;
        font-weight: 500;
        color: #0369a1;
        background: #e0f2fe;
        padding: 2px 8px;
        border-radius: 6px;
    }

    /* Card */
    .er-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e8edf3;
        box-shadow: 0 1px 3px rgba(15,23,42,0.05), 0 8px 32px rgba(15,23,42,0.04);
        overflow: hidden;
    }

    /* Section header inside card */
    .er-section {
        padding: 28px 32px;
        border-bottom: 1px solid #f1f5f9;
    }
    .er-section:last-child {
        border-bottom: none;
    }
    .er-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .er-section-title .icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .er-section-title .icon svg {
        width: 16px;
        height: 16px;
    }
    .er-section-title .icon.blue  { background: #e0f2fe; color: #0284c7; }
    .er-section-title .icon.green { background: #dcfce7; color: #16a34a; }
    .er-section-title .icon.amber { background: #fef9c3; color: #ca8a04; }
    .er-section-title .icon.rose  { background: #fce7f3; color: #db2777; }
    .er-section-title h2 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        letter-spacing: -0.1px;
    }
    .er-section-title p {
        font-size: 0.76rem;
        color: #94a3b8;
        margin: 2px 0 0;
    }

    /* Grid */
    .er-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .er-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    @media (max-width: 680px) {
        .er-grid-2, .er-grid-3 { grid-template-columns: 1fr; }
        .er-section { padding: 20px 18px; }
        .er-page-header { flex-direction: column; }
    }

    /* Field */
    .er-field { display: flex; flex-direction: column; gap: 5px; }
    .er-label {
        font-size: 0.78rem;
        font-weight: 500;
        color: #475569;
        letter-spacing: 0.1px;
    }
    .er-label .req {
        color: #f43f5e;
        margin-left: 2px;
    }

    .er-input,
    .er-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.875rem;
        color: #0f172a;
        background: #ffffff;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
    }
    .er-input::placeholder { color: #cbd5e1; }
    .er-input:hover, .er-select:hover { border-color: #cbd5e1; }
    .er-input:focus, .er-select:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
        background: #f0f9ff;
    }
    .er-input:disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .er-select-wrap {
        position: relative;
    }
    .er-select-wrap::after {
        content: '';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #94a3b8;
        pointer-events: none;
    }
    .er-select { padding-right: 36px; cursor: pointer; }

    /* Password input wrapper */
    .er-pw-wrap { position: relative; }
    .er-pw-wrap .er-input { padding-right: 70px; }
    .er-pw-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        letter-spacing: 0.3px;
        transition: background 0.15s, color 0.15s;
    }
    .er-pw-toggle:hover { background: #e2e8f0; color: #0f172a; }

    /* Error */
    .er-error {
        font-size: 0.73rem;
        color: #f43f5e;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Hint */
    .er-hint {
        font-size: 0.73rem;
        color: #94a3b8;
    }

    /* Checkbox toggle */
    .er-check-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        background: #fff;
    }
    .er-check-row:hover { border-color: #38bdf8; background: #f0f9ff; }
    .er-check-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #0ea5e9;
        cursor: pointer;
        flex-shrink: 0;
    }
    .er-check-row .er-check-label { font-size: 0.875rem; color: #1e293b; font-weight: 500; }
    .er-check-row .er-check-desc { font-size: 0.75rem; color: #94a3b8; margin-top: 1px; }

    /* Password section */
    .er-pw-section {
        background: #fafbfc;
        border-radius: 14px;
        padding: 20px;
        border: 1.5px dashed #e2e8f0;
    }
    .er-pw-current {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #f1f5f9;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .er-pw-current .pw-icon {
        width: 26px; height: 26px;
        background: #e2e8f0;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .er-pw-current .pw-icon svg { width: 13px; height: 13px; color: #64748b; }
    .er-pw-current span { font-size: 0.81rem; color: #64748b; font-style: italic; }

    /* Divider */
    .er-divider {
        border: none;
        border-top: 1px solid #f1f5f9;
        margin: 0;
    }

    /* Footer actions */
    .er-footer {
        padding: 20px 32px;
        background: #fafbfc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid #f1f5f9;
    }
    @media (max-width: 500px) {
        .er-footer { flex-direction: column; padding: 16px 18px; }
        .er-footer-actions { width: 100%; }
        .er-btn { width: 100%; justify-content: center; }
    }
    .er-footer-hint {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .er-footer-hint span { color: #f43f5e; }
    .er-footer-actions { display: flex; gap: 10px; }

    .er-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s;
        text-decoration: none;
        border: none;
        letter-spacing: -0.1px;
    }
    .er-btn svg { width: 15px; height: 15px; }

    .er-btn-primary {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(14,165,233,0.3), inset 0 1px 0 rgba(255,255,255,0.15);
    }
    .er-btn-primary:hover {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        box-shadow: 0 4px 16px rgba(14,165,233,0.4);
        transform: translateY(-1px);
    }
    .er-btn-primary:active { transform: translateY(0); }

    .er-btn-ghost {
        background: #f1f5f9;
        color: #475569;
    }
    .er-btn-ghost:hover { background: #e2e8f0; color: #1e293b; }

    /* Fade-in animation */
    @keyframes erFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .er-card { animation: erFadeUp 0.35s ease both; }
    .er-page-header { animation: erFadeUp 0.25s ease both; }
</style>

<div class="er-wrap">

    <!-- Page Header -->
    <div class="er-page-header">
        <div class="er-page-header-left">
            <h1>Edit Resident</h1>
            <p>Update resident information and account credentials</p>
        </div>
        <div class="er-id-badge">
            <span class="label">Resident ID</span>
            <span class="value">{{ $resident->resident_id }}</span>
        </div>
    </div>

    <div class="er-card">
        <form method="POST" action="{{ route('admin.residents.update', $resident) }}">
            @csrf
            @method('PUT')

            {{-- ── Personal Information ─────────────────────────── --}}
            <div class="er-section">
                <div class="er-section-title">
                    <div class="icon blue">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h2>Personal Information</h2>
                        <p>Full legal name and identification details</p>
                    </div>
                </div>

                <div class="er-grid-3">
                    <div class="er-field">
                        <label class="er-label">First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" class="er-input"
                               value="{{ old('first_name', $resident->first_name) }}"
                               placeholder="e.g. Juan" required>
                    </div>
                    <div class="er-field">
                        <label class="er-label">Middle Name</label>
                        <input type="text" name="middle_name" class="er-input"
                               value="{{ old('middle_name', $resident->middle_name) }}"
                               placeholder="Optional">
                    </div>
                    <div class="er-field">
                        <label class="er-label">Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" class="er-input"
                               value="{{ old('last_name', $resident->last_name) }}"
                               placeholder="e.g. Dela Cruz" required>
                    </div>
                </div>

                <div style="margin-top:16px;" class="er-grid-2">
                    <div class="er-field">
                        <label class="er-label">Birthdate <span class="req">*</span></label>
                        <input type="date" name="birthdate" class="er-input"
                               value="{{ old('birthdate', $resident->birthdate->format('Y-m-d')) }}" required>
                    </div>
                    <div class="er-field">
                        <label class="er-label">Occupation</label>
                        <input type="text" name="occupation" class="er-input"
                               value="{{ old('occupation', $resident->occupation) }}"
                               placeholder="e.g. Teacher">
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <label class="er-check-row" for="is_pwd">
                        <input type="checkbox" name="is_pwd" id="is_pwd" value="1"
                               {{ $resident->is_pwd ? 'checked' : '' }}>
                        <div>
                            <div class="er-check-label">Person with Disability (PWD)</div>
                            <div class="er-check-desc">Mark if resident holds a PWD classification</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ── Contact & Address ────────────────────────────── --}}
            <div class="er-section">
                <div class="er-section-title">
                    <div class="icon green">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h2>Contact & Address</h2>
                        <p>Where to reach this resident</p>
                    </div>
                </div>

                <div class="er-field" style="margin-bottom:16px;">
                    <label class="er-label">Address <span class="req">*</span></label>
                    <input type="text" name="address" class="er-input"
                           value="{{ old('address', $resident->address) }}"
                           placeholder="House No., Street, Barangay" required>
                </div>

                <div class="er-grid-2">
                    <div class="er-field">
                        <label class="er-label">Contact Number 1 <span class="req">*</span></label>
                        <input type="text" name="contact_number_1" class="er-input"
                               value="{{ old('contact_number_1', $resident->contact_number_1) }}"
                               placeholder="09XX XXX XXXX" required>
                    </div>
                    <div class="er-field">
                        <label class="er-label">Contact Number 2</label>
                        <input type="text" name="contact_number_2" class="er-input"
                               value="{{ old('contact_number_2', $resident->contact_number_2) }}"
                               placeholder="Optional alternate number">
                    </div>
                </div>
            </div>

            {{-- ── Block Assignment ─────────────────────────────── --}}
            <div class="er-section">
                <div class="er-section-title">
                    <div class="icon amber">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M10 4v16M14 4v16"/></svg>
                    </div>
                    <div>
                        <h2>Block Assignment</h2>
                        <p>Which block does this resident belong to</p>
                    </div>
                </div>

                <div class="er-field" style="max-width:400px;">
                    <label class="er-label">Block <span class="req">*</span></label>
                    <div class="er-select-wrap">
                        <select name="block_id" class="er-select" required>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}" {{ $resident->block_id == $block->id ? 'selected' : '' }}>
                                    Block {{ $block->block_number }} — {{ $block->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── Account / Email ──────────────────────────────── --}}
            <div class="er-section">
                <div class="er-section-title">
                    <div class="icon rose">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <div>
                        <h2>Account Credentials</h2>
                        <p>Login email and password management</p>
                    </div>
                </div>

                <div class="er-field" style="margin-bottom:20px; max-width:480px;">
                    <label class="er-label">Email Address <span class="req">*</span></label>
                    <input type="email" name="email" class="er-input"
                           value="{{ old('email', $resident->user->email) }}"
                           placeholder="resident@example.com" required>
                    @error('email')
                        <span class="er-error">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="er-pw-section">
                    <!-- Current password (display only) -->
                    <div style="margin-bottom:16px;">
                        <label class="er-label" style="margin-bottom:8px;display:block;">Current Password</label>
                        <div class="er-pw-current">
                            <div class="pw-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <span>Stored securely — cannot be decrypted or revealed</span>
                        </div>
                    </div>

                    <!-- New password fields -->
                    <div class="er-grid-2">
                        <div class="er-field">
                            <label class="er-label">New Password</label>
                            <div class="er-pw-wrap">
                                <input id="new_password" type="password" name="new_password" class="er-input"
                                       placeholder="Leave blank to keep current">
                                <button type="button" class="er-pw-toggle" id="toggle-new-password">Show</button>
                            </div>
                            @error('new_password')
                                <span class="er-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="er-field">
                            <label class="er-label">Confirm New Password</label>
                            <div class="er-pw-wrap">
                                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="er-input"
                                       placeholder="Repeat new password">
                                <button type="button" class="er-pw-toggle" id="toggle-new-password-confirmation">Show</button>
                            </div>
                        </div>
                    </div>
                    <p class="er-hint" style="margin-top:10px;">💡 Minimum 8 characters recommended. Leave both fields empty to keep the current password unchanged.</p>
                </div>
            </div>
 
            <div class="er-footer">
                <span class="er-footer-hint"><span>*</span> Required fields</span>
                <div class="er-footer-actions">
                    <a href="{{ route('admin.residents.show', $resident) }}" class="er-btn er-btn-ghost">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Cancel
                    </a>
                    <button type="submit" class="er-btn er-btn-primary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Update Resident
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    function setupPasswordToggle(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        if (!input || !button) return;
        button.addEventListener('click', function () {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Hide' : 'Show';
        });
    }

    setupPasswordToggle('new_password', 'toggle-new-password');
    setupPasswordToggle('new_password_confirmation', 'toggle-new-password-confirmation');
 
    const pwdCheckbox = document.getElementById('is_pwd');
    const pwdRow = pwdCheckbox?.closest('label.er-check-row');
    if (pwdCheckbox && pwdRow) {
        const update = () => {
            pwdRow.style.borderColor = pwdCheckbox.checked ? '#38bdf8' : '';
            pwdRow.style.background  = pwdCheckbox.checked ? '#f0f9ff' : '';
        };
        update();
        pwdCheckbox.addEventListener('change', update);
    }
</script>

@endsection