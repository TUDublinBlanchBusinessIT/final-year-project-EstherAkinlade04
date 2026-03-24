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
<div class="flex gap-3 mb-6">

<a href="{{ route('admin.classes.create') }}"
class="bg-purple-600 text-white px-4 py-2 rounded-lg">
+ Add Class
</a>

<a href="{{ route('admin.checkin') }}"
class="bg-green-500 text-white px-4 py-2 rounded-lg">
Scan QR
</a>

</div>
<div class="flex gap-3 mb-6">

<a href="{{ route('admin.classes.create') }}"
class="bg-purple-600 text-white px-4 py-2 rounded-lg">
+ Add Class
</a>

<a href="{{ route('admin.checkin') }}"
class="bg-green-500 text-white px-4 py-2 rounded-lg">
Scan QR
</a>

</div>
<!-- 🚀 NEW DASHBOARD UPGRADE -->

<!-- 🧠 SMART INSIGHTS -->
<div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-lux mb-10">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        🧠 Smart Insights
    </h2>

    @foreach($insights as $insight)
        <div class="bg-purple-50 p-3 rounded-lg mb-2 text-sm">
            {{ $insight }}
        </div>
    @endforeach
</div>

<!-- 📈 GROWTH + PEAK -->
<div class="grid grid-cols-2 gap-6 mb-10">

    <div class="bg-white p-6 rounded-2xl shadow-lux text-center">
        <p class="text-sm text-gray-500">Revenue Growth</p>
        <h3 class="text-3xl font-bold text-purple-600">
            {{ $growthRate }}%
        </h3>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-lux text-center">
        <p class="text-sm text-gray-500">Peak Time</p>

        @if($peakTime)
            <h3 class="text-lg font-semibold">
                {{ $peakTime->day }} {{ $peakTime->hour }}:00
            </h3>
        @else
            <p class="text-gray-400 text-sm">No data</p>
        @endif
    </div>

</div>

<!-- 🏆 CLASS PERFORMANCE -->
<div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-lux mb-10">

    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        🏆 Class Performance
    </h2>

    @foreach($classPerformance as $class)

    <div class="mb-4">

        <div class="flex justify-between text-sm">
            <span>{{ $class['name'] }}</span>
            <span>{{ $class['score'] }}%</span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
            <div class="bg-purple-500 h-2 rounded-full"
                 style="width: {{ $class['score'] }}%">
            </div>
        </div>

    </div>

    @endforeach

</div>

<!-- 🚨 ALERTS -->
<div class="grid grid-cols-2 gap-6 mb-10">

    <div class="bg-red-50 p-6 rounded-2xl">
        <h3 class="font-semibold mb-2 text-red-600">Low Demand</h3>

        @forelse($lowBookingClasses as $class)
            <p class="text-sm">{{ $class->name }}</p>
        @empty
            <p class="text-sm text-gray-400">All good</p>
        @endforelse
    </div>

    <div class="bg-green-50 p-6 rounded-2xl">
        <h3 class="font-semibold mb-2 text-green-600">Almost Full</h3>

        @forelse($almostFullClasses as $class)
            <p class="text-sm">{{ $class->name }}</p>
        @empty
            <p class="text-sm text-gray-400">No classes near full</p>
        @endforelse
    </div>

</div>
<div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-lux mb-10">

    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        🏆 Top Members
    </h2>

    @foreach($topMembers as $member)

    <div class="flex justify-between items-center border-b py-2">

        <span class="font-medium">
            {{ $member->name }}
        </span>

        <span class="text-sm text-purple-600">
            {{ $member->bookings_count }} bookings
        </span>

    </div>

    @endforeach

</div>
<!-- 🔢 LIVE STATS -->
<div class="grid grid-cols-4 gap-6 mb-10 text-center">

