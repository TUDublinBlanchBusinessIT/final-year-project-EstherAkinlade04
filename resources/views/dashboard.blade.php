<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-indigo-50 to-purple-100 min-h-screen">

<!-- NAV -->
<nav class="bg-white shadow-lg px-10 py-4 flex justify-between items-center">

    <div class="flex items-center gap-6">
        <h1 class="text-2xl font-bold text-indigo-800">
            💎 Vault Fitness
        </h1>

        <a href="{{ route('classes.index') }}"
           class="text-indigo-600 font-semibold hover:text-indigo-800 hover:underline transition">
            🔎 Browse Classes
        </a>
    </div>

    <div class="flex items-center gap-6">

        <span class="text-gray-600">
            {{ $user->name }} ({{ auth()->user()->role }})
        </span>

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

<!-- 🔔 MINI REMINDER CARD -->
<div class="bg-white p-6 rounded-3xl shadow-xl mb-10
            hover:shadow-2xl transition duration-300">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-xl font-bold text-indigo-800">
                🗓️ Upcoming Reminder
            </h2>
            <p class="text-gray-500 text-sm">
                Click to view your scheduled sessions
            </p>
        </div>

        <button onclick="toggleCalendar()"
                class="bg-indigo-600 text-white px-4 py-2 rounded-xl
                       hover:bg-indigo-700 hover:scale-105 hover:shadow-lg transition">
            View Calendar
        </button>

    </div>

    <!-- Hidden Calendar -->
    <div id="calendar-container"
         class="mt-6 hidden transition-all duration-500 ease-in-out">

        <div id="mini-calendar"></div>

    </div>

</div>


<h2 class="text-3xl font-bold text-indigo-900 mb-10">
    📚 My Bookings
</h2>

@if($bookings->isEmpty())

    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
        <p class="text-gray-600 mb-4">
            You haven’t booked any classes yet.
        </p>

        <a href="{{ route('classes.index') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-xl
                  hover:bg-indigo-700 hover:scale-105 hover:shadow-xl transition">
            Browse Classes
        </a>
    </div>

@else

<div class="grid md:grid-cols-2 gap-8">

@foreach($bookings as $class)

@php
$isPast = \Carbon\Carbon::parse($class->class_time)->isPast();
@endphp

<div class="bg-white p-8 rounded-3xl shadow-xl
            hover:shadow-2xl hover:-translate-y-1 transition duration-300">

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
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl
                           hover:bg-red-600 hover:scale-105 hover:shadow-lg transition">
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
let calendarRendered = false;
let calendar;

function toggleCalendar() {
    const container = document.getElementById('calendar-container');
    container.classList.toggle('hidden');

    if (!calendarRendered) {
        var calendarEl = document.getElementById('mini-calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 350,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            events: [
                @foreach($bookings as $class)
                {
                    title: "{{ $class->name }}",
                    start: "{{ $class->class_time }}",
                    color: "{{ \Carbon\Carbon::parse($class->class_time)->isPast() ? '#9CA3AF' : '#4F46E5' }}"
                },
                @endforeach
            ]
        });

        calendar.render();
        calendarRendered = true;
    }
}
</script>

</body>
</html>