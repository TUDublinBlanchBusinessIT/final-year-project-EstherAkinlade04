<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-100 min-h-screen">

<!-- ================= NAVBAR ================= -->

<nav class="bg-white shadow-lg px-10 py-5 flex justify-between items-center border-b border-indigo-100">

    <div class="flex items-center gap-8">
        <div>
            <h1 class="text-2xl font-bold text-indigo-800 tracking-wide">
                💎 Vault Fitness
            </h1>
            <p class="text-xs text-gray-400 uppercase tracking-widest">
                Premium Class Booking
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="text-indigo-600 font-semibold hover:text-indigo-800 hover:underline transition">
            🏠 Dashboard
        </a>
    </div>

    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-sm text-gray-400">Welcome back,</p>
            <p class="font-semibold text-indigo-800 text-lg">
                {{ auth()->user()->name }}
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

<!-- ================= HERO SLIDESHOW ================= -->

<div class="relative w-full mb-14 rounded-3xl overflow-hidden shadow-2xl">

    @php
        $slides = [
            [
                'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b',
                'title' => 'Elite Strength Training',
                'desc' => 'Push limits. Build power. Train with purpose.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1558611848-73f7eb4001ab',
                'title' => 'High Performance Coaching',
                'desc' => 'Structured sessions designed for serious results.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438',
                'title' => 'Train With Community',
                'desc' => 'Motivation. Energy. Accountability.'
            ],
        ];
    @endphp

    @foreach($slides as $index => $slide)
        <div class="slide {{ $index === 0 ? '' : 'hidden' }} relative h-[450px]">
            <img src="{{ $slide['img'] }}"
                 class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute inset-0 bg-black/50 flex flex-col justify-center px-16 text-white">
                <h2 class="text-5xl font-extrabold mb-4">{{ $slide['title'] }}</h2>
                <p class="text-lg max-w-xl opacity-90">
                    {{ $slide['desc'] }}
                </p>
            </div>
        </div>
    @endforeach

    <button onclick="prevSlide()"
        class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/20 text-white px-4 py-2 rounded-full hover:bg-white/40 transition">
        ◀
    </button>

    <button onclick="nextSlide()"
        class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/20 text-white px-4 py-2 rounded-full hover:bg-white/40 transition">
        ▶
    </button>

</div>

<h1 class="text-4xl font-extrabold text-indigo-900 mb-12">
    🏋️‍♀️ Available Classes
</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-xl mb-6 shadow">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-800 p-4 rounded-xl mb-6 shadow">
        {{ session('error') }}
    </div>
@endif

@if($classes->isEmpty())
    <div class="bg-white p-8 rounded-2xl shadow text-center text-gray-600">
        No classes available right now.
    </div>
@endif

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

@foreach($classes as $class)

@php
    $isPast = $class->class_time->isPast();
    $isFull = $class->bookings_count >= $class->capacity;
    $alreadyBooked = $class->bookings->where('user_id', auth()->id())->count() > 0;

    $fillPercent = $class->capacity > 0
        ? min(100, ($class->bookings_count / $class->capacity) * 100)
        : 0;
@endphp

<div class="bg-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition duration-300 border border-indigo-100">

    <div class="flex justify-between items-center mb-3">
        <h2 class="text-2xl font-bold text-indigo-800">
            {{ $class->name }}
        </h2>

        @if($class->is_cancelled)
            <span class="bg-black text-white text-xs px-3 py-1 rounded-full">CANCELLED</span>
        @elseif($isPast)
            <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">PAST</span>
        @elseif($isFull)
            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">FULL</span>
        @else
            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">AVAILABLE</span>
        @endif
    </div>

    <p class="text-gray-600 mb-4">
        {{ $class->description }}
    </p>

    <div class="text-sm space-y-2 mb-4 text-gray-700">
        <p>📅 {{ $class->class_time->format('d M Y H:i') }}</p>
        <p>💰 €{{ number_format($class->price, 2) }}</p>
        <p>👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked</p>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mb-5">
        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-700"
             style="width: {{ $fillPercent }}%">
        </div>
    </div>

    @if($alreadyBooked)
        <button disabled class="w-full bg-green-200 text-green-800 py-3 rounded-xl font-semibold">
            ✅ Already Booked
        </button>
    @elseif($class->is_cancelled)
        <button disabled class="w-full bg-black text-white py-3 rounded-xl">
            Class Cancelled
        </button>
    @elseif($isPast)
        <button disabled class="w-full bg-gray-300 text-white py-3 rounded-xl">
            Class Finished
        </button>
    @elseif($isFull)
        <button disabled class="w-full bg-red-300 text-white py-3 rounded-xl">
            Fully Booked
        </button>
    @else
        <form method="POST" action="{{ route('book.class', $class->id) }}">
            @csrf
            <button
                onclick="this.innerHTML='Processing Payment... 💳'; this.disabled=true;"
                class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition font-semibold">
                💳 Pay €{{ number_format($class->price, 2) }} & Book
            </button>
        </form>
    @endif

</div>

@endforeach

</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.add("hidden");
            if (i === index) {
                slide.classList.remove("hidden");
            }
        });
    }

    window.nextSlide = function () {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    };

    window.prevSlide = function () {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    };

    setInterval(nextSlide, 5000);
});
</script>

</body>
</html>