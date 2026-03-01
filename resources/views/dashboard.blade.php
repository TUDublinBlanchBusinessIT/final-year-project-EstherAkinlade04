<!DOCTYPE html>
<html>
<head>
    <title>Member Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-indigo-50 to-purple-100 min-h-screen">

@php
    $isExpired = true;
    $daysLeft = null;

    if ($user->end_date) {
        $endDate = \Carbon\Carbon::parse($user->end_date);
        $isExpired = $endDate->isPast();
        $daysLeft = now()->diffInDays($endDate, false);
    }
@endphp

<nav class="bg-white shadow-lg px-10 py-5 flex justify-between items-center border-b border-indigo-100">

    <div>
        <h1 class="text-2xl font-bold text-indigo-800">
            💎 Vault Fitness
        </h1>
        <p class="text-xs text-gray-400 uppercase tracking-widest">
            Member Performance Dashboard
        </p>
    </div>

    <div class="flex items-center gap-6">

        @if($isExpired)
            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold shadow">
                ❌ Membership Expired
            </span>
        @else
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold shadow">
                🏅 {{ $user->membershipPlan->name ?? 'No Plan Selected' }}
                @if($daysLeft !== null)
                    ({{ $daysLeft }} days left)
                @endif
            </span>
        @endif

        <a href="{{ route('checkout') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            🔄 Renew
        </a>

        <div class="text-right">
            <p class="text-sm text-gray-400">Welcome back,</p>
            <p class="font-semibold text-indigo-800 text-lg">
                {{ $user->name }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
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
        <p class="font-semibold">Your membership has expired.</p>
    </div>
@endif

</div>

</body>
</html>