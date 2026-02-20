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

        <span class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-sm font-semibold shadow">
            🏅 Standard Membership
        </span>

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

    <div class="bg-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition">
        <p class="text-gray-500 text-sm">🔥 Upcoming Sessions</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl text-center hover:shadow-2xl transition">
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

    <div class="bg-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition">
        <p class="text-gray-500 text-sm">Total Bookings</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $bookings->count() }}
        </h2>
    </div>

</div>

<!-- ================= NEXT SESSION ================= -->

@if($nextClass)
<div class="bg-indigo-600 text-white p-8 rounded-3xl shadow-xl mb-10">
    <h2 class="text-xl font-bold mb-2">🔥 Your Next Session</h2>
    <p class="text-2xl font-semibold">{{ $nextClass->name }}</p>
    <p>{{ \Carbon\Carbon::parse($nextClass->class_time)->format('d M Y H:i') }}</p>
</div>
@endif

<!-- ================= BOOKING HISTORY ================= -->

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

<div class="bg-white p-8 rounded-3xl shadow-xl hover:shadow-2xl transition">
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

    @if(!$isPast)
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

<!-- ================= DIGITAL PASS SLIDE PANEL ================= -->

<div id="passOverlay"
     class="fixed inset-0 bg-black/50 hidden z-40"
     onclick="closePass()"></div>

<div id="passPanel"
     class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl
            transform translate-x-full transition-transform duration-500 z-50 p-8">

    <div class="flex justify-between items-center mb-8">
        <h2 class="text-xl font-bold text-indigo-800">
            🎟 Digital Access Pass
        </h2>
        <button onclick="closePass()" class="text-gray-500 hover:text-red-500 text-xl">✕</button>
    </div>

    <div class="perspective w-full">

        <div id="card"
             class="relative w-full h-52 transition-transform duration-700 transform-style preserve-3d cursor-pointer"
             onclick="flipCard()">

            <!-- FRONT -->
            <div class="absolute w-full h-full bg-gradient-to-r from-indigo-600 to-purple-600
                        text-white rounded-3xl shadow-2xl p-6 backface-hidden">

                <h3 class="text-lg font-semibold">Vault Fitness</h3>
                <p class="mt-6 text-2xl font-bold">{{ $user->name }}</p>
                <p class="mt-2 text-sm opacity-80">
                    {{ $user->member_number ?? 'VLT-'.strtoupper(substr(md5($user->email),0,10)) }}
                </p>

            </div>

            <!-- BACK -->
            <div class="absolute w-full h-full bg-white rounded-3xl shadow-2xl
                        p-6 rotate-y-180 backface-hidden flex items-center justify-center">

                <div id="qrcode"></div>

            </div>

        </div>

    </div>

    <div class="mt-8 space-y-4">

        <button onclick="simulateNFC()"
                class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition">
            📲 Tap to Check In
        </button>

        <button class="w-full bg-black text-white py-3 rounded-xl">
             Add to Apple Wallet (Sprint 3)
        </button>

    </div>

</div>

<script>

function openPass() {
    document.getElementById('passPanel').classList.remove('translate-x-full');
    document.getElementById('passOverlay').classList.remove('hidden');
}

function closePass() {
    document.getElementById('passPanel').classList.add('translate-x-full');
    document.getElementById('passOverlay').classList.add('hidden');
}

function flipCard() {
    document.getElementById('card').classList.toggle('flipped');
}

new QRCode(document.getElementById("qrcode"), {
    text: "VaultMember-{{ $user->member_number ?? md5($user->email) }}",
    width: 140,
    height: 140
});

function simulateNFC() {
    alert("✅ NFC Tap Successful\nCheck-in recorded at " + new Date().toLocaleTimeString());
}

</script>

</body>
</html>