<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h1>Register</h1>

@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

<form method="POST" action="/register">
    @csrf

    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password (min 6 chars)" required><br><br>

    <button type="submit">Register</button>
</form>

<p>
    Already have an account?
    <a href="/login">Login</a>
</p>

</body>
</html>
