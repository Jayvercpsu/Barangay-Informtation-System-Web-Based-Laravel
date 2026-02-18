@extends('layouts.app')
@section('title', 'Admin Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <h3 class="font-semibold text-gray-900 mb-6">Account Information</h3>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-5 mb-6">
                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">
                        Administrator
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Update Profile
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <h3 class="font-semibold text-gray-900 mb-6">Change Password</h3>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="px-6 py-2.5 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition-colors">
                Change Password
            </button>
        </form>
    </div>

</div>
@endsection