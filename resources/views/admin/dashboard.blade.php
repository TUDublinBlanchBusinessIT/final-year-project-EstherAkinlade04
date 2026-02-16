<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body id="body" class="bg-gray-100 transition-all duration-500">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="sidebar bg-gradient-to-b from-purple-700 via-purple-800 to-indigo-900
               text-white w-64 flex flex-col transition-all duration-500 ease-in-out">

        <div class="flex items-center justify-between p-6 border-b border-purple-500">
            <span id="logoText"
                  class="text-2xl font-bold whitespace-nowrap transition-all duration-300">
                The Vault
            </span>

            <button onclick="toggleSidebar()" class="text-xl hover:scale-110 transition">
                ☰
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-3">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}"
               data-tooltip="Dashboard">
                <span>🏠</span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="{{ route('admin.classes.create') }}"
               class="nav-link {{ request()->routeIs('admin.classes.create') ? 'active-link' : '' }}"
               data-tooltip="Create Class">
                <span>➕</span>
                <span class="nav-text">Create Class</span>
            </a>

            <a href="{{ route('classes.index') }}"
               class="nav-link"
               data-tooltip="Member Classes">
                <span>📋</span>
                <span class="nav-text">Member Classes</span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="nav-link"
               data-tooltip="Member Dashboard">
                <span>👤</span>
                <span class="nav-text">Member Dashboard</span>
            </a>

        </nav>

        <div class="p-4 border-t border-purple-500 space-y-3">

            <button onclick="toggleDarkMode()"
                    class="w-full bg-purple-900 py-2 rounded hover:bg-purple-700 transition nav-text"
                    data-tooltip="Toggle Dark Mode">
                🌙 Dark Mode
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-purple-900 py-2 rounded hover:bg-purple-700 transition nav-text"
                        data-tooltip="Logout">
                    Logout
                </button>
            </form>

        </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 transition-all duration-500">

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                <p class="text-gray-600">
                    Welcome, {{ auth()->user()->name }}
                </p>
            </div>

            <span class="px-4 py-2 bg-purple-200 text-purple-900 rounded-full font-semibold">
                Role: {{ auth()->user()->role }}
            </span>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

            <div class="stat-card">
                <p>Total Users</p>
                <h2 id="usersCount" class="text-3xl font-bold">0</h2>
            </div>

            <div class="stat-card">
                <p>Total Classes</p>
                <h2 id="classesCount" class="text-3xl font-bold">0</h2>
            </div>

            <div class="stat-card">
                <p>Total Bookings</p>
                <h2 id="bookingsCount" class="text-3xl font-bold">0</h2>
            </div>

        </div>

        <!-- CHART -->
        <div class="bg-white shadow-xl rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">System Overview</h2>
            <canvas id="statsChart"></canvas>
        </div>

    </main>
</div>

<style>
.sidebar.collapsed {
    width: 70px !important;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 16px;
    border-radius: 8px;
    transition: 0.3s;
    position: relative;
}
.nav-link:hover {
    background: rgba(255,255,255,0.1);
    box-shadow: 0 0 15px rgba(155,126,220,0.6);
}
.active-link {
    background: rgba(255,255,255,0.2);
    box-shadow: inset 4px 0 0 white;
}

.nav-text {
    transition: opacity 0.3s;
}

/* Hide text when collapsed */
.sidebar.collapsed .nav-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* Tooltip */
.nav-link::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 75px;
    background: #111;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    opacity: 0;
    transition: 0.3s;
}
.sidebar.collapsed .nav-link:hover::after {
    opacity: 1;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(155,126,220,0.25);
}

.dark-mode {
    background-color: #111827 !important;
    color: white !important;
}
.dark-mode .stat-card {
    background: #1f2937;
}
</style>

<script>
// =============================
// SIDEBAR WITH MEMORY
// =============================
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');

    localStorage.setItem(
        'sidebarCollapsed',
        sidebar.classList.contains('collapsed')
    );
}

// Restore state on load
window.addEventListener('load', () => {
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
    }

    animateCounter('usersCount', {{ $totalUsers }});
    animateCounter('classesCount', {{ $totalClasses }});
    animateCounter('bookingsCount', {{ $totalBookings }});
});

// =============================
// DARK MODE
// =============================
function toggleDarkMode() {
    document.getElementById('body').classList.toggle('dark-mode');
}

// =============================
// ANIMATED COUNTERS
// =============================
function animateCounter(id, endValue) {
    let start = 0;
    const duration = 1200;
    const increment = endValue / (duration / 16);

    const counter = setInterval(() => {
        start += increment;
        if (start >= endValue) {
            start = endValue;
            clearInterval(counter);
        }
        document.getElementById(id).innerText = Math.floor(start);
    }, 16);
}

// =============================
// CHART
// =============================
const statsChart = new Chart(
    document.getElementById('statsChart'),
    {
        type: 'bar',
        data: {
            labels: ['Users', 'Classes', 'Bookings'],
            datasets: [{
                data: [
                    {{ $totalUsers }},
                    {{ $totalClasses }},
                    {{ $totalBookings }}
                ],
                backgroundColor: [
                    '#6f54c6',
                    '#9b7edc',
                    '#4c1d95'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    }
);

// =============================
// LIVE CHART UPDATE (every 15s)
// =============================
setInterval(() => {
    fetch("{{ route('admin.dashboard') }}")
        .then(res => res.text())
        .then(() => {
            // simple refresh for now
            location.reload();
        });
}, 15000);

</script>

</body>
</html>
