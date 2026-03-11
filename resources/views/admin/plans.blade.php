@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto p-8">

<h1 class="text-3xl font-bold mb-6">
Membership Plans
</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-4 rounded mb-4">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.plans.store') }}" class="mb-8">
@csrf

<div class="grid grid-cols-3 gap-4">

<input name="name"
placeholder="Plan Name"
class="border p-3 rounded">

<input name="price"
placeholder="Price (€)"
class="border p-3 rounded">

<input name="duration_days"
placeholder="Duration (days)"
class="border p-3 rounded">

</div>

<button class="bg-purple-600 text-white px-6 py-3 rounded mt-4">
Create Plan
</button>

</form>


<div class="bg-white shadow rounded">

@foreach($plans as $plan)

<div class="p-4 border-b flex justify-between">

<div>

<p class="font-semibold">
{{ $plan->name }}
</p>

<p class="text-sm text-gray-500">
€{{ $plan->price }} • {{ $plan->duration_days }} days
</p>

</div>

</div>

@endforeach

</div>

</div>

@endsection