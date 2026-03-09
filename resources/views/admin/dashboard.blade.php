<!DOCTYPE html>
<html>
<head>
    <title>Vault Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gradient-to-br from-purple-100 via-indigo-100 to-purple-200 min-h-screen">

<div class="flex">

<!-- SIDEBAR -->
<aside class="bg-indigo-900 text-white w-64 min-h-screen p-6 shadow-2xl">

    <h2 class="text-2xl font-bold mb-10">💎 Vault Admin</h2>

    <a href="{{ route('admin.dashboard') }}" class="block mb-4 px-3 py-2 rounded-lg bg-indigo-700">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.classes.create') }}" class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 transition">
        ➕ Create Class
    </a>

    <a href="{{ route('admin.export.revenue') }}" class="block mb-4 px-3 py-2 rounded-lg hover:bg-indigo-700 transition">
        📥 Export Revenue CSV
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button class="w-full bg-purple-600 py-2 rounded-lg hover:bg-purple-500 transition">
            🚪 Logout
        </button>
    </form>

</aside>

<!-- MAIN -->
<main class="flex-1 p-10">

<h1 class="text-4xl font-extrabold text-indigo-900 mb-8">
    Welcome back, {{ auth()->user()->name }} 👑
</h1>

@if(session('success'))
<div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6">
    {{ session('error') }}
</div>
@endif

<!-- ================= STATS ================= -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-14">

@foreach([
['Users',$totalUsers],
['Classes',$totalClasses],
['Bookings',$totalBookings]
] as $stat)

<div class="bg-white p-6 rounded-2xl shadow hover:shadow-xl transition text-center">
<p class="text-gray-500">{{ $stat[0] }}</p>
<h2 class="text-2xl font-bold text-indigo-700">{{ $stat[1] }}</h2>
</div>

@endforeach

<div class="bg-green-100 p-6 rounded-2xl shadow text-center">
<p>Membership Revenue</p>
<h2 class="text-2xl font-bold text-green-800">€{{ number_format($membershipRevenue,2) }}</h2>
</div>

<div class="bg-blue-100 p-6 rounded-2xl shadow text-center">
<p>Class Revenue</p>
<h2 class="text-2xl font-bold text-blue-800">€{{ number_format($classRevenue,2) }}</h2>
</div>

<div class="bg-purple-200 p-6 rounded-2xl shadow text-center">
<p>Total Revenue</p>
<h2 class="text-2xl font-bold text-purple-900">€{{ number_format($totalRevenue,2) }}</h2>
</div>

<div class="bg-yellow-100 p-6 rounded-2xl shadow text-center">
<p>Active Members</p>
<h2 class="text-2xl font-bold text-yellow-800">{{ $activeMembers }}</h2>
</div>

<div class="bg-red-100 p-6 rounded-2xl shadow text-center">
<p>Expired Members</p>
<h2 class="text-2xl font-bold text-red-800">{{ $expiredMembers }}</h2>
</div>

<div class="bg-white p-6 rounded-2xl shadow text-center">
<p>Growth (MoM)</p>
<h2 class="text-2xl font-bold {{ $growthRate >= 0 ? 'text-green-600' : 'text-red-600' }}">
{{ number_format($growthRate,1) }}%
</h2>
</div>

<div class="bg-orange-100 p-6 rounded-2xl shadow text-center">
<p>Expiring Soon</p>
<h2 class="text-2xl font-bold text-orange-800">{{ $expiringSoon }}</h2>
</div>

<div class="bg-indigo-100 p-6 rounded-2xl shadow text-center">
<p>Forecast</p>
<h2 class="text-2xl font-bold text-indigo-800">
€{{ number_format($forecastNextMonth,2) }}
</h2>
</div>

</div>

<!-- ================= CHARTS ================= -->

<div class="grid lg:grid-cols-3 gap-10 mb-14">

<div class="bg-white p-8 rounded-3xl shadow-xl">
<h2 class="text-xl font-bold mb-6">📈 Monthly Revenue</h2>
<canvas id="revenueChart"></canvas>
</div>

<div class="bg-white p-8 rounded-3xl shadow-xl">
<h2 class="text-xl font-bold mb-6">🥧 Membership Breakdown</h2>
<canvas id="membershipChart"></canvas>
</div>

<div class="bg-white p-8 rounded-3xl shadow-xl">
<h2 class="text-xl font-bold mb-6">📊 Bookings Per Class</h2>
<canvas id="bookingChart"></canvas>
</div>

