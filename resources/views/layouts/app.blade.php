<!DOCTYPE html>
<html>
<head>
<title>Vault Fitness</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>

<style>
.nav-blur{
backdrop-filter:blur(16px);
background:rgba(255,255,255,0.85);
border-bottom:1px solid #ede9fe;
}
.lilac-text{color:#6d28d9;}
.btn-primary{
background:#6d28d9;
color:white;
padding:10px 18px;
border-radius:14px;
transition:.3s ease;
}
.btn-primary:hover{
background:#5b21b6;
box-shadow:0 0 15px rgba(124,58,237,0.4);
}
</style>

</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-100">

<!-- 🔥 GLOBAL NAVBAR -->
<nav class="fixed top-0 left-0 right-0 nav-blur z-50">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<h1 class="text-lg tracking-widest font-semibold lilac-text">
VAULT FITNESS
</h1>

<div class="flex gap-6 items-center text-sm">

<span class="font-medium text-gray-700">
👋 {{ auth()->user()->name }}
</span>

<a href="{{ route('dashboard') }}">Dashboard</a>

<a href="{{ route('classes.index') }}">Browse Classes</a>

<a href="{{ route('checkout') }}" class="btn-primary">
Renew
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="hover:text-red-500">Logout</button>
</form>

</div>
</div>
</nav>

<!-- CONTENT -->
<div class="pt-28 px-6 max-w-6xl mx-auto">
    @yield('content')
</div>

</body>
</html>