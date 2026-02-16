<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<h1 class="text-3xl font-bold mb-8">Available Fitness Classes</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
        {{ session('error') }}
    </div>
@endif

@if($classes->isEmpty())
    <p>No classes available yet.</p>
@endif

<div class="grid md:grid-cols-2 gap-6">

@foreach($classes as $class)

    @php
        $bookedCount = $class->bookings->count();
        $remaining = $class->capacity - $bookedCount;
        $status = $class->status;
    @endphp

    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">{{ $class->name }}</h2>

            <!-- STATUS BADGE -->
            @if($status === 'upcoming')
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">
                    Upcoming
                </span>
            @elseif($status === 'full')
                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">
                    Full
                </span>
            @elseif($status === 'past')
                <span class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-full">
                    Past
                </span>
            @endif
        </div>

        <!-- Details -->
        <p class="text-gray-600 mb-3">
            {{ $class->description }}
        </p>

        <p class="text-sm text-gray-700 mb-2">
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y') }}
        </p>

        <p class="text-sm text-gray-700 mb-2">
            <strong>Time:</strong>
            {{ \Carbon\Carbon::parse($class->class_time)->format('H:i') }}
        </p>

        <p class="text-sm text-gray-700 mb-4">
            <strong>Remaining Spots:</strong> {{ $remaining }}
        </p>

        <!-- Booking Button -->
        @if($status === 'upcoming')
            <form method="POST" action="{{ route('book.class', $class->id) }}">
                @csrf
                <button class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">
                    Book Class
                </button>
            </form>
        @elseif($status === 'full')
            <button disabled
                class="w-full bg-red-300 text-white py-2 rounded cursor-not-allowed">
                Class Full
            </button>
        @else
            <button disabled
                class="w-full bg-gray-300 text-white py-2 rounded cursor-not-allowed">
                Class Finished
            </button>
        @endif

    </div>

@endforeach

</div>

</body>
</html>
