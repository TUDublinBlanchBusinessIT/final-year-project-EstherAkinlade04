<!DOCTYPE html>
<html>
<head>
    <title>Member Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <!-- QR Code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        .perspective { perspective: 1000px; }
        .transform-style { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flipped { transform: rotateY(180deg); }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-indigo-50 to-purple-100 min-h-screen">

@php
$daysLeft = $user->end_date
    ? \Carbon\Carbon::now()->diffInDays($user->end_date, false)
    : null;

$isExpired = !$user->end_date || $user->end_date < now();
@endphp

<!-- ================= NAVIGATION ================= -->

<nav class="bg-white shadow-lg px-10 py-5 flex justify-between items-center border-b border-indigo-100">

    <div class="flex items-center gap-8">

        <div>
            <h1 class="text-2xl font-bold text-indigo-800 tracking-wide">
                💎 Vault Fitness
            </h1>
            <p class="text-xs text-gray-400 uppercase tracking-widest">
                Member Performance Dashboard
            </p>
        </div>

        <a href="{{ route('classes.index') }}"
           class="text-indigo-600 font-semibold hover:text-indigo-800 hover:underline transition">
            🔎 Browse Classes
        </a>

    </div>

    <div class="flex items-center gap-6">

        @if($isExpired)
            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold shadow">
                ❌ Membership Expired
            </span>
        @else
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold shadow">
                🏅 {{ ucfirst($user->membership_type) }} Membership
                @if($daysLeft !== null)
                    ({{ $daysLeft }} days left)
                @endif
            </span>
        @endif

        <button onclick="openPass()"
            class="bg-indigo-600 text-white px-4 py-2 rounded-xl
                   hover:bg-indigo-700 hover:scale-105 hover:shadow-lg transition">
            🎟 Digital Pass
        </button>

        <div class="text-right">
            <p class="text-sm text-gray-400">Welcome back,</p>
            <p class="font-semibold text-indigo-800 text-lg">
                {{ $user->name }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl
                           hover:bg-red-600 hover:scale-105 hover:shadow-lg transition">
                🚪 Logout
            </button>
        </form>

    </div>

</nav>

<div class="p-10">

@if($daysLeft !== null && $daysLeft <= 3 && $daysLeft > 0)
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-xl mb-6 shadow">
        ⚠ Your membership expires in {{ $daysLeft }} day(s).
    </div>
@endif

@if($isExpired)
    <div class="bg-red-100 text-red-800 p-6 rounded-xl mb-8 shadow">
        <p class="mb-4 font-semibold">Your membership has expired.</p>

        <a href="{{ route('checkout') }}"
           class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
            🔄 Renew Membership
        </a>
    </div>
@endif

@php
$upcoming = $bookings->where('class_time', '>=', now());
$completed = $bookings->where('class_time', '<', now());
$nextClass = $upcoming->sortBy('class_time')->first();
$attendanceRate = $bookings->count() > 0
    ? round(($completed->count() / $bookings->count()) * 100)
    : 0;
@endphp

<!-- ================= STATS ================= -->

<div class="grid md:grid-cols-3 gap-8 mb-12">

    <div class="bg-white p-6 rounded-3xl shadow-xl">
        <p class="text-gray-500 text-sm">🔥 Upcoming Sessions</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl text-center">
        <p class="text-gray-500 text-sm mb-3">Attendance Rate</p>
        <div class="relative w-24 h-24 mx-auto">
            <svg class="w-24 h-24 transform -rotate-90">
                <circle cx="50%" cy="50%" r="40"
                        stroke="#E5E7EB" stroke-width="8"
                        fill="none"/>
                <circle cx="50%" cy="50%" r="40"
                        stroke="#4F46E5"
                        stroke-width="8"
                        fill="none"
                        stroke-dasharray="251"
                        stroke-dashoffset="{{ 251 - (251 * $attendanceRate / 100) }}"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center text-indigo-700 font-bold">
                {{ $attendanceRate }}%
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl">
        <p class="text-gray-500 text-sm">Total Bookings</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $bookings->count() }}
        </h2>
    </div>

</div>

@if($nextClass)
<div class="bg-indigo-600 text-white p-8 rounded-3xl shadow-xl mb-10">
    <h2 class="text-xl font-bold mb-2">🔥 Your Next Session</h2>
    <p class="text-2xl font-semibold">{{ $nextClass->name }}</p>
    <p>{{ \Carbon\Carbon::parse($nextClass->class_time)->format('d M Y H:i') }}</p>
</div>
@endif

<h2 class="text-3xl font-bold text-indigo-900 mb-8">
    📚 Booking History
</h2>

@if($bookings->isEmpty())
<div class="bg-white p-8 rounded-2xl shadow text-center">
    <p class="text-gray-600">No bookings yet.</p>
</div>
@else

<div class="grid md:grid-cols-2 gap-8">
@foreach($bookings as $class)

@php $isPast = \Carbon\Carbon::parse($class->class_time)->isPast(); @endphp

<div class="bg-white p-8 rounded-3xl shadow-xl">
    <h3 class="text-xl font-bold text-indigo-800 mb-2">
        {{ $class->name }}
    </h3>

    <p class="text-gray-500 mb-2">
        📅 {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
    </p>

    <span class="px-3 py-1 rounded-full text-sm
        {{ $isPast ? 'bg-gray-200 text-gray-600' : 'bg-green-100 text-green-700' }}">
        {{ $isPast ? 'Completed' : 'Upcoming' }}
    </span>

    @if(!$isPast && !$isExpired)
        <form method="POST"
              action="{{ route('cancel.booking', $class->id) }}"
              class="mt-4">
            @csrf
            @method('DELETE')
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                Cancel Booking
            </button>
        </form>
    @endif

</div>

@endforeach
</div>

@endif

</div>

</body>
</html>