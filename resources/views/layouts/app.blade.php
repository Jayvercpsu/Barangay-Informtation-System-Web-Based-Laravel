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

            <span class="text-sm text-gray-500">
                {{ auth()->user()->name }}
            </span>
 
            <a href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : route('resident.profile.edit') }}"
               class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                Profile
            </a>
 
            <button onclick="document.getElementById('logout-modal').classList.remove('hidden')"
                    class="text-sm text-red-500 hover:text-red-700">
                Logout
            </button>
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
                        class="w-full px-4 py-2.5 bg-red-500 text-black text-sm font-medium rounded-xl hover:bg-red-600">
                    Yes, Logout
                </button>
            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('logout-modal').classList.add('hidden');
        }
    });
</script>

</body>
</html>
