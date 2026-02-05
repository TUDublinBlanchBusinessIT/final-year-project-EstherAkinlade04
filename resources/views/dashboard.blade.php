<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>

<p>Welcome, {{ $user->name }}!</p>
<p>Email: {{ $user->email }}</p>

<hr>

<nav>
    <ul>
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="#">My Membership</a></li>
        <li><a href="#">Classes</a></li>
        <li><a href="#">Bookings</a></li>
    </ul>
</nav>

<hr>

<p>This is your dashboard. Fitness features will be added here.</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>
