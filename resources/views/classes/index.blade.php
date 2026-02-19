<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 to-indigo-100 p-10">

<h1 class="text-4xl font-bold text-indigo-900 mb-10">
    🏋️ Available Fitness Classes
</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-6 shadow">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-700 p-4 rounded mb-6 shadow">
        {{ session('error') }}
    </div>
@endif

@if($classes->isEmpty())
    <p>No classes available right now.</p>
@endif

<div class="grid md:grid-cols-2 gap-8">

@foreach($classes as $class)

<div class="bg-white p-6 rounded-2xl shadow-xl hover:scale-105 transition duration-300">

    <h2 class="text-2xl font-bold text-indigo-800 mb-2">
        {{ $class->name }}
    </h2>

    <p class="text-gray-600 mb-4">
        {{ $class->description }}
    </p>

    <p class="text-sm mb-2">
        📅 {{ $class->class_time->format('d M Y H:i') }}
    </p>

    <p class="text-sm mb-2">
        💰 €{{ $class->price }}
    </p>

    <p class="text-sm mb-4">
        👥 {{ $class->bookings_count }} / {{ $class->capacity }} booked
    </p>

    <form method="POST" action="{{ route('book.class', $class->id) }}">
        @csrf
        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition">
            Pay & Book
        </button>
    </form>

</div>

@endforeach

</div>

</body>
</html>
