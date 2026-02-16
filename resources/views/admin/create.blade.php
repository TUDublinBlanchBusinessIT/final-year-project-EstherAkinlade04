<h1>Create Class</h1>

<form method="POST" action="{{ route('admin.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Class Name" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <input type="datetime-local" name="class_time" required><br><br>
    <input type="number" name="capacity" placeholder="Capacity" required><br><br>

    <button type="submit">Create Class</button>
</form>

<a href="{{ route('admin.dashboard') }}">Back</a>
