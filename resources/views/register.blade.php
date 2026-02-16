<h2>Register</h2>

@if ($errors->any())
    <div style="color:red; margin-bottom:15px;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <input type="text" name="name" placeholder="Full Name" required><br><br>

    <input type="email" name="email" placeholder="Email Address" required><br><br>

    <input type="password" id="password"
           name="password"
           placeholder="Password" required><br>

    <button type="button" onclick="generatePassword()">
        🔐 Generate Secure Password
    </button>

    <br><small>
        Must be 8+ characters with uppercase, lowercase, number & symbol.
    </small><br><br>

    <input type="password" id="confirmPassword"
           name="password_confirmation"
           placeholder="Confirm Password" required><br><br>

    <button type="submit">Register</button>
</form>

<p>
    Already have an account?
    <a href="{{ route('login') }}">Login</a>
</p>

<script>
function generatePassword() {
    const chars =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +
        "abcdefghijklmnopqrstuvwxyz" +
        "0123456789" +
        "!@#$%^&*()_+[]{}";

    let password = "";
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(
            Math.floor(Math.random() * chars.length)
        );
    }

    document.getElementById("password").value = password;
    document.getElementById("confirmPassword").value = password;
}
</script>
