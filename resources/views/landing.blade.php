<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Service Desk — Barangay Information System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">

<nav class="border-b border-gray-100 px-6 py-4">
    <div class="max-w-6xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg">
            <div>
                <p class="text-sm font-bold text-gray-900">Community Service Desk</p>
                <p class="text-xs text-gray-500">Barangay Information System</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Login
            </a>
            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors">
                Register
            </a>
        </div>
    </div>
</nav>

<section class="max-w-6xl mx-auto px-6 py-20 text-center">
    <span class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">
        Digital Barangay Services
    </span>
    <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
        Your Barangay Services,<br>
        <span class="text-blue-600">Now Online</span>
    </h1>
    <p class="text-lg text-gray-500 max-w-xl mx-auto mb-10">
        Request certificates, file complaints, and stay updated with community events all from the comfort of your home.
    </p>
    <div class="flex items-center justify-center gap-4">
        <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
            Get Started — Register
        </a>
        <a href="{{ route('login') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
            Login to Portal
        </a>
    </div>
</section>

<section class="bg-gray-50 py-20">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900">What You Can Do</h2>
            <p class="text-gray-500 mt-2">Services available to all registered residents</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Request Certificates</h3>
                <p class="text-gray-500 text-sm">Apply for Barangay Clearance, Proof of Residency, Certificate of Indigency, and Barangay Permit online.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">File Complaints</h3>
                <p class="text-gray-500 text-sm">Submit complaints and track their resolution status directly from your resident portal.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">SMS Notifications</h3>
                <p class="text-gray-500 text-sm">Receive real-time SMS updates about your requests, complaints, and important community announcements.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Built for the Community</h2>
                <p class="text-gray-500 mb-6">
                    Our digital barangay system modernizes the way residents interact with their local government. Faster processing, better transparency, and convenient access anytime, anywhere.
                </p>
                <ul class="space-y-3">
                    @foreach(['Unique Resident ID for every registered member', 'Real-time email & SMS notifications', 'Track complaint and certificate request status', 'Community calendar with upcoming events'] as $feature)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1577495508048-b635879837f1?w=600&auto=format&fit=crop"
                     alt="Community" class="w-full h-80 object-cover">
            </div>
        </div>
    </div>
</section>

<footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
    <p>&copy; {{ date('Y') }} Community Service Desk — Barangay Information System. All rights reserved.</p>
</footer>

</body>
</html>