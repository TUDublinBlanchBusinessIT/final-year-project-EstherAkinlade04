<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; margin:40px;">

<h1>Admin Dashboard</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<p><strong>Welcome Admin:</strong> {{ auth()->user()->name }}</p>
<p><strong>Role:</strong> {{ auth()->user()->role }}</p>

<hr>

<h2>System Stats</h2>

<p><strong>Total Users:</strong> {{ $totalUsers ?? '—' }}</p>
<p><strong>Total Classes:</strong> {{ $totalClasses ?? '—' }}</p>
<p><strong>Total Bookings:</strong> {{ $totalBookings ?? '—' }}</p>

<hr>

<h2>Class Management</h2>

<a href="{{ route('admin.classes.create') }}"
   style="background:#4CAF50; color:white; padding:10px 15px; text-decoration:none; border-radius:6px;">
    ➕ Create New Class
</a>

<br><br>

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
                    <li>{{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        @endif

        <hr>

        <!-- Edit Button -->
        <a href="{{ route('admin.classes.edit', $class->id) }}"
           style="background:#2196F3; color:white; padding:6px 12px; text-decoration:none; border-radius:5px;">
            Edit
        </a>

        <!-- Delete Button -->
        <form method="POST"
              action="{{ route('admin.classes.delete', $class->id) }}"
              style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit"
                    style="background:#f44336; color:white; padding:6px 12px; border:none; border-radius:5px;">
                Delete
            </button>
        </form>

    </div>

@endforeach

@endif

<hr>

<a href="{{ route('dashboard') }}">Back to Dashboard</a>

</body>
</html>
