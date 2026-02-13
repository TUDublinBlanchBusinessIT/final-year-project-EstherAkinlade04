<!DOCTYPE html>
<html>
<head>
    <title>Available Classes</title>
</head>
<body>

<h1>Available Fitness Classes</h1>

@foreach($classes as $class)
    <div style="border:1px solid #ccc; padding:20px; margin-bottom:15px;">
        <h3>{{ $class->name }}</h3>
        <p>{{ $class->description }}</p>
        <p><strong>Date:</strong> {{ $class->class_time }}</p>
        <p><strong>Capacity:</strong> {{ $class->capacity }}</p>
    </div>
@endforeach

@if($classes->isEmpty())
    <p>No classes available yet.</p>
@endif

</body>
</html>
