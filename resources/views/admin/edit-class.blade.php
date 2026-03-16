<!DOCTYPE html>
<html>
<head>
<title>Edit Class Notes</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-purple-50 min-h-screen flex items-center justify-center">

<div class="bg-white p-10 rounded-xl shadow w-full max-w-lg">

<h2 class="text-2xl font-bold mb-6">Edit Class Notes</h2>

<form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
@csrf
@method('PATCH')

<textarea
name="admin_notes"
class="w-full border p-3 rounded-lg"
rows="5"
>{{ $class->admin_notes }}</textarea>

<button class="mt-6 w-full bg-purple-600 text-white py-3 rounded-xl">
Update Notes
</button>

</form>

</div>

</body>
</html>