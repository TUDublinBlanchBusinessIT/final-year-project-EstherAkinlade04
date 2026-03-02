<!DOCTYPE html>
<html>
<head>
    <title>Vault Fitness Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f3f4ff, #e0e7ff);
        }

        /* ========== FACE ID OVERLAY ========== */
        .face-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at center, #1e3a8a, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
            color: white;
            animation: fadeOut 1s ease 3s forwards;
        }

        .face-box {
            width: 120px;
            height: 120px;
            border: 3px solid #60a5fa;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .scan-line {
            position: absolute;
            width: 100%;
            height: 4px;
            background: #60a5fa;
            animation: scan 2s infinite;
        }

        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        /* ========== WALLET CARD ========== */
        .wallet-wrapper {
            perspective: 1400px;
        }

        .wallet-card {
            border-radius: 32px;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform 0.4s ease;
            box-shadow: 0 30px 80px rgba(0,0,0,0.35);
            background: linear-gradient(135deg,#2563eb,#1e3a8a);
            animation: floatCard 6s ease-in-out infinite;
        }

        .wallet-card:hover {
            transform: rotateY(8deg) rotateX(6deg) scale(1.04);
        }

        .wallet-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                120deg,
                rgba(255,255,255,0.2),
                rgba(255,255,255,0.05)
            );
            backdrop-filter: blur(12px);
        }

        @keyframes floatCard {
            0%,100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* NFC pulse */
        .nfc-ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.6);
            animation: pulse 2.5s infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.8; }
            70% { transform: translate(-50%, -50%) scale(1.4); opacity: 0; }
            100% { opacity: 0; }
        }
    </style>
</head>

<body class="min-h-screen">

<!-- FACE ID -->
<div class="face-overlay">
    <div class="face-box">
        <div class="scan-line"></div>
    </div>
    <p class="mt-6 text-lg font-semibold">Authenticating with Face ID</p>
</div>

@php
    $isExpired = $user->end_date && \Carbon\Carbon::parse($user->end_date)->isPast();
    $daysLeft = $user->end_date ? now()->diffInDays($user->end_date, false) : null;

    $upcoming = $bookings->where('class_time', '>=', now());
    $completed = $bookings->where('class_time', '<', now());
    $nextClass = $upcoming->sortBy('class_time')->first();
    $attendanceRate = $bookings->count() > 0
        ? round(($completed->count() / $bookings->count()) * 100)
        : 0;
@endphp

<!-- NAV -->
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-blue-900">💎 Vault Fitness</h1>

    <div class="flex gap-4">
        <a href="{{ route('classes.index') }}" class="text-blue-600 font-semibold">Browse Classes</a>
        <a href="{{ route('checkout') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl">Renew</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl">Logout</button>
        </form>
    </div>
</nav>

<div class="p-6 max-w-6xl mx-auto">

<!-- WALLET -->
<div class="wallet-wrapper max-w-md mx-auto mb-12">

    <div class="wallet-card p-8 text-white relative">

        <div class="nfc-ring"></div>

        <div class="relative z-10 flex justify-between items-start mb-6">
            <div>
                <p class="text-xs uppercase tracking-widest text-blue-100">
                    Vault Fitness
                </p>
                <h2 class="text-lg font-semibold text-white">
                    {{ ucfirst($user->membership_type ?? 'Standard') }} Member
                </h2>
            </div>

            <div id="qrcode" class="bg-white p-2 rounded-xl"></div>
        </div>

        <div class="relative z-10 mb-6">
            <p class="text-sm text-blue-100">Member</p>
            <h3 class="text-2xl font-bold text-white">
                {{ $user->name }}
            </h3>
        </div>

        <div class="relative z-10 flex justify-between">
            <div>
                <p class="text-xs text-blue-100">Expires</p>
                <p class="font-semibold text-white">
                    {{ $user->end_date ? \Carbon\Carbon::parse($user->end_date)->format('d M Y') : 'N/A' }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs uppercase tracking-widest text-blue-100">
                    Tap to Enter
                </p>
                <p class="text-xs text-blue-200">NFC Ready</p>
            </div>
        </div>

    </div>

    <div class="mt-6 text-center">
        <button class="bg-black text-white px-6 py-3 rounded-xl font-semibold shadow-xl hover:scale-105 transition">
             Add to Apple Wallet
        </button>
    </div>

</div>

<!-- STATS -->
<div class="grid md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white p-6 rounded-2xl shadow">
        <p class="text-gray-500">Upcoming</p>
        <h2 class="text-3xl font-bold text-blue-700">{{ $upcoming->count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Attendance</p>
        <h2 class="text-3xl font-bold text-blue-700">{{ $attendanceRate }}%</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow">
        <p class="text-gray-500">Bookings</p>
        <h2 class="text-3xl font-bold text-blue-700">{{ $bookings->count() }}</h2>
    </div>

</div>

@if($nextClass)
<div class="bg-blue-700 text-white p-6 rounded-3xl shadow mb-10">
    <h2 class="text-xl font-bold">🔥 Next Session</h2>
    <p class="text-lg">{{ $nextClass->name }}</p>
    <p>{{ \Carbon\Carbon::parse($nextClass->class_time)->format('d M Y H:i') }}</p>
</div>
@endif

</div>

<script>
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $user->email }}",
    width: 80,
    height: 80
});
</script>

</body>
</html>