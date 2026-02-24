<!DOCTYPE html>
<html>
<head>
    <title>Join Vault Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen">

<div class="max-w-6xl mx-auto py-20 px-6">

    <!-- HEADER -->
    <div class="text-center mb-16">

        <div class="flex justify-center mb-10">
            <div class="relative w-36 h-36 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border border-purple-300"></div>
                <div class="absolute inset-4 rounded-full bg-white border border-purple-600 flex items-center justify-center">
                    <span class="text-5xl font-semibold tracking-widest text-purple-800">
                        VF
                    </span>
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


    <!-- PROGRESS -->
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

            <h2 class="text-2xl font-medium mb-6">Select a Gym</h2>

            <select name="gym_location"
                    id="gym_location"
                    class="w-full p-4 border border-gray-300 rounded-lg mb-16 focus:outline-none focus:ring-2 focus:ring-purple-600"
                    required>
                <option value="">Select a Gym</option>
                <option value="Dublin">Dublin</option>
                <option value="Cork">Cork</option>
                <option value="Galway">Galway</option>
            </select>


            <!-- PILL BUTTONS LIKE SCREENSHOT -->
            <div class="flex justify-center gap-16 mb-24">

                <!-- MEMBERSHIPS -->
                <div onclick="selectCategory('membership')"
                     id="membershipBox"
                     class="w-96 h-28 bg-purple-700 text-white 
                            rounded-full flex flex-col items-center justify-center 
                            cursor-pointer transition duration-300 
                            hover:scale-105 hover:shadow-2xl">

                    <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-purple-700 font-bold text-sm">VF</span>
                    </div>

                    <span class="text-xl font-semibold tracking-wider">
                        MEMBERSHIPS
                    </span>

                </div>


                <!-- STUDENT -->
                <div onclick="selectCategory('student')"
                     id="studentBox"
                     class="w-96 h-28 bg-purple-500 text-white 
                            rounded-full flex flex-col items-center justify-center 
                            cursor-pointer transition duration-300 
                            hover:scale-105 hover:shadow-2xl">

                    <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-purple-700 font-bold text-sm">🎓</span>
                    </div>

                    <span class="text-xl font-semibold tracking-wider">
                        STUDENT
                    </span>

                </div>

            </div>


            <!-- MEMBERSHIP PLANS -->
            <h2 class="text-2xl font-medium mb-6">Membership Plans</h2>

            <div class="grid md:grid-cols-3 gap-8 mb-12">

                <div onclick="choosePlan('standard_monthly')"
                     class="border border-gray-200 p-8 rounded-lg cursor-pointer hover:border-purple-700 hover:shadow-md transition">
                    <p class="text-3xl font-semibold">€38</p>
                    <p class="mt-2 font-medium">Monthly</p>
                    <p class="text-sm text-gray-500 mt-2">+ €25 Joining Fee</p>
                </div>

                <div onclick="choosePlan('standard_3month')"
                     class="border border-gray-200 p-8 rounded-lg cursor-pointer hover:border-purple-700 hover:shadow-md transition">
                    <p class="text-3xl font-semibold">€114</p>
                    <p class="mt-2 font-medium">3 x Monthly</p>
                    <p class="text-sm text-gray-500 mt-2">+ €25 Joining Fee</p>
                </div>

                <div onclick="choosePlan('standard_annual')"
                     class="border border-gray-200 p-8 rounded-lg cursor-pointer hover:border-purple-700 hover:shadow-md transition">
                    <p class="text-3xl font-semibold">€355</p>
                    <p class="mt-2 font-medium">Annually</p>
                    <p class="text-sm text-gray-500 mt-2">+ €25 Joining Fee</p>
                </div>

            </div>

            <input type="hidden" name="membership_type" id="membership_type">

            <button type="button"
                    onclick="goToStep2()"
                    class="w-full bg-purple-700 text-white py-4 rounded-lg hover:bg-purple-800 transition text-lg">
                Continue
            </button>

        </div>


        <!-- STEP 2 -->
        <div id="step2" class="hidden">

            <h2 class="text-2xl font-medium mb-8">Your Details</h2>

            <div class="space-y-5">

                <input type="text" name="name" placeholder="Full Name"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="email" name="email" placeholder="Email Address"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="password" name="password" placeholder="Password"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>

                <input type="date" name="membership_start_date"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>

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

function choosePlan(plan) {
    document.getElementById('membership_type').value = plan;
}

function selectCategory(type) {

    const membership = document.getElementById('membershipBox');
    const student = document.getElementById('studentBox');

    membership.classList.remove('bg-purple-900');
    student.classList.remove('bg-purple-900');

    if (type === 'membership') {
        membership.classList.add('bg-purple-900');
        document.getElementById('membership_type').value = 'membership';
    }

    if (type === 'student') {
        student.classList.add('bg-purple-900');
        document.getElementById('membership_type').value = 'student';
    }
}

function goToStep2() {

    if (!document.getElementById('gym_location').value) {
        alert("Please select a gym first.");
        return;
    }

    if (!document.getElementById('membership_type').value) {
        alert("Please select a membership option.");
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