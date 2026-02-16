<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto p-8">

    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

    {{-- Flash Messages --}}
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

    {{-- Stats Cards --}}
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

    {{-- Chart --}}
    <div class="bg-white shadow rounded-xl p-6 mb-10">
        <h2 class="text-xl font-semibold mb-4">System Overview</h2>
        <canvas id="statsChart"></canvas>
    </div>

    {{-- Filters --}}
    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-3">

            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Search class..."
                   class="border px-3 py-2 rounded">

            <select name="status" class="border px-3 py-2 rounded">
                <option value="">All</option>
                <option value="upcoming" {{ $status=='upcoming'?'selected':'' }}>Upcoming</option>
                <option value="completed" {{ $status=='completed'?'selected':'' }}>Completed</option>
                <option value="full" {{ $status=='full'?'selected':'' }}>Full</option>
            </select>

            <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Filter
            </button>

        </form>

        <a href="{{ route('admin.classes.create') }}"
           class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            + Create Class
        </a>
    </div>

    {{-- Classes Table --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-4">Class</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Capacity</th>
                    <th class="p-4">Booked</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>

            <tbody>

            @foreach($classes as $class)

                @php
                    $remaining = $class->capacity - $class->bookings_count;
                    $isPast = \Carbon\Carbon::parse($class->class_time)->isPast();
                @endphp

                <tr class="border-t hover:bg-gray-50">

                    <td class="p-4 font-semibold">
                        {{ $class->name }}
                    </td>

                    <td class="p-4">
                        {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
                    </td>

                    <td class="p-4">{{ $class->capacity }}</td>
                    <td class="p-4">{{ $class->bookings_count }}</td>

                    <td class="p-4">
                        @if($isPast)
                            <span class="text-gray-500 font-semibold">Completed</span>
                        @elseif($remaining <= 0)
                            <span class="text-red-600 font-semibold">Full</span>
                        @else
                            <span class="text-green-600 font-semibold">Upcoming</span>
                        @endif
                    </td>

                    <td class="p-4 flex gap-3">

                        <a href="{{ route('admin.classes.edit', $class->id) }}"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.classes.destroy', $class->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>

                    </td>

                </tr>

            @endforeach

            </tbody>
        </table>

    </div>

    <div class="mt-6">
        {{ $classes->links() }}
    </div>

</div>

<script>
    const ctx = document.getElementById('statsChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Users', 'Classes', 'Bookings'],
            datasets: [{
                label: 'System Data',
                data: [{{ $totalUsers }}, {{ $totalClasses }}, {{ $totalBookings }}],
                backgroundColor: [
                    '#6f54c6',
                    '#9b7edc',
                    '#4c1d95'
                ]
            }]
        }
    });
</script>

</body>
</html>
