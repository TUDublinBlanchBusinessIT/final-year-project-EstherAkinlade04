<nav>
    <a href="/dashboard">Dashboard</a> |
    <form method="POST" action="/logout" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<hr>
