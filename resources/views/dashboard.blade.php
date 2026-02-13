<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<h1>Dashboard</h1>

<p>Welcome, {{ $user->name }}!</p>
<p>Email: {{ $user->email }}</p>

<hr>

<h2>My Bookings</h2>

@if($bookings->isEmpty())
    <p>You have not booked any classes yet.</p>
@else
    @foreach($bookings as $class)
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:10px;">
            <h3>{{ $class->name }}</h3>

            <p>
                <strong>Date:</strong>
                {{ \Carbon\Carbon::parse($class->class_time)->format('d F Y') }}
                <br>
                <strong>Time:</strong>
                {{ \Carbon\Carbon::parse($class->class_time)->format('H:i') }}
            </p>

            <!-- ✅ CANCEL BUTTON -->
            <form method="POST" action="{{ route('cancel.booking', $class->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Cancel Booking</button>
            </form>
        </div>
    @endforeach
@endif

<hr>

<nav>
    <a href="/classes">Browse Classes</a> |
    <form method="POST" action="/logout" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

</body>
</html>
