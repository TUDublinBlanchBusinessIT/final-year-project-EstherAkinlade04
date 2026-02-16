<!DOCTYPE html>
<html>
<head>
    <title>Create Class</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; margin:40px;">

<h1>Create New Fitness Class</h1>

@if ($errors->any())
    <div style="background:#ffe6e6; padding:15px; border-radius:6px; margin-bottom:20px;">
        <strong>Please fix the following errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.classes.store') }}" 
      style="background:white; padding:30px; border-radius:10px; max-width:500px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
    @csrf

    <label>Class Name</label><br>
    <input type="text" name="name" 
           value="{{ old('name') }}"
           required 
           style="width:100%; padding:10px; margin-bottom:15px;"><br>

    <label>Description</label><br>
    <textarea name="description" 
              required 
              style="width:100%; padding:10px; margin-bottom:15px;">{{ old('description') }}</textarea><br>

    <label>Date & Time</label><br>
    <input type="datetime-local" 
           name="class_time" 
           value="{{ old('class_time') }}"
           required 
           style="width:100%; padding:10px; margin-bottom:15px;"><br>

    <label>Capacity</label><br>
    <input type="number" 
           name="capacity" 
           value="{{ old('capacity') }}"
           min="1"
           required 
           style="width:100%; padding:10px; margin-bottom:20px;"><br>

    <button type="submit" 
            style="padding:12px 25px; background:#6f54c6; color:white; border:none; border-radius:6px; cursor:pointer;">
        Create Class
    </button>
</form>

<br>

<a href="{{ route('admin.dashboard') }}">← Back to Admin Dashboard</a>

</body>
</html>
