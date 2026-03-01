<!DOCTYPE html>
<html>
<head>
    <title>The Vault – Premium Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-100 min-h-screen">

@php
    $user = auth()->user();
    $membershipExpired = !$user->end_date || \Carbon\Carbon::parse($user->end_date)->isPast();
@endphp

<!-- ================= HERO ================= -->

<div class="relative overflow-hidden h-[420px]">

    <div id="slides" class="absolute inset-0 transition-opacity duration-1000">
        <img src="https://images.unsplash.com/photo-1554284126-aa88f22d8b74"
             class="absolute w-full h-full object-cover opacity-100 slide">
        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b"
             class="absolute w-full h-full object-cover opacity-0 slide">
        <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438"
             class="absolute w-full h-full object-cover opacity-0 slide">
    </div>

    <div class="absolute inset-0 bg-black/50 flex flex-col justify-center items-center text-white text-center">
        <h1 class="text-5xl font-extrabold mb-4">Train Hard. Book Smart.</h1>
        <p class="text-lg opacity-90">Elite fitness booking platform</p>
    </div>

</div>

<!-- ================= CONTENT ================= -->

<div class="p-10">

{{-- SUCCESS / ERROR --}}
@if(session('success'))
<div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 shadow">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6 shadow">
    {{ session('error') }}
</div>
@endif

@if($membershipExpired)
<div class="bg-red-100 text-red-800 p-6 rounded-xl mb-10 shadow flex justify-between items-center">
    <div>
        <p class="font-semibold text-lg">Your membership has expired.</p>
        <p class="text-sm">Renew to continue booking classes.</p>
    </div>

    <a href="{{ route('checkout') }}"
       class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition font-semibold">
        🔄 Renew Membership
    </a>
</div>
@endif

<!-- ================= ALL CLASSES ================= -->

<h2 class="text-3xl font-bold text-indigo-900 mb-8">
    🏋️ All Classes
</h2>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

@foreach($classes as $class)

@php
    $isPast = $class->class_time->isPast();
    $isFull = $class->bookings_count >= $class->capacity;
    $alreadyBooked = $class->bookings->where('user_id', $user->id)->count() > 0;
    $fillPercent = $class->capacity > 0 
        ? min(100, ($class->bookings_count / $class->capacity) * 100) 
        : 0;
@endphp

<div class="bg-white p-6 rounded-3xl shadow-xl 
            hover:shadow-2xl hover:-translate-y-2 
            transition duration-300 border border-indigo-100 relative">

    @if($fillPercent > 80 && !$isFull)
        <span class="absolute top-4 right-4 bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full animate-pulse">
            🔥 Filling Fast
        </span>
    @endif

    <h2 class="text-2xl font-bold text-indigo-800 mb-2">
        {{ $class->name }}
    </h2>

    <p class="text-gray-600 mb-4">
        {{ $class->description }}
    </p>

    <div class="text-sm space-y-2 mb-4 text-gray-700">
        <p>📅 {{ $class->class_time->format('d M Y H:i') }}</p>
        <p>👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked</p>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mb-5 overflow-hidden">
        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-1000"
             style="width: {{ $fillPercent }}%">
        </div>
    </div>

    {{-- BOOKING LOGIC --}}

    @if($alreadyBooked)
        <button disabled class="w-full bg-green-200 text-green-800 py-3 rounded-xl font-semibold">
            ✅ Already Booked
        </button>

    @elseif($isPast || $isFull)
        <button disabled class="w-full bg-gray-300 text-white py-3 rounded-xl">
            Booking Unavailable
        </button>

    @elseif($membershipExpired)
        <a href="{{ route('checkout') }}"
           class="block w-full text-center bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition font-semibold">
            🔄 Renew to Book
        </a>

    @else
        <form method="POST" action="{{ route('book.class', $class->id) }}">
            @csrf
            <button
                class="w-full bg-indigo-600 text-white py-3 rounded-xl 
                       hover:bg-indigo-700 hover:shadow-xl hover:scale-105 transition font-semibold">
                📅 Book Class
            </button>
        </form>
    @endif

</div>

@endforeach

</div>

</div>

<!-- ================= HERO SLIDESHOW SCRIPT ================= -->

<script>
document.addEventListener("DOMContentLoaded", function() {

    let slides = document.querySelectorAll('.slide');
    let index = 0;

    setInterval(() => {
        slides[index].classList.remove('opacity-100');
        slides[index].classList.add('opacity-0');

        index = (index + 1) % slides.length;

        slides[index].classList.remove('opacity-0');
        slides[index].classList.add('opacity-100');
    }, 4000);

});
</script>

</body>
</html>