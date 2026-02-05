<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>

<p>Welcome, {{ auth()->user()->name }}!</p>
<p>Email: {{ auth()->user()->email }}</p>

<hr>

<p>This is your dashboard.</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>
