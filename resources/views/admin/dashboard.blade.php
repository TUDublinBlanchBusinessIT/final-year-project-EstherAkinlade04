<!DOCTYPE html>
<html>
<head>
    <title>Vault Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FullCalendar CDN -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>

<body class="bg-gradient-to-br from-purple-100 via-indigo-100 to-purple-200 min-h-screen">

<div class="flex">

<!-- SIDEBAR -->
<aside id="sidebar"
       class="bg-indigo-900 text-white w-64 min-h-screen p-6 transition-all duration-300 shadow-2xl">

    <div class="flex justify-between items-center mb-10">
        <h2 class="text-2xl font-bold tracking-wide">💎 Vault Admin</h2>
        <button onclick="toggleSidebar()" class="text-white text-xl hover:scale-110 transition">☰</button>
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-lg transition">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.classes.create') }}"
       class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-lg transition">
        ➕ Create Class
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button class="w-full bg-purple-600 py-2 rounded-lg hover:bg-purple-500 hover:shadow-xl hover:scale-105 transition">
            🚪 Logout
        </button>
    </form>

</aside>

<!-- MAIN -->
<main class="flex-1 p-10">

<h1 class="text-4xl font-extrabold text-indigo-900 mb-12">
    Welcome back, {{ auth()->user()->name }} 👑
</h1>

<!-- STATS -->
<div class="grid grid-cols-4 gap-8 mb-14">

    @foreach([
        ['Total Users', $totalUsers],
        ['Total Classes', $totalClasses],
        ['Total Bookings', $totalBookings]
    ] as $stat)
    <div class="bg-white/80 backdrop-blur-lg p-8 rounded-3xl shadow-xl hover:scale-105 hover:shadow-2xl transition duration-300 text-center">
        <p class="text-gray-500 mb-2">{{ $stat[0] }}</p>
        <h2 class="text-4xl font-bold text-indigo-700">{{ $stat[1] }}</h2>
    </div>
    @endforeach

    <div class="bg-gradient-to-r from-green-200 to-emerald-300 p-8 rounded-3xl shadow-xl hover:scale-105 hover:shadow-2xl transition duration-300 text-center">
        <p class="text-gray-700 mb-2">Total Revenue</p>
        <h2 class="text-4xl font-bold text-green-800">
            €{{ number_format($totalRevenue, 2) }}
        </h2>
    </div>

</div>

<!-- Calendar -->
<div class="bg-white p-8 rounded-3xl shadow-xl mb-14">
    <h2 class="text-2xl font-bold text-indigo-800 mb-6">📅 Class Schedule Overview</h2>
    <div id="calendar"></div>
</div>

<h2 class="text-3xl font-bold text-indigo-900 mb-8">📚 Manage Classes</h2>

@foreach($classes as $class)

<div class="bg-white/90 backdrop-blur-lg p-8 rounded-3xl shadow-xl mb-4 hover:-translate-y-1 hover:shadow-2xl transition duration-300">

    <div class="flex justify-between">

        <div>
            <h3 class="text-2xl font-bold text-indigo-800 mb-2">
                {{ $class->name }}
            </h3>

            <p class="text-gray-500 mb-2">
                📅 {{ $class->class_time->format('d M Y H:i') }}
            </p>

            <p class="font-semibold mb-2">
                💰 €{{ $class->price }}
            </p>

            <span class="px-3 py-1 rounded-full text-sm
                @if($class->status == 'full') bg-red-100 text-red-700
                @elseif($class->status == 'past') bg-gray-200 text-gray-600
                @elseif($class->status == 'cancelled') bg-black text-white
                @else bg-green-100 text-green-700
                @endif">
                {{ strtoupper($class->status) }}
            </span>

            <!-- Capacity Bar -->
            <div class="w-72 bg-gray-200 rounded-full h-3 mt-4 overflow-hidden">
                <div class="bg-indigo-600 h-3 rounded-full transition-all duration-700"
                     style="width: {{ $class->fill_percentage }}%">
                </div>
            </div>

            <p class="text-sm mt-2">
                👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <button onclick="toggleMembers({{ $class->id }})"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 hover:scale-105 hover:shadow-xl transition">
                👥 View Members
            </button>

            @if(!$class->is_cancelled)
            <form method="POST" action="{{ route('admin.classes.cancel', $class->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 hover:scale-105 hover:shadow-xl transition">
                    Cancel Class
                </button>
            </form>
            @endif
        </div>

    </div>

</div>

<!-- ✅ MEMBERS SECTION ADDED -->
<div id="members-{{ $class->id }}" class="hidden bg-indigo-50 p-6 rounded-2xl shadow-inner mb-10">

    <h4 class="text-lg font-bold text-indigo-800 mb-4">
        👥 Booked Members
    </h4>

    @if($class->bookings->count() > 0)

        <ul class="space-y-3">
            @foreach($class->bookings as $booking)
                <li class="flex justify-between bg-white p-4 rounded-xl shadow-sm">
                    <div>
                        <p class="font-semibold text-indigo-900">
                            {{ $booking->user->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $booking->user->email }}
                        </p>
                    </div>

                    <span class="text-sm text-gray-400">
                        {{ $booking->created_at->format('d M Y') }}
                    </span>
                </li>
            @endforeach
        </ul>

    @else
        <p class="text-gray-500">No members booked yet.</p>
    @endif

</div>

@endforeach

{{ $classes->links() }}

</main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('w-20');
}

function toggleMembers(id) {
    document.getElementById('members-' + id)?.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        height: 500,
        events: [
            @foreach($classes as $class)
            {
                title: "{{ $class->name }}",
                start: "{{ $class->class_time }}"
            },
            @endforeach
        ]
    });
    calendar.render();
});
</script>

</body>
</html>