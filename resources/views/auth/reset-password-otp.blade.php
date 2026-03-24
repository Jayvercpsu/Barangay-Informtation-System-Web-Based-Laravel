@extends('layouts.guest')

@section('title', 'Reset Password via OTP')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 rounded-xl shadow-md">
            <h1 class="text-2xl font-bold text-gray-900">Enter OTP</h1>
            <p class="text-gray-500 text-sm mt-1">Reset your password securely</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Verify OTP</h2>
            <p class="text-sm text-gray-600 mb-5">
                Enter the 6-digit OTP sent to your email.
            </p>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg border border-green-200 bg-green-50 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($email === '')
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Email is missing. Please request OTP again from forgot password.
                </div>

                <a href="{{ route('password.request') }}"
                   class="mt-4 inline-flex items-center justify-center w-full py-2.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Go to Forgot Password
                </a>
            @elseif (!$otpVerified)
                <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" value="{{ old('email', $email) }}" readonly
                               class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-sm text-gray-600 focus:outline-none">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
                        <input type="text" name="otp" required maxlength="6" inputmode="numeric" pattern="[0-9]{6}" value="{{ old('otp') }}"
                               placeholder="Enter 6-digit OTP"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm tracking-[0.35em] text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('otp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
                        Verify OTP
                    </button>
                </form>

                <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">
                    <button type="submit"
                            class="w-full py-2.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Resend OTP
                    </button>
                </form>
            @else
                <div class="mb-4 p-3 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 text-sm">
                    OTP verified for <strong>{{ $email }}</strong>. Enter your new password below.
                </div>

                <form method="POST" action="{{ route('password.otp.reset') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
                        Reset Password
                    </button>
                </form>
            @endif

            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                If you forgot your email, proceed to the Barangay office for manual password assistance.
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
