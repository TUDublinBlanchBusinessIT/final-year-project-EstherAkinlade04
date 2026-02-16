<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>

<h1>Admin Dashboard</h1>

<p><strong>Welcome Admin:</strong> {{ auth()->user()->name }}</p>
<p><strong>Role:</strong> {{ auth()->user()->role }}</p>

<hr>

<h2>Class Overview</h2>

@if($classes->isEmpty())
    <p>No classes created yet.</p>
@else

@foreach($classes as $class)

    @php
        $capacity = $class->capacity;
        $booked = $class->bookings_count ?? 0;
        $remaining = $capacity - $booked;
    @endphp

    <div style="border:1px solid #ccc; padding:20px; margin-bottom:15px; border-radius:8px;">

        <h3>{{ $class->name }}</h3>

        <p>
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
        </p>

        <p>
            <strong>Capacity:</strong> {{ $capacity }} <br>
            <strong>Booked:</strong> {{ $booked }} <br>
            <strong>Remaining:</strong> {{ $remaining }}
        </p>

        @if($remaining <= 0)
            <p style="color:red; font-weight:bold;">Class Full</p>
        @else
            <p style="color:green; font-weight:bold;">Spots Available</p>
        @endif

    </div>

@endforeach

@endif

<hr>

<a href="/dashboard">Back to Dashboard</a>

</body>
</html>
