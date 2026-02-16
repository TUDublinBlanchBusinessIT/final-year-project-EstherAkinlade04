<h1>Edit Class</h1>

<form method="POST" action="{{ route('admin.update', $class->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $class->name }}" required><br><br>
    <textarea name="description" required>{{ $class->description }}</textarea><br><br>
    <input type="datetime-local"
           name="class_time"
           value="{{ \Carbon\Carbon::parse($class->class_time)->format('Y-m-d\TH:i') }}"
           required><br><br>
    <input type="number" name="capacity" value="{{ $class->capacity }}" required><br><br>

    <button type="submit">Update Class</button>
</form>

<a href="{{ route('admin.dashboard') }}">Back</a>
