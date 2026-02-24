<!DOCTYPE html>
<html>
<head>
    <title>Join Vault Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen">

<div class="max-w-7xl mx-auto py-20 px-6">

    <!-- HEADER -->
    <div class="text-center mb-16">

        <div class="flex justify-center mb-10">
            <div class="relative w-36 h-36 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border border-purple-300"></div>
                <div class="absolute inset-4 rounded-full bg-white border border-purple-600 flex items-center justify-center">
                    <span class="text-5xl font-semibold tracking-widest text-purple-800">VF</span>
                </div>
            </div>
        </div>

        <p class="text-base text-gray-600 mb-6">
            Already a member?
            <a href="{{ route('login') }}" class="text-purple-700 font-semibold hover:underline">
                Log In Here
            </a>
        </p>

        <h1 class="text-4xl font-light tracking-widest text-gray-800">
            LET'S GET STARTED
        </h1>
    </div>


    <!-- PROGRESS BAR (RESTORED) -->
    <div class="mb-24">

        <div class="relative w-full h-12 bg-purple-700 rounded-full flex items-center px-10">
            <div class="absolute left-10 right-10 h-[2px] bg-purple-400 rounded-full"></div>

            <div id="progressBar"
                 class="absolute left-10 h-[2px] bg-purple-200 rounded-full transition-all duration-700"
                 style="width: 33%">
            </div>

            <div id="progressDot"
                 class="absolute w-5 h-5 rounded-full bg-purple-200 border-2 border-purple-700 transition-all duration-700"
                 style="left: calc(33% - 10px);">
            </div>
        </div>

        <div class="flex justify-between mt-10 text-lg font-semibold tracking-wider text-gray-800">
            <div class="w-1/3 flex items-center gap-3">
                <span class="text-purple-700 text-2xl font-bold">1.</span>
                <span>SELECT GYM & PLAN</span>
            </div>
            <div class="w-1/3 flex items-center justify-center gap-3">
                <span class="text-purple-700 text-2xl font-bold">2.</span>
                <span>YOUR DETAILS</span>
            </div>
            <div class="w-1/3 flex items-center justify-end gap-3">
                <span class="text-purple-700 text-2xl font-bold">3.</span>
                <span>COMPLETE</span>
            </div>
        </div>
    </div>


    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- STEP 1 -->
        <div id="step1">

            <!-- Gym -->
            <h2 class="text-2xl font-semibold mb-6">Select a Gym</h2>

            <select name="gym_location"
                    id="gym_location"
                    class="w-full p-4 border border-gray-300 rounded-lg mb-16 focus:ring-2 focus:ring-purple-600"
                    required>
                <option value="">Select a Gym</option>
                <option value="Dublin">Dublin</option>
                <option value="Cork">Cork</option>
                <option value="Galway">Galway</option>
            </select>


            <!-- CATEGORY BUTTONS -->
            <div class="flex justify-center gap-16 mb-16">

                <div onclick="showMembership()"
                     class="w-96 h-28 bg-purple-700 text-white rounded-full 
                            flex items-center justify-center cursor-pointer hover:scale-105 transition shadow-xl">
                    <span class="text-xl font-semibold tracking-wider">MEMBERSHIPS</span>
                </div>

                <div onclick="showStudent()"
                     class="w-96 h-28 bg-purple-500 text-white rounded-full 
                            flex items-center justify-center cursor-pointer hover:scale-105 transition shadow-xl">
                    <span class="text-xl font-semibold tracking-wider">STUDENTS</span>
                </div>

            </div>


            <!-- STUDENT NOTICE -->
            <div id="studentNotice"
                 class="hidden max-w-4xl mx-auto mb-12 bg-yellow-50 border border-yellow-400 
                        text-yellow-900 p-8 rounded-2xl shadow">
                <h3 class="text-xl font-bold mb-4">⚠ Student ID Required</h3>
                <p>You must show valid Student ID on first entry.</p>
                <p class="mt-2 font-semibold">No ID, no membership.</p>
            </div>


            <!-- PLANS -->
            <div id="plans" class="hidden space-y-8">

                <!-- LONG CARD -->
                <div class="bg-gradient-to-r from-purple-700 to-purple-900 
                            text-white rounded-3xl p-10 shadow-2xl flex justify-between items-center">

                    <div>
                        <h3 class="text-xl font-semibold">FLYE ROAMING</h3>
                        <p class="mt-2">Access to all gyms</p>
                        <p class="mt-4 text-4xl font-bold">€41</p>
                        <p>Monthly + €25 Joining Fee</p>
                    </div>

                    <button type="button"
                            onclick="selectPlan('roaming')"
                            class="bg-white text-purple-800 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                        Select
                    </button>
                </div>


                <!-- LONG CARD -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-700 
                            text-white rounded-3xl p-10 shadow-2xl flex justify-between items-center">

                    <div>
                        <h3 class="text-xl font-semibold">MEMBERSHIP</h3>
                        <p class="mt-2">Access to 1 Flyefit gym</p>
                        <p class="mt-4 text-4xl font-bold">€38</p>
                        <p>Monthly + €25 Joining Fee</p>
                    </div>

                    <button type="button"
                            onclick="selectPlan('membership')"
                            class="bg-white text-purple-800 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                        Select
                    </button>
                </div>

            </div>


            <!-- START DATE -->
            <div id="startDateSection"
                 class="hidden max-w-md mx-auto mt-16">

                <label class="block text-lg font-semibold mb-3">
                    Choose Start Date
                </label>

                <input type="date"
                       name="membership_start_date"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600"
                       required>

                <input type="hidden" name="membership_type" id="membership_type">

                <button type="button"
                        onclick="goToStep2()"
                        class="mt-8 w-full bg-purple-700 text-white py-4 rounded-lg hover:bg-purple-800 transition text-lg">
                    Continue
                </button>
            </div>

        </div>


        <!-- STEP 2 -->
        <div id="step2" class="hidden mt-20">

            <h2 class="text-2xl font-semibold mb-8">Your Details</h2>

            <div class="space-y-5">
                <input type="text" name="name" placeholder="Full Name"
                       class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="email" name="email" placeholder="Email Address"
                       class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="password" name="password" placeholder="Password"
                       class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                       class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-purple-600" required>
            </div>

            <div class="mt-12">
                <button type="submit"
                        class="w-full bg-purple-700 text-white py-4 rounded-lg hover:bg-purple-800 transition text-lg">
                    Complete Registration
                </button>
            </div>

        </div>

    </form>

</div>


<script>

function showMembership() {
    document.getElementById('plans').classList.remove('hidden');
    document.getElementById('studentNotice').classList.add('hidden');
}

function showStudent() {
    document.getElementById('plans').classList.remove('hidden');
    document.getElementById('studentNotice').classList.remove('hidden');
}

function selectPlan(plan) {
    document.getElementById('membership_type').value = plan;
    document.getElementById('startDateSection').classList.remove('hidden');
}

function goToStep2() {

    if (!document.getElementById('gym_location').value) {
        alert("Please select a gym first.");
        return;
    }

    if (!document.getElementById('membership_type').value) {
        alert("Please select a plan.");
        return;
    }

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');

    document.getElementById('progressBar').style.width = "66%";
    document.getElementById('progressDot').style.left = "calc(66% - 10px)";
}

</script>

</body>
</html>