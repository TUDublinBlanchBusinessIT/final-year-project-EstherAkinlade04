<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; margin:40px;">

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

    <div style="background:white; border:1px solid #ddd; padding:20px; margin-bottom:20px; border-radius:8px; box-shadow:0 3px 8px rgba(0,0,0,0.05);">

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

        <hr>

        <h4>Booked Members:</h4>

        @if($class->users->isEmpty())
            <p>No members booked yet.</p>
        @else
            <ul>
                @foreach($class->users as $user)
                    <li>
                        {{ $user->name }} ({{ $user->email }})
                    </li>
                @endforeach
            </ul>
        @endif

    </div>

@endforeach

@endif

<hr>

<a href="{{ route('dashboard') }}">Back to Dashboard</a>

</body>
</html>
