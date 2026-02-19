<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="bg-gradient-to-b from-purple-700 via-purple-800 to-indigo-900
                  text-white w-64 flex flex-col">

        <div class="p-6 border-b border-purple-500">
            <h2 class="text-2xl font-bold">The Vault</h2>
        </div>

        <nav class="flex-1 p-4 space-y-3">
            <a href="{{ route('admin.dashboard') }}"
               class="block p-3 rounded hover:bg-white/10
               {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('admin.classes.create') }}"
               class="block p-3 rounded hover:bg-white/10">
                ➕ Create Class
            </a>
        </nav>

        <div class="p-4 border-t border-purple-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-purple-900 py-2 rounded hover:bg-purple-700">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10">

        <h1 class="text-3xl font-bold mb-8">
            Welcome, {{ auth()->user()->name }}
        </h1>

        <!-- STATS -->
        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded shadow">
                <p>Total Users</p>
                <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p>Total Classes</p>
                <h2 class="text-3xl font-bold">{{ $totalClasses }}</h2>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p>Total Bookings</p>
                <h2 class="text-3xl font-bold">{{ $totalBookings }}</h2>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-6">Classes</h2>

        @foreach($classes as $class)

        <div class="bg-white p-6 rounded shadow mb-6">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">{{ $class->name }}</h3>

                    <p class="text-gray-500">
                        {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
                    </p>

                    <!-- Status -->
                    <span class="px-2 py-1 text-xs rounded
                        @if($class->status === 'full') bg-red-100 text-red-700
                        @elseif($class->status === 'past') bg-gray-200 text-gray-600
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst($class->status) }}
                    </span>
                </div>

                <div class="flex gap-2">

                    <form method="POST"
                          action="{{ route('admin.classes.markAll', $class->id) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            Mark All Present
                        </button>
                    </form>

                    <a href="{{ route('admin.classes.export', $class->id) }}"
                       class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                        Export CSV
                    </a>

                    <button onclick="toggleMembers({{ $class->id }})"
                            class="bg-purple-600 text-white px-3 py-1 rounded text-sm">
                        View Members
                    </button>
                </div>
            </div>

            <!-- Members -->
            <div id="members-{{ $class->id }}" class="hidden mt-6">

                @if($class->bookings->isEmpty())
                    <p class="text-gray-500">No bookings yet.</p>
                @else
                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th>Email</th>
                                <th>Payment</th>
                                <th>Attendance</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($class->bookings as $booking)
                            <tr class="border-t">
                                <td class="p-2">{{ $booking->user->name }}</td>
                                <td>{{ $booking->user->email }}</td>

                                <td>
                                    <span class="px-2 py-1 text-xs rounded
                                        {{ ($booking->payment_status ?? 'paid') === 'paid'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($booking->payment_status ?? 'paid') }}
                                    </span>
                                </td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.bookings.attendance', $booking->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-blue-600 underline">
                                            {{ $booking->attended ? 'Present' : 'Mark Present' }}
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.bookings.remove', $booking->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 underline">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>

        @endforeach

        {{ $classes->links() }}

    </main>
</div>

<script>
function toggleMembers(id) {
    document.getElementById('members-' + id).classList.toggle('hidden');
}
</script>

</body>
</html>
