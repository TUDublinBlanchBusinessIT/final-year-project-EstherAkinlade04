<!DOCTYPE html>
<html>
<head>
    <title>Vault Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
       class="block mb-4 px-3 py-2 rounded-lg bg-indigo-700 shadow-lg">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.classes.create') }}"
       class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-lg transition">
        ➕ Create Class
    </a>

    <a href="{{ route('admin.export.revenue') }}"
       class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 transition">
        📥 Export Revenue CSV
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button class="w-full bg-purple-600 py-2 rounded-lg hover:bg-purple-500 transition">
            🚪 Logout
        </button>
    </form>

</aside>

<!-- MAIN -->
<main class="flex-1 p-10">

<h1 class="text-4xl font-extrabold text-indigo-900 mb-12">
    Welcome back, {{ auth()->user()->name }} 👑
</h1>

<!-- ================= STATS ================= -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-14">

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Users</p>
        <h2 class="text-2xl font-bold text-indigo-700">{{ $totalUsers }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Classes</p>
        <h2 class="text-2xl font-bold text-indigo-700">{{ $totalClasses }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Bookings</p>
        <h2 class="text-2xl font-bold text-indigo-700">{{ $totalBookings }}</h2>
    </div>

    <div class="bg-green-100 p-6 rounded-2xl shadow text-center">
        <p class="text-gray-700">Membership Revenue</p>
        <h2 class="text-2xl font-bold text-green-800">
            €{{ number_format($membershipRevenue, 2) }}
        </h2>
    </div>

    <div class="bg-blue-100 p-6 rounded-2xl shadow text-center">
        <p class="text-gray-700">Class Revenue</p>
        <h2 class="text-2xl font-bold text-blue-800">
            €{{ number_format($classRevenue, 2) }}
        </h2>
    </div>

    <div class="bg-purple-200 p-6 rounded-2xl shadow text-center">
        <p class="text-gray-700">Total Revenue</p>
        <h2 class="text-2xl font-bold text-purple-900">
            €{{ number_format($totalRevenue, 2) }}
        </h2>
    </div>

    <div class="bg-yellow-100 p-6 rounded-2xl shadow text-center">
        <p class="text-gray-700">Active Members</p>
        <h2 class="text-2xl font-bold text-yellow-800">
            {{ $activeMembers }}
        </h2>
    </div>

    <div class="bg-red-100 p-6 rounded-2xl shadow text-center">
        <p class="text-gray-700">Expired Members</p>
        <h2 class="text-2xl font-bold text-red-800">
            {{ $expiredMembers }}
        </h2>
    </div>

</div>

<!-- ================= REVENUE CHART ================= -->
<div class="bg-white p-8 rounded-3xl shadow-xl mb-14">
    <h2 class="text-2xl font-bold text-indigo-800 mb-6">📈 Monthly Membership Revenue</h2>
    <canvas id="revenueChart" height="100"></canvas>
</div>

<!-- ================= MEMBERSHIP BREAKDOWN ================= -->
<div class="bg-white p-8 rounded-3xl shadow-xl mb-14">
    <h2 class="text-2xl font-bold text-indigo-800 mb-6">🥧 Membership Breakdown</h2>
    <canvas id="membershipChart" height="100"></canvas>
</div>

<!-- ================= CALENDAR ================= -->
<div class="bg-white p-8 rounded-3xl shadow-xl mb-14">
    <h2 class="text-2xl font-bold text-indigo-800 mb-6">📅 Class Schedule Overview</h2>
    <div id="calendar"></div>
</div>

<!-- ================= MANAGE CLASSES ================= -->
<h2 class="text-3xl font-bold text-indigo-900 mb-8">📚 Manage Classes</h2>

@foreach($classes as $class)

<div class="bg-white p-8 rounded-3xl shadow-xl mb-4 hover:-translate-y-1 hover:shadow-2xl transition duration-300">

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

            <p class="text-sm mt-2">
                👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <button onclick="toggleMembers({{ $class->id }})"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition">
                👥 View Members
            </button>

            @if(!$class->is_cancelled)
            <form method="POST" action="{{ route('admin.classes.cancel', $class->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 transition">
                    Cancel Class
                </button>
            </form>
            @endif
        </div>

    </div>

</div>

<div id="members-{{ $class->id }}" class="hidden bg-indigo-50 p-6 rounded-2xl shadow-inner mb-10">
    <h4 class="text-lg font-bold text-indigo-800 mb-4">👥 Booked Members</h4>

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

// Revenue Line Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: @json($monthlyRevenue->pluck('month')),
        datasets: [{
            label: 'Revenue (€)',
            data: @json($monthlyRevenue->pluck('total')),
            borderWidth: 3,
            tension: 0.3
        }]
    }
});

// Membership Pie Chart
new Chart(document.getElementById('membershipChart'), {
    type: 'pie',
    data: {
        labels: @json($membershipBreakdown->pluck('membership_type')),
        datasets: [{
            data: @json($membershipBreakdown->pluck('total'))
        }]
    }
});

// Calendar
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