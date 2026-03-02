<!DOCTYPE html>
<html>
<head>
    <title>Vault Fitness</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background: linear-gradient(145deg,#fbfaff,#f3f0ff);
            font-family: ui-sans-serif, system-ui;
            color:#1f2937;
            overflow-x:hidden;
        }

        /* Smooth page fade */
        body {
            opacity:0;
            animation:pageFade 0.8s ease forwards;
        }

        @keyframes pageFade {
            to { opacity:1; }
        }

        .nav-blur {
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,0.8);
            border-bottom:1px solid #ede9fe;
        }

        .lilac-text { color:#6d28d9; }

        .btn-primary {
            background:#6d28d9;
            color:white;
            padding:10px 18px;
            border-radius:14px;
            transition:all .3s ease;
        }

        .btn-primary:hover {
            background:#5b21b6;
            box-shadow:0 0 15px rgba(124,58,237,0.4);
            transform:translateY(-2px);
        }

        /* LUX CARD */
        .lux-card {
            background:rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-radius:24px;
            border:1px solid #ede9fe;
            box-shadow:0 20px 50px rgba(124,58,237,0.08);
            transition:all .4s ease;
        }

        .lux-card:hover {
            transform:translateY(-6px);
            box-shadow:0 30px 70px rgba(124,58,237,0.18);
        }

        /* WALLET CARD */
        .wallet-card {
            background: linear-gradient(135deg,#c4b5fd,#a78bfa);
            border-radius:32px;
            color:#312e81;
            box-shadow:0 40px 90px rgba(124,58,237,0.3);
            position:relative;
            overflow:hidden;
            transition:all .4s ease;
        }

        .wallet-card:hover {
            transform:translateY(-8px) scale(1.01);
        }

        .wallet-card::before {
            content:"";
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:6px;
            background:linear-gradient(90deg,#6d28d9,#ffffff,#6d28d9);
        }

        .wallet-card::after {
            content:"";
            position:absolute;
            top:-50%;
            left:-50%;
            width:200%;
            height:200%;
            background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.35),transparent 70%);
            transform:rotate(25deg);
            animation:shimmer 7s infinite;
        }

        @keyframes shimmer {
            0% { transform:translateX(-100%) rotate(25deg); }
            100% { transform:translateX(100%) rotate(25deg); }
        }

        .btn-wallet {
            background:black;
            color:white;
            border-radius:16px;
            padding:14px;
            font-weight:500;
            transition:.3s ease;
        }

        .btn-wallet:hover {
            background:#111;
            transform:scale(1.05);
            box-shadow:0 15px 40px rgba(0,0,0,0.4);
        }

        /* FACE ID */
        .face-overlay {
            position:fixed;
            inset:0;
            background:white;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            z-index:9999;
            animation:fadeOut 1s ease 2.5s forwards;
        }

        @keyframes fadeOut {
            to { opacity:0; visibility:hidden; }
        }

        .face-circle {
            width:110px;
            height:110px;
            border:3px solid #6d28d9;
            border-radius:50%;
            position:relative;
            overflow:hidden;
        }

        .scan-line {
            position:absolute;
            width:100%;
            height:3px;
            background:#6d28d9;
            animation:scan 2s infinite;
        }

        @keyframes scan {
            0%{top:0;}
            100%{top:100%;}
        }

        /* Parallax subtle */
        .parallax {
            transition:transform 0.2s ease-out;
        }

    </style>
</head>

<body>

<!-- FACE ID -->
<div class="face-overlay">
    <div class="face-circle">
        <div class="scan-line"></div>
    </div>
    <p class="mt-6 lilac-text font-semibold">Authenticating</p>
</div>

<nav class="fixed top-0 left-0 right-0 nav-blur z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg tracking-widest font-semibold lilac-text">
            VAULT FITNESS
        </h1>

        <div class="flex gap-6 items-center text-sm">
            <a href="{{ route('classes.index') }}">Browse Classes</a>
            <a href="{{ route('checkout') }}" class="btn-primary">Renew</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="hover:text-red-500">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="pt-28 px-6 max-w-6xl mx-auto">

@php
$upcoming = $bookings->where('class_time','>=',now());
$nextClass = $upcoming->sortBy('class_time')->first();
$memberId = str_pad($user->id,6,'0',STR_PAD_LEFT);
@endphp

<!-- WALLET -->
<div class="wallet-card p-8 mb-14 parallax">

    <div class="text-center mb-6">
        <p class="text-xs tracking-widest">VAULT MEMBERSHIP</p>
        <h2 class="text-3xl font-semibold mt-2">
            {{ ucfirst($user->membership_type ?? 'Elite') }}
        </h2>
        <p class="text-sm opacity-70">ID #{{ $memberId }}</p>
    </div>

    <div class="flex justify-between items-center mt-8">
        <div>
            <p class="text-xs tracking-widest">MEMBER</p>
            <p class="text-lg font-semibold">{{ $user->name }}</p>
        </div>
        <div class="bg-white p-3 rounded-xl shadow">
            <div id="qrcode"></div>
        </div>
    </div>

    <div class="mt-8">
        <p class="text-xs tracking-widest">EXPIRES</p>
        <p class="font-semibold">
            {{ $user->end_date ? \Carbon\Carbon::parse($user->end_date)->format('d M Y') : 'N/A' }}
        </p>
    </div>

    <div class="mt-6">
        <button class="btn-wallet w-full"> Add to Apple Wallet</button>
    </div>

</div>

<!-- STATS -->
<div class="grid md:grid-cols-2 gap-6 mb-10">

    <div class="lux-card p-6 text-center">
        <p class="text-gray-500">Upcoming Bookings</p>
        <h2 class="text-4xl lilac-text font-bold">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="lux-card p-6 text-center">
        <p class="text-gray-500">Total Bookings</p>
        <h2 class="text-4xl lilac-text font-bold">
            {{ $bookings->count() }}
        </h2>
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

<h2 class="text-2xl lilac-text mb-6">Your Bookings</h2>

<div class="grid md:grid-cols-2 gap-6">

@foreach($bookings as $class)

@php $isPast = \Carbon\Carbon::parse($class->class_time)->isPast(); @endphp

<div class="lux-card p-6">

    <h3 class="text-lg font-semibold mb-2">{{ $class->name }}</h3>

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

</div>

<script>
new QRCode(document.getElementById("qrcode"), {
    text:"{{ $user->email }}",
    width:70,
    height:70
});

/* Parallax effect */
window.addEventListener('scroll', function(){
    document.querySelectorAll('.parallax').forEach(el=>{
        let offset = window.scrollY * 0.03;
        el.style.transform = "translateY("+offset+"px)";
    });
});
</script>

</body>
</html>