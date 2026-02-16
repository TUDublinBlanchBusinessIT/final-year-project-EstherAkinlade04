<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body style="font-family:Arial; background:#f5f6fa; padding:40px;">

<h1>Admin Dashboard</h1>

@if(session('success'))
    <div style="background:#d4edda; padding:15px; border-radius:6px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#f8d7da; padding:15px; border-radius:6px; margin-bottom:20px;">
        {{ session('error') }}
    </div>
@endif

<hr>

<h2>System Stats</h2>

<div style="display:flex; gap:20px; margin-bottom:30px;">
    <div style="background:white; padding:20px; border-radius:8px; width:200px;">
        <strong>Total Users</strong><br>
        {{ $totalUsers }}
    </div>

    <div style="background:white; padding:20px; border-radius:8px; width:200px;">
        <strong>Total Classes</strong><br>
        {{ $totalClasses }}
    </div>

    <div style="background:white; padding:20px; border-radius:8px; width:200px;">
        <strong>Total Bookings</strong><br>
        {{ $totalBookings }}
    </div>
</div>

<hr>

<h2>Filter & Search</h2>

<form method="GET" action="{{ route('admin.dashboard') }}" style="margin-bottom:20px;">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search class name">

    <select name="status">
        <option value="">All</option>
        <option value="upcoming" {{ $status=='upcoming'?'selected':'' }}>Upcoming</option>
        <option value="completed" {{ $status=='completed'?'selected':'' }}>Completed</option>
        <option value="full" {{ $status=='full'?'selected':'' }}>Fully Booked</option>
    </select>

    <button type="submit">Apply</button>
</form>

<a href="{{ route('admin.classes.create') }}"
   style="background:#6f54c6; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">
   + Create Class
</a>

<hr>

<h2>Classes</h2>

@foreach($classes as $class)

@php
    $capacity = $class->capacity;
    $booked = $class->bookings_count;
    $remaining = $capacity - $booked;
    $isPast = \Carbon\Carbon::parse($class->class_time)->isPast();
@endphp

<div style="background:white; padding:20px; margin-bottom:20px; border-radius:8px;">

    <h3>{{ $class->name }}</h3>

    {{-- Status Badge --}}
    @if($isPast)
        <span style="color:gray; font-weight:bold;">Completed</span>
    @elseif($remaining <= 0)
        <span style="color:red; font-weight:bold;">Full</span>
    @else
        <span style="color:green; font-weight:bold;">Upcoming</span>
    @endif

    <p>
        {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y H:i') }}
    </p>

    <p>
        Capacity: {{ $capacity }} <br>
        Booked: {{ $booked }} <br>
        Remaining: {{ $remaining }}
    </p>

    <a href="{{ route('admin.classes.edit', $class->id) }}">Edit</a>

    <form method="POST"
          action="{{ route('admin.classes.destroy', $class->id) }}"
          style="display:inline;"
          onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>

</div>

@endforeach

{{ $classes->links() }}

<br><br>
<a href="{{ route('dashboard') }}">Back to Member Dashboard</a>

</body>
</html>
