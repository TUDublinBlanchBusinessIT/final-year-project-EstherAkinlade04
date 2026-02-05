<h1>Dashboard</h1>

<p>Welcome, {{ auth()->user()->name }}!</p>
<p>Email: {{ auth()->user()->email }}</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>
