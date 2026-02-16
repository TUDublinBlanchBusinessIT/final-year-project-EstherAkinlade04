<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-md">

    <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">
        Login
    </h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 flex items-center gap-2">
            <span class="text-2xl">✔</span>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf

        <input type="email"
               name="email"
               placeholder="Email"
               value="{{ old('email') }}"
               required
               class="w-full border p-3 rounded focus:ring-2 focus:ring-purple-500">

        <input type="password"
               name="password"
               placeholder="Password"
               required
               class="w-full border p-3 rounded focus:ring-2 focus:ring-purple-500">

        <button type="submit"
                class="w-full bg-purple-600 text-white py-3 rounded hover:bg-purple-700 transition">
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-sm">
        No account?
        <a href="/register" class="text-purple-600 font-semibold">
            Register
        </a>
    </p>

</div>

</body>
</html>