</div>

<!-- ================= CALENDAR ================= -->

<div class="bg-white p-8 rounded-3xl shadow-xl mb-14">
<h2 class="text-xl font-bold mb-6">📅 Class Schedule</h2>
<div id="calendar"></div>
</div>

<!-- ================= CLASS MANAGEMENT ================= -->

<h2 class="text-3xl font-bold text-indigo-900 mb-8">📚 Manage Classes</h2>

@foreach($classes as $class)

<div class="bg-white p-8 rounded-3xl shadow-xl mb-6 hover:shadow-2xl transition">

<div class="flex justify-between items-start">

<div>

<h3 class="text-2xl font-bold text-indigo-800">{{ $class->name }}</h3>

<p class="text-gray-500 mt-1">
📅 {{ $class->class_time->format('d M Y H:i') }}
</p>

<p class="mt-2 font-semibold">💰 €{{ $class->price }}</p>

@php
$status='active';
if($class->is_cancelled)$status='cancelled';
elseif($class->class_time < now())$status='past';
elseif($class->bookings_count >= $class->capacity)$status='full';
@endphp

<span class="inline-block mt-3 px-3 py-1 text-xs rounded-full
@if($status=='full') bg-red-100 text-red-700
@elseif($status=='past') bg-gray-200 text-gray-600
@elseif($status=='cancelled') bg-black text-white
@else bg-green-100 text-green-700
@endif">
{{ strtoupper($status) }}
</span>

<div class="w-64 bg-gray-200 rounded-full h-3 mt-4 overflow-hidden">
<div class="bg-indigo-600 h-3 rounded-full transition-all duration-700"
style="width: {{ ($class->bookings_count / $class->capacity) * 100 }}%">
</div>
</div>

<p class="text-sm mt-2">
👥 {{ $class->bookings_count }} / {{ $class->capacity }}
</p>

</div>

<div class="flex flex-col gap-3">

<button onclick="toggleMembers({{ $class->id }})"
class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition">
👥 View Members
</button>

@if(!$class->is_cancelled)
<form method="POST" action="{{ route('admin.classes.cancel',$class->id) }}">
@csrf
@method('PATCH')
<button class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 transition">
Cancel
</button>
</form>
@endif

</div>

</div>

<div id="members-{{ $class->id }}" class="hidden mt-6 bg-indigo-50 p-6 rounded-2xl">

@forelse($class->bookings as $booking)

<div class="flex justify-between bg-white p-4 rounded-xl mb-3">

<div>
<p class="font-semibold">{{ $booking->user->name }}</p>
<p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
</div>

<span class="text-sm text-gray-400">
{{ $booking->created_at->format('d M Y') }}
</span>

</div>

@empty
<p class="text-gray-500">No members booked yet.</p>
@endforelse

</div>

</div>

@endforeach

{{ $classes->links() }}

</main>
</div>

<script>

function toggleMembers(id){
document.getElementById('members-'+id)?.classList.toggle('hidden');
}

new Chart(document.getElementById('revenueChart'),{
type:'line',
data:{
labels:@json($monthlyRevenue->pluck('month')),
datasets:[{
label:'Revenue (€)',
data:@json($monthlyRevenue->pluck('total')),
borderColor:'#4f46e5',
backgroundColor:'rgba(79,70,229,0.1)',
fill:true,
tension:0.4
}]
}
});

new Chart(document.getElementById('membershipChart'),{
type:'doughnut',
data:{
labels:@json($membershipBreakdown->pluck('membership_type')),
datasets:[{
data:@json($membershipBreakdown->pluck('total'))
}]
}
});

new Chart(document.getElementById('bookingChart'),{
type:'bar',
data:{
labels:@json($bookingLabels),
datasets:[{
label:'Bookings',
data:@json($bookingCounts),
backgroundColor:'#8b5cf6',
borderRadius:8
}]
},
options:{
plugins:{legend:{display:false}},
scales:{y:{beginAtZero:true}}
}
});

document.addEventListener('DOMContentLoaded',function(){

new FullCalendar.Calendar(document.getElementById('calendar'),{
initialView:'dayGridMonth',
height:500,
events:[
@foreach($classes as $class)
{
title:"{{ $class->name }}",
start:"{{ $class->class_time }}"
},
@endforeach
]
}).render();

});

</script>

</body>
</html>