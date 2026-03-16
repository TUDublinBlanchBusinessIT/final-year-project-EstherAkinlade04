<!DOCTYPE html>
<html>
<head>

<title>Create Class</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
background:linear-gradient(135deg,#f5f3ff,#ffffff);
font-family:ui-sans-serif,system-ui;
}
.card{
background:white;
border-radius:20px;
box-shadow:0 20px 40px rgba(0,0,0,0.08);
}
</style>

</head>

<body class="min-h-screen flex items-center justify-center p-10">

<div class="card w-full max-w-xl p-10">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Create New Class
</h1>

@if($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded mb-6">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('admin.classes.store') }}">

@csrf

<div class="mb-5">
<label class="block text-sm font-semibold mb-2">Class Name</label>
<input type="text"
name="name"
class="w-full border p-3 rounded-lg"
placeholder="HIIT Training">
</div>

<div class="mb-5">
<label class="block text-sm font-semibold mb-2">Description</label>
<textarea
name="description"
class="w-full border p-3 rounded-lg"
rows="3"
placeholder="High intensity interval training session">
</textarea>
</div>

<div class="mb-5">
<label class="block text-sm font-semibold mb-2">Date & Time</label>
<input
type="datetime-local"
name="class_time"
class="w-full border p-3 rounded-lg">
</div>

<div class="grid grid-cols-2 gap-4 mb-6">

<div>
<label class="block text-sm font-semibold mb-2">Capacity</label>
<input
type="number"
name="capacity"
class="w-full border p-3 rounded-lg"
placeholder="20">
</div>

<div>
<label class="block text-sm font-semibold mb-2">Price (€)</label>
<input
type="number"
step="0.01"
name="price"
class="w-full border p-3 rounded-lg"
placeholder="20">
</div>

</div>

<!-- NEW CLASS NOTES FIELD -->

<div class="mb-6">
<label class="block text-sm font-semibold mb-2">Admin Notes</label>

<textarea
name="admin_notes"
class="w-full border p-3 rounded-lg"
rows="3"
placeholder="Optional notes for this class (equipment, reminders etc.)">
</textarea>

</div>

<button
class="w-full bg-purple-600 text-white py-3 rounded-xl hover:bg-purple-700 transition">
Create Class
</button>

</form>

<a href="{{ route('admin.dashboard') }}"
class="block text-center mt-6 text-sm text-gray-500 hover:text-purple-600">
← Back to Dashboard
</a>

</div>

</body>
</html>