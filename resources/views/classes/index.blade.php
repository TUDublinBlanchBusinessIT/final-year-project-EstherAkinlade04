<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-100 min-h-screen p-10">

<h1 class="text-4xl font-extrabold text-indigo-900 mb-12 flex items-center gap-3">
    🏋️‍♀️ The Vault – Premium Classes
</h1>

{{-- SUCCESS / ERROR MESSAGES --}}
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
@endphp

<div class="bg-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition duration-300 border border-indigo-100">

    {{-- TITLE --}}
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-2xl font-bold text-indigo-800">
            {{ $class->name }}
        </h2>

        {{-- STATUS BADGE --}}
        @if($class->is_cancelled)
            <span class="bg-black text-white text-xs px-3 py-1 rounded-full">
                CANCELLED
            </span>
        @elseif($isPast)
            <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">
                PAST
            </span>
        @elseif($isFull)
            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">
                FULL
            </span>
        @else
            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                AVAILABLE
            </span>
        @endif
    </div>

    {{-- DESCRIPTION --}}
    <p class="text-gray-600 mb-4">
        {{ $class->description }}
    </p>

    {{-- DETAILS --}}
    <div class="text-sm space-y-2 mb-4 text-gray-700">
        <p>📅 {{ $class->class_time->format('d M Y H:i') }}</p>
        <p>💰 €{{ number_format($class->price, 2) }}</p>
        <p>👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked</p>
    </div>

    {{-- PROGRESS BAR --}}
    <div class="w-full bg-gray-200 rounded-full h-2 mb-5">
        <div class="bg-indigo-600 h-2 rounded-full"
             style="width: {{ min(100, ($class->bookings_count / $class->capacity) * 100) }}%">
        </div>
    </div>

    {{-- BOOKING / PAYMENT SECTION --}}
    @if($alreadyBooked)
        <button disabled
            class="w-full bg-green-200 text-green-800 py-3 rounded-xl font-semibold cursor-not-allowed">
            ✅ Already Booked
        </button>

    @elseif($class->is_cancelled)
        <button disabled
            class="w-full bg-black text-white py-3 rounded-xl cursor-not-allowed">
            Class Cancelled
        </button>

    @elseif($isPast)
        <button disabled
            class="w-full bg-gray-300 text-white py-3 rounded-xl cursor-not-allowed">
            Class Finished
        </button>

    @elseif($isFull)
        <button disabled
            class="w-full bg-red-300 text-white py-3 rounded-xl cursor-not-allowed">
            Fully Booked
        </button>

    @else
        {{-- PAYMENT SIMULATION --}}
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

</body>
</html>
