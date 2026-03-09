<!DOCTYPE html>
<html>
<head>

<title>Vault Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
theme:{
extend:{
colors:{
lilac:"#C4B5FD",
deep:"#5B21B6"
},
boxShadow:{
lux:"0 10px 30px rgba(0,0,0,0.08)",
glow:"0 0 20px rgba(139,92,246,0.35)"
}
}
}
}
</script>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

.panel{
display:none;
animation:fade .3s ease;
}

@keyframes fade{
from{opacity:0; transform:translateY(10px)}
to{opacity:1; transform:translateY(0)}
}

</style>

</head>

<body class="bg-gradient-to-br from-white via-purple-50 to-white min-h-screen text-gray-800">

<div class="flex">

<!-- SIDEBAR -->

<aside class="bg-white/80 backdrop-blur-xl border-r border-purple-100 w-64 min-h-screen p-6 shadow-lux">

<h2 class="text-2xl font-bold text-deep mb-10">
💎 Vault Admin
</h2>

<nav class="space-y-3">

<button onclick="openPanel('analytics')" class="block w-full text-left px-4 py-3 rounded-xl hover:bg-purple-50 hover:shadow-glow transition">
📊 Analytics
</button>

<button onclick="openPanel('revenue')" class="block w-full text-left px-4 py-3 rounded-xl hover:bg-purple-50 hover:shadow-glow transition">
💰 Revenue
</button>

<button onclick="openPanel('classes')" class="block w-full text-left px-4 py-3 rounded-xl hover:bg-purple-50 hover:shadow-glow transition">
🏋️ Classes
</button>

</nav>

<a href="{{ route('admin.classes.create') }}"
class="block mt-6 px-4 py-3 rounded-xl bg-purple-600 text-white text-center hover:bg-purple-700 transition">

➕ Create Class

</a>

<a href="{{ route('admin.export.revenue') }}"
class="block mt-3 px-4 py-3 rounded-xl bg-white border border-purple-200 text-center hover:bg-purple-50 transition">

📥 Export Revenue

</a>

<form method="POST" action="{{ route('logout') }}" class="mt-10">
@csrf
<button class="w-full bg-purple-600 text-white py-3 rounded-xl hover:bg-purple-700 transition shadow">
Logout
</button>
</form>

</aside>

<!-- MAIN -->

<main class="flex-1 p-14">

<h1 class="text-4xl font-bold text-gray-800 mb-12">
Welcome back, {{ auth()->user()->name }}
</h1>

<!-- CALENDAR (MAIN FEATURE) -->

<div class="bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux mb-10">

<h2 class="text-xl font-semibold text-gray-700 mb-6">
📅 Class Calendar
</h2>

<div id="calendar"></div>

</div>

<!-- ANALYTICS PANEL -->

<div id="analytics" class="panel bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux mb-10">

<h2 class="text-2xl font-bold mb-8">Analytics Overview</h2>

<div class="grid grid-cols-4 gap-6">

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Users</p>
<h3 class="text-3xl font-bold text-deep">{{ $totalUsers }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Bookings</p>
<h3 class="text-3xl font-bold text-deep">{{ $totalBookings }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Active Members</p>
<h3 class="text-3xl font-bold text-deep">{{ $activeMembers }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Growth</p>
<h3 class="text-3xl font-bold text-deep">{{ number_format($growthRate,1) }}%</h3>
</div>

</div>

</div>

<!-- REVENUE PANEL -->

<div id="revenue" class="panel bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux mb-10">

<h2 class="text-2xl font-bold mb-8">Revenue Analytics</h2>

<div class="grid lg:grid-cols-2 gap-10">

<div>
<canvas id="revenueChart"></canvas>
</div>

<div>
<canvas id="membershipChart"></canvas>
</div>

</div>

</div>

<!-- CLASSES PANEL -->

<div id="classes" class="panel bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux">

<h2 class="text-2xl font-bold mb-8">Class Management</h2>

@foreach($classes as $class)

<div class="flex justify-between items-center border-b py-4">

<div>

<p class="font-semibold text-gray-800">{{ $class->name }}</p>

<p class="text-sm text-gray-500">
{{ $class->class_time->format('d M Y H:i') }}
</p>

</div>

<div class="text-sm text-gray-500">
👥 {{ $class->bookings_count }}
</div>

</div>

@endforeach

{{ $classes->links() }}

</div>

</main>

</div>

<script>

function openPanel(panel){

document.querySelectorAll(".panel").forEach(p=>{
p.style.display="none"
})

document.getElementById(panel).style.display="block"

}

new Chart(document.getElementById('revenueChart'),{

type:'line',

data:{
labels:@json($monthlyRevenue->pluck('month')),
datasets:[{
data:@json($monthlyRevenue->pluck('total')),
borderColor:"#8B5CF6",
backgroundColor:"rgba(139,92,246,0.15)",
fill:true,
tension:.4
}]
}

})

new Chart(document.getElementById('membershipChart'),{

type:'doughnut',

data:{
labels:@json($membershipBreakdown->pluck('membership_type')),
datasets:[{
data:@json($membershipBreakdown->pluck('total'))
}]
}

})

document.addEventListener("DOMContentLoaded",()=>{

new FullCalendar.Calendar(document.getElementById("calendar"),{

initialView:"dayGridMonth",

height:550,

events:[
@foreach($classes as $class)
{
title:"{{ $class->name }}",
start:"{{ $class->class_time }}"
},
@endforeach
]

}).render()

})

</script>

</body>
</html>