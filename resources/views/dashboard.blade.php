<!DOCTYPE html>
<html>
<head>
    <title>Member Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        .glass {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-100 via-indigo-100 to-purple-200 min-h-screen">

@php
    $isExpired = false;
    $daysLeft = null;
    $isExpiringSoon = false;

    if ($user->end_date) {
        $endDate = \Carbon\Carbon::parse($user->end_date);
        $isExpired = $endDate->isPast();
        $daysLeft = now()->diffInDays($endDate, false);
        $isExpiringSoon = !$isExpired && $daysLeft <= 3;
    }
@endphp

<nav class="bg-white shadow-lg px-10 py-5 flex justify-between items-center">

    <div>
        <h1 class="text-2xl font-bold text-indigo-800">💎 Vault Fitness</h1>
        <p class="text-xs text-gray-400 uppercase tracking-widest">
            Member Dashboard
        </p>
    </div>

    <div class="flex items-center gap-6">

        @if($isExpired)
            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">
                ❌ Expired
            </span>
        @elseif($isExpiringSoon)
            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">
                ⚠ Expiring Soon ({{ $daysLeft }} days)
            </span>
        @else
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                🏅 Active
            </span>
        @endif

        @if($isExpired || $isExpiringSoon)
        <a href="{{ route('checkout') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            🔄 Renew
        </a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                Logout
            </button>
        </form>

    </div>
</nav>

<div class="p-10">

<!-- ================= DIGITAL MEMBERSHIP CARD ================= -->

<div class="mb-12">

<div class="glass rounded-3xl p-8 shadow-2xl text-white relative overflow-hidden"
     style="background: linear-gradient(135deg,#4f46e5,#7c3aed);">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold mb-2">
                {{ $user->name }}
            </h2>

            <p class="text-sm opacity-90">
                {{ ucfirst($user->membership_type ?? 'Standard') }} Membership
            </p>

            @if($user->end_date)
                <p class="text-sm mt-2">
                    Expires:
                    {{ \Carbon\Carbon::parse($user->end_date)->format('d M Y') }}
                </p>
            @endif

            @if($isExpired)
                <p class="mt-3 text-red-200 font-semibold">
                    Membership Expired
                </p>
            @endif

        </div>

        <div id="qrcode"></div>

    </div>

</div>

</div>

<!-- ================= STATS ================= -->

@php
$upcoming = $bookings->where('class_time', '>=', now());
$completed = $bookings->where('class_time', '<', now());
$attendanceRate = $bookings->count() > 0
    ? round(($completed->count() / $bookings->count()) * 100)
    : 0;
@endphp

<div class="grid md:grid-cols-3 gap-8 mb-12">

    <div class="bg-white p-6 rounded-3xl shadow-xl">
        <p class="text-gray-500 text-sm">Upcoming Sessions</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl text-center">
        <p class="text-gray-500 text-sm mb-3">Attendance Rate</p>
        <h2 class="text-3xl font-bold text-indigo-700">
            {{ $attendanceRate }}%
        </h2>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl">
        <p class="text-gray-500 text-sm">Total Bookings</p>
        <h2 class="text-3xl font-bold text-indigo-700 mt-2">
            {{ $bookings->count() }}
        </h2>
    </div>

</div>

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

<script>
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $user->email }}",
    width: 100,
    height: 100
});
</script>

</body>
</html>