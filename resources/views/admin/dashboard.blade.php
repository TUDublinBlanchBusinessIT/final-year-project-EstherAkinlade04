<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<aside class="bg-indigo-900 text-white w-64 p-6">
    <h2 class="text-2xl font-bold mb-8">THE VAULT ADMIN</h2>

    <a href="{{ route('admin.dashboard') }}" class="block mb-3 hover:text-gray-300">Dashboard</a>
    <a href="{{ route('admin.classes.create') }}" class="block mb-3 hover:text-gray-300">Create Class</a>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button class="bg-indigo-700 w-full py-2 rounded">Logout</button>
    </form>
</aside>

<main class="flex-1 p-10">

<h1 class="text-4xl font-bold mb-8">
    Admin Control Panel
</h1>

<!-- FILTERS -->
<div class="flex gap-4 mb-6">
    <a href="/admin" class="px-4 py-2 bg-gray-200 rounded">All</a>
    <a href="?status=upcoming" class="px-4 py-2 bg-green-200 rounded">Upcoming</a>
    <a href="?status=full" class="px-4 py-2 bg-red-200 rounded">Full</a>
    <a href="?status=past" class="px-4 py-2 bg-gray-300 rounded">Past</a>
</div>

<form method="GET" class="mb-8">
    <input type="text" name="search"
           placeholder="Search class..."
           class="border p-2 rounded w-64">
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Search</button>
</form>

<!-- STATS -->
<div class="grid grid-cols-4 gap-6 mb-10">

    <div class="bg-white p-6 rounded shadow text-center">
        <p>Total Users</p>
        <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
    </div>

    <div class="bg-white p-6 rounded shadow text-center">
        <p>Total Classes</p>
        <h2 class="text-3xl font-bold">{{ $totalClasses }}</h2>
    </div>

    <div class="bg-white p-6 rounded shadow text-center">
        <p>Total Bookings</p>
        <h2 class="text-3xl font-bold">{{ $totalBookings }}</h2>
    </div>

    <div class="bg-white p-6 rounded shadow text-center bg-green-100">
        <p>Total Revenue</p>
        <h2 class="text-3xl font-bold text-green-700">
            €{{ number_format($totalRevenue, 2) }}
        </h2>
    </div>

</div>

<h2 class="text-2xl font-bold mb-6">Classes</h2>

@foreach($classes as $class)

<div class="bg-white p-6 rounded shadow mb-8">

<div class="flex justify-between">

<div>
    <h3 class="text-xl font-bold">{{ $class->name }}</h3>
    <p class="text-gray-500">
        {{ $class->class_time->format('d M Y H:i') }}
    </p>

    <p class="mt-2 font-semibold">
        Price: €{{ $class->price }}
    </p>

    <p>
        {{ $class->bookings_count }} / {{ $class->capacity }} booked
    </p>

    <!-- Capacity Bar -->
    <div class="w-full bg-gray-200 rounded h-3 mt-2">
        <div class="bg-indigo-600 h-3 rounded"
             style="width: {{ ($class->bookings_count / $class->capacity) * 100 }}%">
        </div>
    </div>

</div>

<div>
    <a href="{{ route('admin.classes.export', $class->id) }}"
       class="bg-green-600 text-white px-3 py-1 rounded">
        Export
    </a>
</div>

</div>

</div>

@endforeach

{{ $classes->links() }}

</main>
</div>

</body>
</html>
