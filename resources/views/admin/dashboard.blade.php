<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body id="body" class="bg-gray-100 transition-colors duration-300">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="bg-purple-700 text-white w-64 flex flex-col transition-all duration-300 overflow-hidden">

        <div class="flex items-center justify-between p-6 border-b border-purple-500">
            <span id="logoText"
                  class="text-2xl font-bold transition-all duration-300 whitespace-nowrap">
                The Vault
            </span>

            <button onclick="toggleSidebar()" class="text-xl">
                ☰
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-3">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3">
                <span>🏠</span>
                <span class="nav-text transition-all duration-300 whitespace-nowrap">
                    Dashboard
                </span>
            </a>

            <a href="{{ route('admin.classes.create') }}"
               class="nav-link flex items-center gap-3">
                <span>➕</span>
                <span class="nav-text transition-all duration-300 whitespace-nowrap">
                    Create Class
                </span>
            </a>

            <a href="{{ route('classes.index') }}"
               class="nav-link flex items-center gap-3">
                <span>📋</span>
                <span class="nav-text transition-all duration-300 whitespace-nowrap">
                    Member Classes
                </span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="nav-link flex items-center gap-3">
                <span>👤</span>
                <span class="nav-text transition-all duration-300 whitespace-nowrap">
                    Member Dashboard
                </span>
            </a>

        </nav>

        <div class="p-4 border-t border-purple-500 space-y-2">

            <button onclick="toggleDarkMode()"
                    class="w-full bg-purple-900 py-2 rounded hover:bg-purple-800 transition nav-text">
                🌙 Toggle Dark Mode
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-purple-900 py-2 rounded hover:bg-purple-800 transition nav-text">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main id="mainContent" class="flex-1 p-10 transition-all duration-300">

        <div class="flex justify-between items-center mb-8">
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="stat-card">
                <p>Total Users</p>
                <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Classes</p>
                <h2 class="text-3xl font-bold">{{ $totalClasses }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Bookings</p>
                <h2 class="text-3xl font-bold">{{ $totalBookings }}</h2>
            </div>

        </div>

        <!-- CHART -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">System Overview</h2>
            <canvas id="statsChart"></canvas>
        </div>

    </main>
</div>

<style>
.nav-link {
    padding: 10px 16px;
    border-radius: 8px;
    transition: 0.3s;
}
.nav-link:hover {
    background: #5b3ec8;
    box-shadow: 0 0 15px rgba(155,126,220,0.6);
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(155,126,220,0.25);
}
.dark-mode {
    background-color: #1e1e2f !important;
    color: white !important;
}
.dark-mode .stat-card {
    background: #2b2b45;
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const texts = document.querySelectorAll('.nav-text');
    const logo = document.getElementById('logoText');

    if (sidebar.classList.contains('w-64')) {

        // Collapse
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-16');

        texts.forEach(el => el.style.opacity = '0');
        logo.style.opacity = '0';

    } else {

        // Expand
        sidebar.classList.remove('w-16');
        sidebar.classList.add('w-64');

        texts.forEach(el => el.style.opacity = '1');
        logo.style.opacity = '1';
    }
}

// Dark Mode
function toggleDarkMode() {
    document.getElementById('body').classList.toggle('dark-mode');
}

// Chart
new Chart(document.getElementById('statsChart'), {
    type: 'bar',
    data: {
        labels: ['Users', 'Classes', 'Bookings'],
        datasets: [{
            data: [
                {{ $totalUsers }},
                {{ $totalClasses }},
                {{ $totalBookings }}
            ],
            backgroundColor: ['#6f54c6','#9b7edc','#4c1d95']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

</body>
</html>
