@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 rounded-xl shadow-md">
            <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-1">Community Service Desk</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Forgot your password?</h2>
            <p class="text-sm text-gray-600 mb-5">
                Enter your account email and we will send a 6-digit OTP code.
            </p>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg border border-green-200 bg-green-50 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
                    Send OTP Code
                </button>
            </form>

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
