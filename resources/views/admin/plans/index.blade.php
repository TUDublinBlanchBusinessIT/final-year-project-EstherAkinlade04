<!DOCTYPE html>
<html>
<head>

<title>Membership Plans</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto p-8">

<h1 class="text-3xl font-bold mb-6">
Membership Plans
</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-4 rounded mb-4">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.membership-plans.store') }}" class="mb-8">
@csrf

<div class="grid grid-cols-3 gap-4">

<input name="name"
placeholder="Plan Name"
class="border p-3 rounded w-full">

<input name="price"
placeholder="Price (€)"
class="border p-3 rounded w-full">

<input name="duration_days"
placeholder="Duration (days)"
class="border p-3 rounded w-full">

</div>

<button class="bg-purple-600 text-white px-6 py-3 rounded mt-4 hover:bg-purple-700">
Create Plan
</button>

</form>


<div class="bg-white shadow rounded">

@foreach($plans as $plan)

<div class="p-4 border-b flex justify-between items-center">

<div>

<p class="font-semibold">
{{ $plan->name }}
</p>

<p class="text-sm text-gray-500">
€{{ $plan->price }} • {{ $plan->duration_days }} days
</p>

</div>

<form method="POST"
action="{{ route('admin.membership-plans.destroy', $plan->id) }}">
@csrf
@method('DELETE')

<button class="text-red-500 hover:text-red-700">
Delete
</button>

</form>

</div>

@endforeach

</div>

</div>

</body>
</html>