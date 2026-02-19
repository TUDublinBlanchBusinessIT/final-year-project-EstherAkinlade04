<!DOCTYPE html>
<html>
<head>
    <title>Vault Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-100 to-indigo-200 min-h-screen">

<div class="flex">

<!-- SIDEBAR -->
<aside id="sidebar" class="bg-indigo-900 text-white w-64 min-h-screen p-6 transition-all duration-300">

    <div class="flex justify-between items-center mb-10">
        <h2 class="text-2xl font-bold">💎 Vault Admin</h2>
        <button onclick="toggleSidebar()">☰</button>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="block mb-4 hover:text-purple-300">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.classes.create') }}" class="block mb-4 hover:text-purple-300">
        ➕ Create Class
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button class="bg-purple-600 w-full py-2 rounded hover:bg-purple-500">
            Logout
        </button>
    </form>

</aside>

<!-- MAIN -->
<main class="flex-1 p-10">

<h1 class="text-4xl font-bold text-indigo-900 mb-10">
    Welcome back, {{ auth()->user()->name }} 👑
</h1>

<!-- STATS CARDS -->
<div class="grid grid-cols-4 gap-6 mb-10">

    <div class="bg-white p-6 rounded-xl shadow-lg text-center">
        <p>Total Users</p>
        <h2 class="text-3xl font-bold text-indigo-700">{{ $totalUsers }}</h2>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg text-center">
        <p>Total Classes</p>
        <h2 class="text-3xl font-bold text-indigo-700">{{ $totalClasses }}</h2>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg text-center">
        <p>Total Bookings</p>
        <h2 class="text-3xl font-bold text-indigo-700">{{ $totalBookings }}</h2>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg text-center bg-green-100">
        <p>Total Revenue</p>
        <h2 class="text-3xl font-bold text-green-700">
            €{{ number_format($totalRevenue, 2) }}
        </h2>
    </div>

</div>

<h2 class="text-2xl font-bold text-indigo-900 mb-6">Classes</h2>

@foreach($classes as $class)

<div class="bg-white p-6 rounded-xl shadow-lg mb-8">

<div class="flex justify-between items-start">

<div>
    <h3 class="text-xl font-bold text-indigo-800">
        {{ $class->name }}
    </h3>

    <p class="text-gray-500">
        {{ $class->class_time->format('d M Y H:i') }}
    </p>

    <p class="mt-2 font-semibold">
        💰 €{{ $class->price }}
    </p>

    <!-- Status Badge -->
    <span class="px-3 py-1 rounded text-sm
        @if($class->status == 'full') bg-red-100 text-red-700
        @elseif($class->status == 'past') bg-gray-200 text-gray-600
        @elseif($class->status == 'cancelled') bg-black text-white
        @else bg-green-100 text-green-700
        @endif">
        {{ strtoupper($class->status) }}
    </span>

    <!-- Capacity Bar -->
    <div class="w-64 bg-gray-200 rounded h-3 mt-4">
        <div class="bg-indigo-600 h-3 rounded"
             style="width: {{ $class->fill_percentage }}%">
        </div>
    </div>

    <p class="text-sm mt-1">
        {{ $class->bookings_count }} / {{ $class->capacity }} booked
    </p>

    <!-- Admin Notes -->
    @if($class->admin_notes)
        <div class="mt-4 p-3 bg-purple-50 border-l-4 border-purple-400">
            📝 {{ $class->admin_notes }}
        </div>
    @endif

</div>

<div class="flex flex-col gap-2">

    <a href="{{ route('admin.classes.export', $class->id) }}"
       class="bg-green-600 text-white px-4 py-1 rounded text-sm">
        Export CSV
    </a>

    @if(!$class->is_cancelled)
    <form method="POST"
          action="{{ route('admin.classes.cancel', $class->id) }}">
        @csrf
        @method('PATCH')
        <button class="bg-red-600 text-white px-4 py-1 rounded text-sm">
            Cancel Class
        </button>
    </form>
    @endif

</div>

</div>

</div>

@endforeach

{{ $classes->links() }}

</main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('w-20');
}
</script>

</body>
</html>
