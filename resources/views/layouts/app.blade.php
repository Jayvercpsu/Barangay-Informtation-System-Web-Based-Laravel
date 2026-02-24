<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Service Desk') — Barangay Information System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

@if(auth()->user()->isAdmin())
    @include('layouts.partials.admin-sidebar')
@else
    @include('layouts.partials.resident-sidebar')
@endif

@php
    $adminNotifications = collect();
    $unreadNotificationsCount = 0;

    if (auth()->user()->isAdmin() && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
        $adminNotifications = auth()->user()->notifications()->latest()->take(8)->get();
        $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
    }
@endphp

<div class="lg:pl-64">
 
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
         
        <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
 
        <h1 class="text-lg font-semibold text-gray-800">
            @yield('page-title', 'Dashboard')
        </h1>
 
        <div class="flex items-center gap-4">
            @if(auth()->user()->isAdmin())
                <div class="relative" id="notification-dropdown-wrapper">
                    <button id="notification-toggle"
                            class="relative flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            aria-label="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    <span class="pointer-events-none absolute z-20 text-[15px] font-bold leading-none text-red-600"
                          style="top: -12px; right: -4px;">
                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                    </span>

                    <div id="notification-dropdown"
                         class="hidden fixed w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                         style="z-index: 9999;">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Notifications</p>
                                <p class="text-xs text-gray-500">{{ $unreadNotificationsCount }} unread</p>
                            </div>
                            @if($unreadNotificationsCount > 0)
                                <form method="POST" action="{{ route('admin.notifications.read_all') }}">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                        Mark all read
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @forelse($adminNotifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $isUnread = is_null($notification->read_at);
                                @endphp
                                <div class="px-4 py-3 border-b border-gray-100 last:border-b-0 {{ $isUnread ? 'bg-blue-50/40' : '' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <a href="{{ $data['link'] ?? route('admin.dashboard') }}"
                                           class="block min-w-0">
                                            <p class="text-sm font-medium text-gray-800 truncate">{{ $data['title'] ?? 'Resident Activity' }}</p>
                                            <p class="text-xs text-gray-600 mt-0.5">{{ $data['message'] ?? '' }}</p>
                                            <p class="text-[11px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                        @if($isUnread)
                                            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" class="shrink-0">
                                                @csrf
                                                <button type="submit"
                                                        class="text-[11px] font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                                    Mark read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-gray-400">
                                    No notifications yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <div class="relative" id="profile-dropdown-wrapper">
                <button id="profile-toggle"
                        class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </button>

            <div id="profile-dropdown"
                     class="hidden fixed w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                     style="z-index: 9999;">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                            {{ auth()->user()->isAdmin() ? 'Barangay Admin' : 'Resident' }}
                        </p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : route('resident.profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                    </div>
                    <div class="py-1 border-t border-gray-100">
                        <button onclick="document.getElementById('profile-dropdown').classList.add('hidden'); document.getElementById('logout-modal').classList.remove('hidden')"
                                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
 
    <main class="p-6">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>
 
<div id="logout-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/30 backdrop-blur-sm"
     onclick="if(event.target === this) this.classList.add('hidden')">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
 
        <div class="flex items-center justify-center w-12 h-12 bg-red-50 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 text-center">Sign out?</h3>
        <p class="text-sm text-gray-500 text-center mb-6">
            Are you sure you want to logout from your account?
        </p>

        <div class="flex gap-3">
 
            <button onclick="document.getElementById('logout-modal').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200">
                Cancel
            </button>
 
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-500 text-danger text-sm font-medium rounded-xl hover:bg-red-600">
                    Yes, Logout
                </button>
            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
        document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
    });

    document.getElementById('profile-toggle')?.addEventListener('click', function() {
        const dropdown = document.getElementById('profile-dropdown');
        if (!dropdown) return;

        const btn = this.getBoundingClientRect();
        const dropdownWidth = 224;

        dropdown.style.top = (btn.bottom + window.scrollY + 8) + 'px';
        dropdown.style.left = (btn.right + window.scrollX - dropdownWidth) + 'px';
        dropdown.classList.toggle('hidden');

        document.getElementById('notification-dropdown')?.classList.add('hidden');
    });

    document.getElementById('notification-toggle')?.addEventListener('click', function () {
        const dropdown = document.getElementById('notification-dropdown');
        if (!dropdown) return;

        const btn = this.getBoundingClientRect();
        const dropdownWidth = 320;

        dropdown.style.top = (btn.bottom + window.scrollY + 8) + 'px';
        dropdown.style.left = (btn.right + window.scrollX - dropdownWidth) + 'px';
        dropdown.classList.toggle('hidden');

        document.getElementById('profile-dropdown')?.classList.add('hidden');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('logout-modal')?.classList.add('hidden');
            document.getElementById('profile-dropdown')?.classList.add('hidden');
            document.getElementById('notification-dropdown')?.classList.add('hidden');
        }
    });

    document.addEventListener('click', function (e) {
        const profileWrapper = document.getElementById('profile-dropdown-wrapper');
        if (profileWrapper && !profileWrapper.contains(e.target)) {
            document.getElementById('profile-dropdown')?.classList.add('hidden');
        }

        const notificationWrapper = document.getElementById('notification-dropdown-wrapper');
        if (notificationWrapper && !notificationWrapper.contains(e.target)) {
            document.getElementById('notification-dropdown')?.classList.add('hidden');
        }
    });
</script>

</body>
</html>
