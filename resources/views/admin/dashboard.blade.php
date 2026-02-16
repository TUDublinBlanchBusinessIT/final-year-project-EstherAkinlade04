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

<h2>Search Classes</h2>

<form method="GET" action="{{ route('admin.dashboard') }}">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search class name">
    <button type="submit">Search</button>
</form>

<br>

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
@endphp

<div style="background:white; padding:20px; margin-bottom:20px; border-radius:8px;">

    <h3>{{ $class->name }}</h3>

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
          onsubmit="return confirm('Are you sure you want to delete this class?');">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>

</div>

@endforeach

{{ $classes->links() }}

<br>
<a href="{{ route('dashboard') }}">Back to Member Dashboard</a>

</body>
</html>