<div class="bg-white p-6 rounded-xl shadow-lux">
<p class="text-sm text-gray-500">Users</p>
<h3 id="usersCount" class="text-3xl font-bold">0</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-lux">
<p class="text-sm text-gray-500">Bookings</p>
<h3 id="bookingsCount" class="text-3xl font-bold">0</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-lux">
<p class="text-sm text-gray-500">Revenue</p>
<h3 id="revenueCount" class="text-3xl font-bold">0</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-lux">
<p class="text-sm text-gray-500">Active</p>
<h3 id="activeCount" class="text-3xl font-bold">0</h3>
</div>

</div>
<div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-lux mb-10">

    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        ⏳ Expiring Memberships (Next 7 Days)
    </h2>

    @forelse($expiringSoonUsers as $user)

    <div class="flex justify-between border-b py-2">

        <span>{{ $user->name }}</span>

        <span class="text-sm text-orange-500">
            {{ \Carbon\Carbon::parse($user->end_date)->format('d M Y') }}
        </span>

    </div>

    @empty

    <p class="text-sm text-gray-500">
        No memberships expiring soon
    </p>

    @endforelse

</div>

<div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-lux mb-10">

    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        ❌ Cancelled Classes
    </h2>

    @forelse($cancelledClasses as $class)

    <div class="flex justify-between border-b py-2">

        <span>{{ $class->name }}</span>

        <span class="text-sm text-red-500">
            {{ \Carbon\Carbon::parse($class->class_time)->format('d M H:i') }}
        </span>

    </div>

    @empty

    <p class="text-sm text-gray-500">
        No cancelled classes
    </p>

    @endforelse

</div>

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
<div class="bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-lux mt-10">

<h2 class="text-xl font-semibold mb-6 text-gray-700">
🔥 Gym Activity (Bookings Per Day)
</h2>

<!-- LEGEND -->
<div class="flex items-center gap-2 text-xs text-gray-600 mb-3">
    <span>Less</span>
    <div class="w-3 h-3 bg-[#F3F0FF] rounded"></div>
    <div class="w-3 h-3 bg-[#C4B5FD] rounded"></div>
    <div class="w-3 h-3 bg-[#A78BFA] rounded"></div>
    <div class="w-3 h-3 bg-[#7C3AED] rounded"></div>
    <div class="w-3 h-3 bg-[#4C1D95] rounded"></div>
    <span>More</span>
</div>

<!-- MONTH LABELS (optional visual) -->
<div class="flex text-xs text-gray-500 mb-2 ml-6">
    <span class="mr-8">Jan</span>
    <span class="mr-8">Feb</span>
    <span class="mr-8">Mar</span>
    <span class="mr-8">Apr</span>
</div>

<!-- HEATMAP + DAY LABELS -->
<div class="flex gap-2">

    <!-- DAY LABELS -->
    <div class="flex flex-col justify-between text-xs text-gray-500 mr-2">
        <span>Mon</span>
        <span>Tue</span>
        <span>Wed</span>
        <span>Thu</span>
        <span>Fri</span>
        <span>Sat</span>
        <span>Sun</span>
    </div>

    <!-- GRID -->
    <div id="heatmapGrid" class="flex gap-2"></div>

</div>

</div>

</main>

</div>


<!-- ANALYTICS -->

<div id="analytics" class="panel">

<h2 class="text-2xl font-bold mb-8">Analytics</h2>

<div class="grid grid-cols-2 gap-6">

<div class="bg-purple-50 p-6 rounded-xl text-center">
<p class="text-sm text-gray-500">Users</p>
<h3 class="text-3xl font-bold">{{ $totalUsers }}</h3>
</div>
<canvas id="membershipChart" class="mt-10"></canvas>
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

<!-- ✅ ADD THIS HERE -->
<canvas id="membershipChart" class="mt-10"></canvas>
</div>

</div>


<!-- REVENUE -->

<div id="revenue" class="panel">

<h2 class="text-2xl font-bold mb-8">Revenue</h2>

<!-- Monthly Revenue -->
<canvas id="revenueChart"></canvas>

<!-- Revenue per Class -->
<canvas id="classRevenueChart" class="mt-10"></canvas>

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

// 📈 Monthly revenue
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

