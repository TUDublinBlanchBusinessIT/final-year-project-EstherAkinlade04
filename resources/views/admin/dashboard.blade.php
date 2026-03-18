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

.progress{
height:6px;
background:#e5e7eb;
border-radius:999px;
overflow:hidden;
margin-top:6px;
}

.progress-bar{
height:100%;
background:#8B5CF6;
}

.action-btn{
font-size:12px;
padding:4px 10px;
border-radius:6px;
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

<a href="{{ route('admin.checkin') }}"
class="sidebar-btn block px-4 py-3 rounded-xl text-left">
QR Check-In
</a>

<a href="{{ route('admin.membership-plans.index') }}"
class="sidebar-btn block px-4 py-3 rounded-xl text-left">
Membership Plans
</a>

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

<!-- SEARCH -->
<div class="mb-10 relative">

<input id="adminSearch" type="text"
placeholder="🔍 Search users, classes, bookings..."
class="w-full p-4 border border-purple-200 rounded-xl"/>

<!-- FILTERS -->
<div class="flex gap-2 mt-3">
<button onclick="setFilter('all')" id="filter-all" class="px-3 py-1 bg-purple-600 text-white rounded">All</button>
<button onclick="setFilter('users')" id="filter-users" class="px-3 py-1 bg-gray-200 rounded">Users</button>
<button onclick="setFilter('classes')" id="filter-classes" class="px-3 py-1 bg-gray-200 rounded">Classes</button>
<button onclick="setFilter('bookings')" id="filter-bookings" class="px-3 py-1 bg-gray-200 rounded">Bookings</button>
</div>

<!-- RESULTS -->
<div id="searchResults"
class="absolute w-full bg-white shadow-xl rounded-xl mt-2 max-h-64 overflow-y-auto z-50"></div>

</div>

<div class="bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux">

<h2 class="text-xl font-semibold mb-6 text-gray-700">
Class Calendar
</h2>

<div id="calendar"></div>

</div>

</main>

</div>

<div id="overlay" class="overlay" onclick="closePanels()"></div>


<!-- ANALYTICS -->

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
<p class="text-sm text-gray-500">Active Members</p>
<h3 class="text-3xl font-bold">{{ $activeMembers }}</h3>
</div>

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Total Revenue</p>
<h3 class="text-3xl font-bold">€{{ number_format($totalRevenue,0) }}</h3>
</div>

</div>

</div>


<!-- REVENUE -->

<div id="revenue" class="panel">

<h2 class="text-2xl font-bold mb-8">Revenue</h2>

<canvas id="revenueChart"></canvas>

</div>


<!-- CLASSES -->

<div id="classes" class="panel">

<h2 class="text-2xl font-bold mb-8">Classes</h2>

@foreach($classes as $class)

@php
$fill = $class->fill_percentage ?? 0;
@endphp

<div class="border-b py-4">

<div class="flex justify-between">

<div>

<p class="font-semibold">

@if(isset($mostPopularClass) && $class->id === $mostPopularClass->id)
<span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded mr-2">
🔥 MOST POPULAR
</span>
@endif

{{ $class->name }}

</p>

<p class="text-sm text-gray-500">
{{ $class->class_time->format('d M Y H:i') }}
</p>

@if($class->admin_notes)
<p class="text-xs text-purple-600 mt-1">
📝 {{ $class->admin_notes }}
</p>
@endif

</div>

<div class="text-sm text-gray-500">
👥 {{ $class->bookings_count }} / {{ $class->capacity }}
</div>

</div>

<div class="progress">
<div class="progress-bar" style="width: {{ $fill }}%"></div>
</div>

@if($fill >= 80 && $fill < 100)
<p class="text-xs text-red-500 mt-1">
🔥 Filling fast
</p>
@endif


<!-- ADMIN ACTION BUTTONS -->

<div class="flex gap-2 mt-3">

<a href="{{ route('admin.classes.edit',$class->id) }}"
class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200">
Edit
</a>

<form method="POST" action="{{ route('admin.classes.cancel',$class->id) }}">
@csrf
@method('PATCH')
<button class="action-btn bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
Cancel
</button>
</form>

<form method="POST" action="{{ route('admin.classes.delete',$class->id) }}">
@csrf
@method('DELETE')
<button onclick="return confirm('Delete this class?')"
class="action-btn bg-red-100 text-red-600 hover:bg-red-200">
Delete
</button>
</form>

</div>

</div>

@endforeach

</div>


<script>

/* SEARCH SYSTEM */

let activeFilter = "all";

function setFilter(type){
activeFilter = type;

document.querySelectorAll('[id^="filter-"]').forEach(btn=>{
btn.classList.remove('bg-purple-600','text-white');
btn.classList.add('bg-gray-200');
});

document.getElementById('filter-'+type).classList.add('bg-purple-600','text-white');
}

function highlight(text, query){
if(!text) return "";
return text.replace(new RegExp(`(${query})`, 'gi'), '<span class="bg-yellow-200">$1</span>');
}

document.getElementById('adminSearch').addEventListener('keyup', function(){

let query = this.value;

if(query.length < 2){
document.getElementById('searchResults').innerHTML = "";
return;
}

fetch(`/admin/search?q=${query}`)
.then(res => res.json())
.then(data => {

let html = "";

/* USERS */
if((activeFilter==="all"||activeFilter==="users") && data.users){
data.users.forEach(u=>{
html += `
<div class="p-3 bg-purple-50 rounded-xl">
    👤 <strong>${highlight(u.name,query)}</strong><br>
    <span class="text-xs text-gray-500">${u.email}</span><br>
    <span class="text-xs text-purple-600">
        ${u.membership_type ?? 'No plan'}
    </span>
</div>`;
});
}

/* CLASSES */
if((activeFilter==="all"||activeFilter==="classes") && data.classes){
data.classes.forEach(c=>{
html += `
<a href="/admin/classes/${c.id}/edit"
class="block p-3 bg-blue-50 rounded-xl hover:bg-blue-100">
    📅 <strong>${highlight(c.name,query)}</strong><br>
    <span class="text-xs text-gray-500">
        ${c.class_time ?? ''}
    </span>
</a>`;
});
}

/* BOOKINGS */
if((activeFilter==="all"||activeFilter==="bookings") && data.bookings){

data.bookings.forEach(b=>{

let className = "No class";

if(b.fitnessClass && b.fitnessClass.name){
    className = b.fitnessClass.name;
}

html += `
<div class="p-3 bg-green-50 rounded-xl">
    📖 <strong>${highlight(b.user?.name ?? 'Unknown',query)}</strong><br>
    <span class="text-xs text-gray-500">
        ${highlight(className,query)}
    </span>
</div>`;
});
}
document.getElementById('searchResults').innerHTML = html || "No results";

});

});

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


/* REVENUE CHART */

let revenueChartLoaded = false;

function loadRevenueChart(){

if(revenueChartLoaded) return;

new Chart(document.getElementById('revenueChart'),{

type:'line',

data:{
labels:@json($monthlyRevenue->pluck('month')),
datasets:[{
label:"Revenue €",
data:@json($monthlyRevenue->pluck('total')),
borderColor:"#8B5CF6",
backgroundColor:"rgba(139,92,246,0.15)",
fill:true,
tension:.4
}]
},

options:{
plugins:{legend:{display:false}},
scales:{y:{beginAtZero:true}}
}

});

revenueChartLoaded = true;

}


/* CALENDAR */

document.addEventListener("DOMContentLoaded",()=>{

let calendar = new FullCalendar.Calendar(document.getElementById("calendar"),{

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

});

calendar.render();

})

</script>

</body>
</html>