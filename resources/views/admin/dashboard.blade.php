<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; margin:40px;">

<h1>Admin Dashboard</h1>

@if(session('success'))
    <p style="color:green; font-weight:bold;">{{ session('success') }}</p>
@endif

<!-- SYSTEM STATS -->
<div style="display:flex; gap:20px; margin-bottom:30px;">
    <div style="background:white; padding:20px; border-radius:8px; flex:1;">
        <h3>Total Users</h3>
        <p style="font-size:22px; font-weight:bold;">{{ $totalUsers }}</p>
    </div>

    <div style="background:white; padding:20px; border-radius:8px; flex:1;">
        <h3>Total Classes</h3>
        <p style="font-size:22px; font-weight:bold;">{{ $totalClasses }}</p>
    </div>

    <div style="background:white; padding:20px; border-radius:8px; flex:1;">
        <h3>Total Bookings</h3>
        <p style="font-size:22px; font-weight:bold;">{{ $totalBookings }}</p>
    </div>
</div>

<hr>

<a href="{{ route('admin.classes.create') }}"
   style="padding:10px 20px; background:#6f54c6; color:white; text-decoration:none; border-radius:6px;">
   + Create New Class
</a>

<hr>

<h2>Manage Classes</h2>

@foreach($classes as $class)

    @php
        $remaining = $class->capacity - $class->bookings_count;
    @endphp

    <div style="background:white; padding:20px; margin-bottom:20px; border-radius:8px;">

        <h3>{{ $class->name }}</h3>

        <p>
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
        </p>

        <p>
            Capacity: {{ $class->capacity }} |
            Booked: {{ $class->bookings_count }} |
            Remaining: {{ $remaining }}
        </p>

        <div style="margin-top:10px;">
            <a href="{{ route('admin.classes.edit', $class->id) }}"
               style="padding:6px 12px; background:#3490dc; color:white; text-decoration:none; border-radius:4px;">
               Edit
            </a>

            <form method="POST"
                  action="{{ route('admin.classes.destroy', $class->id) }}"
                  style="display:inline;"
                  onsubmit="return confirm('Are you sure you want to delete this class?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        style="padding:6px 12px; background:#e3342f; color:white; border:none; border-radius:4px; cursor:pointer;">
                    Delete
                </button>
            </form>
        </div>

    </div>

@endforeach

<hr>

<a href="{{ route('dashboard') }}">← Back to Member Dashboard</a>

</body>
</html>
