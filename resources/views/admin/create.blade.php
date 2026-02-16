<!DOCTYPE html>
<html>
<head>
    <title>Create Class</title>
</head>
<body>

<h1>Create New Fitness Class</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.classes.store') }}">
    @csrf

    <p>
        <label>Class Name:</label><br>
        <input type="text" name="name" required>
    </p>

    <p>
        <label>Description:</label><br>
        <textarea name="description" required></textarea>
    </p>

    <p>
        <label>Date & Time:</label><br>
        <input type="datetime-local" name="class_time" required>
    </p>

    <p>
        <label>Capacity:</label><br>
        <input type="number" name="capacity" min="1" required>
    </p>

    <button type="submit">Create Class</button>
</form>

<br>
<a href="{{ route('admin.dashboard') }}">Back to Admin Dashboard</a>

</body>
</html>
