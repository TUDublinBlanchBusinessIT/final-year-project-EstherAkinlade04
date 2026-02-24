<!DOCTYPE html>
<html>
<head>
    <title>Join Vault Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen">

<div class="max-w-6xl mx-auto py-16 px-6">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-light tracking-wide">
            LET'S GET STARTED
        </h1>

        <p class="text-sm text-gray-500 mt-3">
            Already a member?
            <a href="{{ route('login') }}" class="text-purple-600 font-semibold">
                Log In Here
            </a>
        </p>
    </div>

    <!-- PROGRESS -->
    <div class="mb-14">
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div id="progressBar"
                 class="bg-purple-600 h-3 rounded-full transition-all duration-500"
                 style="width: 33%">
            </div>
        </div>

        <div class="flex justify-between text-sm text-gray-500 mt-3">
            <span>1. SELECT GYM AND PLAN</span>
            <span>2. YOUR DETAILS</span>
            <span>3. COMPLETE</span>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- ================= STEP 1 ================= -->
        <div id="step1">

            <h2 class="text-2xl font-semibold mb-6">
                Select a Gym
            </h2>

            <select name="gym_location"
                    id="gym_location"
                    class="w-full p-4 border rounded-xl mb-10"
                    required>
                <option value="">Select a Gym</option>
                <option value="Dublin">Dublin</option>
                <option value="Cork">Cork</option>
                <option value="Galway">Galway</option>
            </select>

            <h2 class="text-2xl font-semibold mb-6">
                Choose Your Plan
            </h2>

            <div class="grid md:grid-cols-3 gap-6 mb-10">

                <div onclick="choosePlan('standard_monthly')"
                     class="border p-6 rounded-xl cursor-pointer hover:shadow-xl">
                    <p class="text-3xl font-bold">€38</p>
                    <p>Monthly</p>
                    <p class="text-sm text-gray-500">+ €25 Joining Fee</p>
                </div>

                <div onclick="choosePlan('standard_3month')"
                     class="border p-6 rounded-xl cursor-pointer hover:shadow-xl">
                    <p class="text-3xl font-bold">€114</p>
                    <p>3 x Monthly</p>
                    <p class="text-sm text-gray-500">+ €25 Joining Fee</p>
                </div>

                <div onclick="choosePlan('standard_annual')"
                     class="border p-6 rounded-xl cursor-pointer hover:shadow-xl">
                    <p class="text-3xl font-bold">€355</p>
                    <p>Annually</p>
                    <p class="text-sm text-gray-500">+ €25 Joining Fee</p>
                </div>

            </div>

            <input type="hidden" name="membership_type" id="membership_type">

            <button type="button"
                    onclick="goToStep2()"
                    class="w-full bg-purple-700 text-white py-4 rounded-xl">
                Continue
            </button>

        </div>

        <!-- ================= STEP 2 ================= -->
        <div id="step2" class="hidden">

            <h2 class="text-2xl font-semibold mb-6">
                Your Details
            </h2>

            <div class="space-y-4">

                <input type="text" name="name"
                       placeholder="Full Name"
                       class="w-full p-4 border rounded-xl"
                       required>

                <input type="email" name="email"
                       placeholder="Email Address"
                       class="w-full p-4 border rounded-xl"
                       required>

                <input type="password"
                       name="password"
                       placeholder="Password"
                       class="w-full p-4 border rounded-xl"
                       required>

                <input type="password"
                       name="password_confirmation"
                       placeholder="Confirm Password"
                       class="w-full p-4 border rounded-xl"
                       required>

                <input type="date"
                       name="membership_start_date"
                       class="w-full p-4 border rounded-xl"
                       required>

            </div>

            <div class="mt-10">
                <button type="submit"
                        class="w-full bg-purple-700 text-white py-4 rounded-xl">
                    Complete Registration
                </button>
            </div>

        </div>

    </form>

</div>

<script>
function choosePlan(plan) {
    document.getElementById('membership_type').value = plan;
}

function goToStep2() {

    if (!document.getElementById('gym_location').value) {
        alert("Please select a gym first.");
        return;
    }

    if (!document.getElementById('membership_type').value) {
        alert("Please select a membership plan.");
        return;
    }

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    document.getElementById('progressBar').style.width = "66%";
}
</script>

</body>
</html>