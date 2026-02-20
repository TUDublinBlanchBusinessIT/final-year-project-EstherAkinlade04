<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 to-indigo-100 min-h-screen p-10">

<h1 class="text-4xl font-bold text-indigo-900 mb-10">
    Welcome back, {{ $user->name }} 👋
</h1>

<!-- STATS -->
<div class="grid md:grid-cols-4 gap-6 mb-12">

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Total Bookings</p>
        <h2 class="text-3xl font-bold text-indigo-700">
            {{ $bookings->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Upcoming</p>
        <h2 class="text-3xl font-bold text-green-600">
            {{ $upcoming->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Past Classes</p>
        <h2 class="text-3xl font-bold text-gray-600">
            {{ $past->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow text-center">
        <p class="text-gray-500">Total Spent</p>
        <h2 class="text-3xl font-bold text-indigo-800">
            €{{ number_format($totalSpent, 2) }}
        </h2>
    </div>

</div>

<!-- UPCOMING BOOKINGS -->
<h2 class="text-2xl font-bold text-indigo-800 mb-4">Upcoming Classes</h2>

@if($upcoming->isEmpty())
    <div class="bg-white p-6 rounded-xl shadow mb-10 text-gray-500">
        No upcoming bookings.
    </div>
@endif

@foreach($upcoming as $class)
<div class="bg-white p-6 rounded-2xl shadow mb-6">

    <div class="flex justify-between items-center">

        <div>
            <h3 class="text-xl font-bold text-indigo-800">
                {{ $class->name }}
            </h3>
            <p class="text-gray-500">
                {{ $class->class_time->format('d M Y H:i') }}
            </p>

            <span class="px-3 py-1 text-sm rounded-full
                {{ $class->pivot->payment_status == 'paid'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700' }}">
                {{ strtoupper($class->pivot->payment_status) }}
            </span>

        </div>

        <form method="POST" action="{{ route('cancel.booking', $class->id) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">
                Cancel
            </button>
        </form>

    </div>

</div>
@endforeach

<!-- PAST BOOKINGS -->
<h2 class="text-2xl font-bold text-indigo-800 mt-10 mb-4">Past Classes</h2>

@if($past->isEmpty())
    <div class="bg-white p-6 rounded-xl shadow text-gray-500">
        No past bookings yet.
    </div>
@endif

@foreach($past as $class)
<div class="bg-white p-6 rounded-2xl shadow mb-6">

    <div class="flex justify-between items-center">

        <div>
            <h3 class="text-xl font-bold text-indigo-800">
                {{ $class->name }}
            </h3>
            <p class="text-gray-500">
                {{ $class->class_time->format('d M Y H:i') }}
            </p>

            <span class="px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-700">
                Completed
            </span>
        </div>

        <span class="text-green-600 font-semibold">
            €{{ number_format($class->price, 2) }}
        </span>

    </div>

</div>
@endforeach

</body>
</html>