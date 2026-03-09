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
lux:"0 12px 30px rgba(0,0,0,0.08)",
glow:"0 0 18px rgba(139,92,246,0.25)"
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
position:fixed;
top:0;
right:-100%;
width:500px;
height:100%;
background:white;
box-shadow:-10px 0 40px rgba(0,0,0,.15);
padding:40px;
overflow:auto;
transition:.35s ease;
z-index:50;
}

.panel.open{
right:0;
}

.overlay{
position:fixed;
inset:0;
background:rgba(0,0,0,.35);
display:none;
z-index:40;
}

.overlay.show{
display:block;
}

.sidebar-btn{
transition:.25s;
}

.sidebar-btn:hover{
transform:translateX(6px);
background:#f5f3ff;
}

</style>

</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-purple-100 min-h-screen text-gray-800">

<div class="flex">

<!-- SIDEBAR -->

<aside class="bg-white/80 backdrop-blur-xl border-r border-purple-100 w-64 min-h-screen p-6 shadow-lux">

<h2 class="text-2xl font-bold text-deep mb-12">
Vault Admin
</h2>

<nav class="space-y-4">

<button onclick="openPanel('analytics')" class="sidebar-btn w-full text-left px-4 py-3 rounded-xl">
Analytics
</button>

<button onclick="openPanel('revenue')" class="sidebar-btn w-full text-left px-4 py-3 rounded-xl">
Revenue
</button>

<button onclick="openPanel('classes')" class="sidebar-btn w-full text-left px-4 py-3 rounded-xl">
Classes
</button>

<a href="{{ route('admin.classes.create') }}"
class="block px-4 py-3 rounded-xl bg-purple-600 text-white text-center hover:bg-purple-700 transition">
Create Class
</a>

<a href="{{ route('admin.export.revenue') }}"
class="block px-4 py-3 rounded-xl border border-purple-200 text-center hover:bg-purple-50 transition">
Export Revenue
</a>

</nav>

<form method="POST" action="{{ route('logout') }}" class="mt-12">
@csrf
<button class="w-full bg-purple-600 text-white py-3 rounded-xl hover:bg-purple-700 transition">
Logout
</button>
</form>

</aside>

<!-- MAIN -->

<main class="flex-1 p-14">

<h1 class="text-4xl font-bold text-gray-800 mb-12">
Welcome back, {{ auth()->user()->name }}
</h1>

<!-- CALENDAR -->

<div class="bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux">

<h2 class="text-xl font-semibold mb-6 text-gray-700">
Class Calendar
</h2>

<div id="calendar"></div>

</div>

</main>

</div>

<!-- OVERLAY -->

<div id="overlay" class="overlay" onclick="closePanels()"></div>

<!-- ANALYTICS PANEL -->

<div id="analytics" class="panel">

<h2 class="text-2xl font-bold mb-8">Analytics</h2>

<div class="grid grid-cols-2 gap-6">

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Users</p>
<h3 class="text-3xl font-bold">{{ $totalUsers }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Bookings</p>
<h3 class="text-3xl font-bold">{{ $totalBookings }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Active</p>
<h3 class="text-3xl font-bold">{{ $activeMembers }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Growth</p>
<h3 class="text-3xl font-bold">{{ number_format($growthRate,1) }}%</h3>
</div>

</div>

</div>

<!-- REVENUE PANEL -->

<div id="revenue" class="panel">

<h2 class="text-2xl font-bold mb-8">Revenue</h2>

<canvas id="revenueChart"></canvas>

</div>

<!-- CLASSES PANEL -->

<div id="classes" class="panel">

<h2 class="text-2xl font-bold mb-8">Classes</h2>

@foreach($classes as $class)

<div class="border-b py-4 flex justify-between">

<div>

<p class="font-semibold">{{ $class->name }}</p>

<p class="text-sm text-gray-500">
{{ $class->class_time->format('d M Y H:i') }}
</p>

</div>

<div class="text-sm text-gray-500">
👥 {{ $class->bookings_count }}
</div>

</div>

@endforeach

</div>

<script>

/* PANEL CONTROL */

function openPanel(id){

document.getElementById("overlay").classList.add("show")

document.querySelectorAll(".panel").forEach(p=>p.classList.remove("open"))

document.getElementById(id).classList.add("open")

if(id === "revenue"){
loadRevenueChart()
}

}

function closePanels(){

document.getElementById("overlay").classList.remove("show")

document.querySelectorAll(".panel").forEach(p=>p.classList.remove("open"))

}

/* REVENUE CHART FIX */

let revenueChartLoaded = false;

function loadRevenueChart(){

if(revenueChartLoaded) return;

new Chart(document.getElementById('revenueChart'),{

type:'line',

data:{
labels:@json($monthlyRevenue->pluck('month') ?? []),

datasets:[{
label:"Revenue €",
data:@json($monthlyRevenue->pluck('total') ?? []),
borderColor:"#8B5CF6",
backgroundColor:"rgba(139,92,246,0.15)",
fill:true,
tension:.4
}]

},

options:{
plugins:{
legend:{display:false}
},
scales:{
y:{beginAtZero:true}
}
}

});

revenueChartLoaded = true;

}

/* CALENDAR */

document.addEventListener("DOMContentLoaded",()=>{

new FullCalendar.Calendar(document.getElementById("calendar"),{

initialView:"dayGridMonth",

height:600,

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