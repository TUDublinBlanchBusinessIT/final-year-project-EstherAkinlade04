<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

    <h2 class="text-2xl font-bold mb-6 text-center">Create Account</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input type="text" name="name"
               placeholder="Full Name"
               class="w-full p-3 border rounded"
               required>

        <input type="email" name="email"
               placeholder="Email Address"
               class="w-full p-3 border rounded"
               required>

        <!-- PASSWORD -->
        <div>
            <div class="relative">
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Password"
                       class="w-full p-3 border rounded"
                       oninput="checkStrength()"
                       required>

                <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-3 text-gray-500">
                    👁
                </button>
            </div>

            <!-- Strength Bar -->
            <div class="mt-2">
                <div class="h-2 bg-gray-200 rounded">
                    <div id="strengthBar"
                         class="h-2 rounded transition-all duration-300"
                         style="width:0%">
                    </div>
                </div>
                <p id="strengthText"
                   class="text-sm mt-1 font-semibold">
                </p>
            </div>

            <button type="button"
                    onclick="generatePassword()"
                    class="mt-2 text-purple-600 text-sm font-semibold">
                🔐 Generate Secure Password
            </button>
        </div>

        <input type="password"
               id="confirmPassword"
               name="password_confirmation"
               placeholder="Confirm Password"
               class="w-full p-3 border rounded"
               required>

        <button type="submit"
                class="w-full bg-purple-600 text-white p-3 rounded font-bold hover:bg-purple-700 transition">
            Register
        </button>
    </form>

    <p class="text-sm text-center mt-4">
        Already have an account?
        <a href="{{ route('login') }}"
           class="text-purple-600 font-semibold">
           Login
        </a>
    </p>
</div>

<script>
// PASSWORD STRENGTH CHECK
function checkStrength() {
    const password = document.getElementById("password").value;
    const bar = document.getElementById("strengthBar");
    const text = document.getElementById("strengthText");

    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    switch (strength) {
        case 0:
        case 1:
            bar.style.width = "20%";
            bar.className = "h-2 rounded bg-red-500 transition-all duration-300";
            text.innerText = "Weak";
            text.className = "text-sm mt-1 font-semibold text-red-600";
            break;

        case 2:
        case 3:
            bar.style.width = "60%";
            bar.className = "h-2 rounded bg-yellow-500 transition-all duration-300";
            text.innerText = "Medium";
            text.className = "text-sm mt-1 font-semibold text-yellow-600";
            break;

        case 4:
        case 5:
            bar.style.width = "100%";
            bar.className = "h-2 rounded bg-green-500 transition-all duration-300";
            text.innerText = "Strong";
            text.className = "text-sm mt-1 font-semibold text-green-600";
            break;
    }
}

// GENERATE PASSWORD
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

    checkStrength();
}

// SHOW / HIDE PASSWORD
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