// 💰 Revenue per class (NEW 🔥)
new Chart(document.getElementById('classRevenueChart'),{
type:'bar',
data:{
labels:@json($classRevenueData->pluck('name')),
datasets:[{
label:"Revenue per Class €",
data:@json($classRevenueData->pluck('revenue')),
backgroundColor:"#C4B5FD"
}]
},
options:{
plugins:{legend:{display:false}},
scales:{y:{beginAtZero:true}}
}
});
new Chart(document.getElementById('membershipChart'),{
type:'pie',
data:{
labels:@json($membershipBreakdown->pluck('membership_type')),
datasets:[{
data:@json($membershipBreakdown->pluck('total')),
backgroundColor:["#C4B5FD","#A78BFA","#7C3AED","#5B21B6"]
}]
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

<!-- HEATMAP SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", () => {

const container = document.getElementById('heatmapGrid');
if(!container) return;

// ✅ Get data safely
let activityData = @json($activityData);

// ✅ fallback data
if(!activityData || !activityData.length){
    activityData = [
        {date:"2025-03-01", total:2},
        {date:"2025-03-02", total:5},
        {date:"2025-03-03", total:8}
    ];
}

// ✅ FIXED mapping
const dataMap = {};
activityData.forEach(d => {

    if(!d.date) return;

    const cleanDate = d.date.includes('T')
        ? d.date.split('T')[0]
        : d.date;

    dataMap[cleanDate] = d.total || 0;
});

console.log("Final dataMap:", dataMap);

// date formatter
function formatDate(date){
    return date.getFullYear()+"-"+String(date.getMonth()+1).padStart(2,'0')+"-"+String(date.getDate()).padStart(2,'0');
}

// range
const dates = Object.keys(dataMap).sort();
if(dates.length === 0) return;

let start = new Date(dates[0]);
let end = new Date(dates[dates.length-1]);

start.setDate(start.getDate() - start.getDay());
end.setDate(end.getDate() + (6 - end.getDay()));

const max = Math.max(...Object.values(dataMap)) || 1;

let current = new Date(start);

// build heatmap
while(current <= end){

    const col = document.createElement('div');
    col.style.display = "flex";
    col.style.flexDirection = "column";
    col.style.gap = "6px";

    for(let i=0;i<7;i++){

        const dateStr = formatDate(current);
        const value = dataMap[dateStr] || 0;
        const intensity = value/max;

        
        let color = "#F3F0FF"; // default (very light)

        if (value >= 1) color = "#C4B5FD";
        if (value >= 2) color = "#A78BFA";
        if (value >= 4) color = "#7C3AED";
        if (value >= 6) color = "#4C1D95";

        const box = document.createElement('div');

box.style.width = "14px";
box.style.height = "14px";
box.style.borderRadius = "3px";
box.style.backgroundColor = color;

// ✅ TEXT INSIDE BOX (optional but included)
box.innerText = value > 0 ? value : "";
box.style.fontSize = "8px";
box.style.display = "flex";
box.style.alignItems = "center";
box.style.justifyContent = "center";
box.style.color = value > 3 ? "#fff" : "#333";

// ✅ TOOLTIP
box.title = `${dateStr} → ${value} bookings`;

        col.appendChild(box);
        current.setDate(current.getDate()+1);
    }

    container.appendChild(col);
}

});
</script>
<script>
function animateValue(id, end, isCurrency = false) {
    const el = document.getElementById(id);
    if (!el) return;

    let start = 0;
    const duration = 1000; // 1 second
    const startTime = performance.now();

    function update(currentTime) {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const value = Math.floor(progress * end);

        if (isCurrency) {
            el.innerText = '€' + value.toLocaleString();
        } else {
            el.innerText = value.toLocaleString();
        }

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// Run animations
animateValue("usersCount", {{ $totalUsers }});
animateValue("bookingsCount", {{ $totalBookings }});
animateValue("revenueCount", {{ $totalRevenue }}, true);
animateValue("activeCount", {{ $activeMembers }});
</script>
</body>
</html>