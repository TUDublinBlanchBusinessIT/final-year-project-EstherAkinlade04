<!DOCTYPE html>
<html>
<head>
    <title>Join Vault Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-2xl">

    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-indigo-800">
            💎 Let's Get Started
        </h1>
        <p class="text-gray-500 mt-2">Join Vault Fitness Today</p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between text-sm font-semibold mb-2">
            <span id="step1Text">1. Select Plan</span>
            <span id="step2Text" class="text-gray-400">2. Your Details</span>
            <span id="step3Text" class="text-gray-400">3. Payment</span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full">
            <div id="progressBar"
                 class="h-2 bg-indigo-600 rounded-full transition-all duration-500"
                 style="width:33%">
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- STEP 1 -->
        <div id="step1">

            <label class="block font-semibold mb-2">Select Your Gym</label>
            <select name="gym_location"
                    class="w-full p-3 border rounded mb-6"
                    required>
                <option value="">Choose Location</option>
                <option value="Dublin City">Dublin City</option>
                <option value="Cork Central">Cork Central</option>
                <option value="Galway West">Galway West</option>
            </select>

            <label class="block font-semibold mb-4">Choose Membership</label>

            <div class="grid grid-cols-3 gap-4">

                <label class="border p-4 rounded-xl cursor-pointer hover:shadow-xl transition">
                    <input type="radio" name="membership_type" value="basic" required>
                    <h3 class="font-bold text-indigo-700 mt-2">Basic</h3>
                    <p class="text-sm text-gray-500">Standard Access</p>
                </label>

                <label class="border p-4 rounded-xl cursor-pointer hover:shadow-xl transition">
                    <input type="radio" name="membership_type" value="pro">
                    <h3 class="font-bold text-purple-600 mt-2">Pro</h3>
                    <p class="text-sm text-gray-500">10% Discount</p>
                </label>

                <label class="border p-4 rounded-xl cursor-pointer hover:shadow-xl transition">
                    <input type="radio" name="membership_type" value="elite">
                    <h3 class="font-bold text-yellow-500 mt-2">Elite</h3>
                    <p class="text-sm text-gray-500">20% Discount + Priority</p>
                </label>

            </div>

            <button type="button"
                    onclick="nextStep(2)"
                    class="mt-8 w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition">
                Continue
            </button>
        </div>

        <!-- STEP 2 -->
        <div id="step2" class="hidden space-y-4">

            <input type="text" name="name" placeholder="Full Name"
                   class="w-full p-3 border rounded" required>

            <input type="email" name="email" placeholder="Email Address"
                   class="w-full p-3 border rounded" required>

            <input type="password" name="password"
                   placeholder="Password"
                   class="w-full p-3 border rounded" required>

            <input type="password" name="password_confirmation"
                   placeholder="Confirm Password"
                   class="w-full p-3 border rounded" required>

            <div class="flex gap-4">
                <button type="button"
                        onclick="nextStep(1)"
                        class="w-1/2 bg-gray-300 p-3 rounded-xl">
                    Back
                </button>

                <button type="button"
                        onclick="nextStep(3)"
                        class="w-1/2 bg-indigo-600 text-white p-3 rounded-xl">
                    Continue
                </button>
            </div>
        </div>

        <!-- STEP 3 -->
        <div id="step3" class="hidden space-y-4">

            <input type="text" placeholder="Card Number"
                   class="w-full p-3 border rounded" required>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" placeholder="Expiry Date"
                       class="p-3 border rounded" required>
                <input type="text" placeholder="CVC"
                       class="p-3 border rounded" required>
            </div>

            <div class="flex gap-4">
                <button type="button"
                        onclick="nextStep(2)"
                        class="w-1/2 bg-gray-300 p-3 rounded-xl">
                    Back
                </button>

                <button type="submit"
                        class="w-1/2 bg-green-600 text-white p-3 rounded-xl hover:bg-green-700 transition">
                    Complete Registration
                </button>
            </div>
        </div>

    </form>

</div>

<script>
function nextStep(step) {
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.add('hidden');

    document.getElementById('step' + step).classList.remove('hidden');

    const progress = document.getElementById('progressBar');
    if (step === 1) progress.style.width = "33%";
    if (step === 2) progress.style.width = "66%";
    if (step === 3) progress.style.width = "100%";
}
</script>

</body>
</html>