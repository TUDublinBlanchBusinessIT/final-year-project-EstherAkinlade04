<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
</head>
<body>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<h1>Available Fitness Classes</h1>

@foreach($classes as $class)
    <div style="border:1px solid #ccc; padding:20px; margin-bottom:15px;">
        
        <h3>{{ $class->name }}</h3>
        <p>{{ $class->description }}</p>

        <p>
            <strong>Date:</strong> 
            {{ \Carbon\Carbon::parse($class->class_time)->format('d M Y') }}
            <br>
            <strong>Time:</strong> 
            {{ \Carbon\Carbon::parse($class->class_time)->format('H:i') }}
        </p>

        <p><strong>Capacity:</strong> {{ $class->capacity }}</p>

        <!-- ✅ FORM MUST BE OUTSIDE <p> -->
        <form method="POST" action="{{ route('book.class', $class->id) }}">
            @csrf
            <button type="submit">Book Class</button>
        </form>

    </div>
@endforeach

@if($classes->isEmpty())
    <p>No classes available yet.</p>
@endif

</body>
</html>
