<!DOCTYPE html>
<html>
<head>
    <title>Vault Fitness</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg,#faf7ff,#f3f0ff);
            font-family: ui-sans-serif, system-ui;
            color: #1f2937;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform:translateY(15px);}
            to {opacity:1; transform:translateY(0);}
        }

        .nav-blur {
            backdrop-filter: blur(14px);
            background: rgba(255,255,255,0.85);
            border-bottom: 1px solid #ede9fe;
        }

        .lilac-text { color:#6d28d9; }

        .lux-card {
            background:white;
            border-radius:22px;
            border:1px solid #ede9fe;
            box-shadow:0 12px 30px rgba(124,58,237,0.08);
            transition:0.3s ease;
        }

        .lux-card:hover {
            transform:translateY(-4px);
            box-shadow:0 20px 40px rgba(124,58,237,0.15);
        }

        /* WALLET CARD */
        .wallet-card {
            background: linear-gradient(135deg,#c4b5fd,#a78bfa);
            border-radius:28px;
            color:#312e81;
            box-shadow:0 30px 70px rgba(124,58,237,0.25);
            position:relative;
            overflow:hidden;
            transition:0.4s ease;
        }

        /* metallic stripe */
        .wallet-card::before {
            content:"";
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:6px;
            background: linear-gradient(90deg,#6d28d9,#c4b5fd,#6d28d9);
        }

        /* glass shine */
        .wallet-card::after {
            content:"";
            position:absolute;
            top:-40%;
            left:-20%;
            width:150%;
            height:150%;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.35), transparent 60%);
            transform: rotate(25deg);
        }

        .wallet-card:hover {
            transform: translateY(-6px);
            box-shadow:0 40px 80px rgba(124,58,237,0.35);
        }

        .card-label {
            font-size:11px;
            letter-spacing:2px;
            color:#4c1d95;
        }

        .membership-id {
            font-size:12px;
            opacity:0.7;
        }

        .btn-primary {
            background:#6d28d9;
            color:white;
            padding:10px 18px;
            border-radius:12px;
            transition:0.3s ease;
        }

        .btn-primary:hover {
            background:#5b21b6;
            transform:translateY(-2px);
        }

        .btn-wallet {
            background:black;
            color:white;
            border-radius:14px;
            padding:12px;
            font-weight:500;
            transition:0.3s ease;
        }

        .btn-wallet:hover {
            background:#111;
            transform:scale(1.02);
        }

        .sidebar {
            transition:transform 0.3s ease;
            background:white;
            border-right:1px solid #ede9fe;
        }

        .sidebar-hidden {
            transform:translateX(-100%);
        }

        .qr-badge {
            background:white;
            padding:10px;
            border-radius:16px;
            box-shadow:0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="min-h-screen">

<nav class="fixed top-0 left-0 right-0 nav-blur z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="flex items-center gap-4">
            <button class="md:hidden text-2xl lilac-text" onclick="toggleSidebar()">☰</button>
            <h1 class="text-lg tracking-widest font-semibold lilac-text">
                VAULT FITNESS
            </h1>
        </div>

        <div class="hidden md:flex gap-6 items-center text-sm">
            <a href="{{ route('classes.index') }}" class="hover:text-black transition">
                Browse Classes
            </a>

            <a href="{{ route('checkout') }}" class="btn-primary">
                Renew
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="hover:text-red-500 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div id="sidebar"
     class="sidebar sidebar-hidden fixed top-0 left-0 h-full w-64 p-6 z-50 md:hidden">

    <h2 class="lilac-text text-lg font-semibold mb-6">Menu</h2>

    <a href="{{ route('classes.index') }}" class="block mb-4">Browse Classes</a>
    <a href="{{ route('checkout') }}" class="block mb-4">Renew</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="text-red-500">Logout</button>
    </form>
</div>

<div class="pt-28 px-6 max-w-6xl mx-auto">

@php
$upcoming = $bookings->where('class_time','>=',now());
$nextClass = $upcoming->sortBy('class_time')->first();
$memberId = str_pad($user->id,6,'0',STR_PAD_LEFT);
@endphp

<!-- MEMBERSHIP CARD -->
<div class="wallet-card p-8 mb-12">

    <div class="text-center mb-6">
        <p class="card-label">VAULT MEMBERSHIP</p>
        <h2 class="text-3xl font-semibold mt-2">
            {{ ucfirst($user->membership_type ?? 'Elite') }}
        </h2>
        <p class="membership-id mt-1">
            ID #{{ $memberId }}
        </p>
    </div>

    <div class="flex justify-between items-center mt-8">

        <div>
            <p class="card-label">MEMBER</p>
            <p class="text-lg font-semibold">
                {{ $user->name }}
            </p>
        </div>

        <div class="qr-badge">
            <div id="qrcode"></div>
        </div>

    </div>

    <div class="flex justify-between mt-8">
        <div>
            <p class="card-label">EXPIRES</p>
            <p class="font-semibold">
                {{ $user->end_date ? \Carbon\Carbon::parse($user->end_date)->format('d M Y') : 'N/A' }}
            </p>
        </div>
    </div>

    <div class="mt-6">
        <button class="btn-wallet w-full">
             Add to Apple Wallet
        </button>
    </div>

</div>

@if($nextClass)
<div class="lux-card p-6 mb-8">
    <h2 class="text-lg lilac-text mb-2">Next Session</h2>
    <p class="text-xl font-semibold">{{ $nextClass->name }}</p>
    <p class="text-gray-500">
        {{ \Carbon\Carbon::parse($nextClass->class_time)->format('d M Y H:i') }}
    </p>
</div>
@endif

<div class="grid md:grid-cols-2 gap-6 mb-10">

    <div class="lux-card p-6 text-center">
        <p class="text-gray-500">Upcoming Bookings</p>
        <h2 class="text-4xl lilac-text font-bold counter">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="lux-card p-6 text-center">
        <p class="text-gray-500">Total Bookings</p>
        <h2 class="text-4xl lilac-text font-bold counter">
            {{ $bookings->count() }}
        </h2>
    </div>

</div>

<h2 class="text-2xl lilac-text mb-6">Your Bookings</h2>

@if($bookings->isEmpty())
<div class="lux-card p-8 text-center">
    No bookings yet.
</div>
@else

<div class="grid md:grid-cols-2 gap-6">

@foreach($bookings as $class)

@php $isPast = \Carbon\Carbon::parse($class->class_time)->isPast(); @endphp

<div class="lux-card p-6">

    <h3 class="text-lg font-semibold mb-2">
        {{ $class->name }}
    </h3>

    <p class="text-gray-500 mb-3">
        {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
    </p>

    <span class="text-xs px-3 py-1 rounded-full
        {{ $isPast ? 'bg-gray-200 text-gray-600' : 'bg-[#c4b5fd] text-black' }}">
        {{ $isPast ? 'Completed' : 'Upcoming' }}
    </span>

    @if(!$isPast)
    <form method="POST"
          action="{{ route('cancel.booking',$class->id) }}"
          class="mt-4">
        @csrf
        @method('DELETE')
        <button class="text-red-500 hover:text-red-400 text-sm">
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
    width: 70,
    height: 70
});

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('sidebar-hidden');
}

document.querySelectorAll('.counter').forEach(counter => {
    let target = +counter.innerText;
    let count = 0;
    let step = target / 20;

    function update() {
        count += step;
        if(count < target) {
            counter.innerText = Math.ceil(count);
            setTimeout(update, 30);
        } else {
            counter.innerText = target;
        }
    }
    update();
});
</script>

</body>
</html>