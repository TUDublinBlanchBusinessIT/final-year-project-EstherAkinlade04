<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="bg-purple-700 text-white flex flex-col transition-all duration-300 w-64">

        <!-- Logo + Toggle -->
        <div class="flex items-center justify-between p-6 border-b border-purple-500">
            <span id="logoText" class="text-2xl font-bold">The Vault</span>

            <button onclick="toggleSidebar()"
                    class="text-white text-xl focus:outline-none">
                ☰
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded bg-purple-900 hover:bg-purple-600 transition">
                <span>🏠</span>
                <span class="linkText">Dashboard</span>
            </a>

            <a href="{{ route('admin.classes.create') }}"
               class="flex items-center gap-3 px-4 py-2 rounded hover:bg-purple-600 transition">
                <span>➕</span>
                <span class="linkText">Create Class</span>
            </a>

            <a href="{{ route('classes.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded hover:bg-purple-600 transition">
                <span>📋</span>
                <span class="linkText">Member Classes</span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded hover:bg-purple-600 transition">
                <span>👤</span>
                <span class="linkText">Member Dashboard</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-purple-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-purple-900 py-2 rounded hover:bg-purple-800 transition">
                    <span class="linkText">Logout</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-10 transition-all duration-300">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <p class="text-gray-600">
                Welcome, {{ auth()->user()->name }}
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white shadow rounded-xl p-6">
                <p class="text-gray-500">Total Users</p>
                <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <p class="text-gray-500">Total Classes</p>
                <h2 class="text-3xl font-bold">{{ $totalClasses }}</h2>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <p class="text-gray-500">Total Bookings</p>
                <h2 class="text-3xl font-bold">{{ $totalBookings }}</h2>
            </div>
        </div>

        <!-- Chart -->
        <div class="bg-white shadow rounded-xl p-6 mb-10">
            <h2 class="text-xl font-semibold mb-4">System Overview</h2>
            <canvas id="statsChart"></canvas>
        </div>

    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const texts = document.querySelectorAll('.linkText');
    const logo = document.getElementById('logoText');

    if (sidebar.classList.contains('w-64')) {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');

        texts.forEach(text => text.style.display = 'none');
        logo.style.display = 'none';
    } else {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');

        texts.forEach(text => text.style.display = 'inline');
        logo.style.display = 'inline';
    }
}

// Chart
new Chart(document.getElementById('statsChart'), {
    type: 'bar',
    data: {
        labels: ['Users', 'Classes', 'Bookings'],
        datasets: [{
            label: 'System Data',
            data: [{{ $totalUsers }}, {{ $totalClasses }}, {{ $totalBookings }}],
            backgroundColor: ['#6f54c6','#9b7edc','#4c1d95']
        }]
    }
});
</script>

</body>
</html>
