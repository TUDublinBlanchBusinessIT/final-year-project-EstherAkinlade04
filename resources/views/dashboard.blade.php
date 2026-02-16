<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<h1>Dashboard</h1>

<p>Welcome, {{ $user->name }}!</p>
<p>Email: {{ $user->email }}</p>
<p><strong>Role:</strong> {{ auth()->user()->role }}</p>

<hr>

<h2>My Bookings</h2>

@if($bookings->isEmpty())
    <p>You have not booked any classes yet.</p>
@else

    @foreach($bookings as $class)

        @php
            $isPast = \Carbon\Carbon::parse($class->class_time)->isPast();
        @endphp

        <div style="border:1px solid #ccc; padding:15px; margin-bottom:10px; border-radius:6px;">

            <h3>{{ $class->name }}</h3>

            <p>
                <strong>Date:</strong>
                {{ \Carbon\Carbon::parse($class->class_time)->format('d F Y') }}
                <br>
                <strong>Time:</strong>
                {{ \Carbon\Carbon::parse($class->class_time)->format('H:i') }}
            </p>

            <!-- ✅ STATUS BADGE -->
            @if($isPast)
                <span style="background:#ccc; padding:5px 10px; border-radius:5px;">
                    Completed
                </span>
            @else
                <span style="background:#4CAF50; color:white; padding:5px 10px; border-radius:5px;">
                    Upcoming
                </span>
            @endif

            <br><br>

            <!-- ❌ Disable cancel if class is past -->
            @if(!$isPast)
                <form method="POST" action="{{ route('cancel.booking', $class->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Cancel Booking</button>
                </form>
            @endif

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
