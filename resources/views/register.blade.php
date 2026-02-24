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

    <!-- PROGRESS BAR -->
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
                     class="w-96 h-28 bg-purple-700 text-white rounded-full flex items-center justify-center cursor-pointer hover:scale-105 transition shadow-xl">
                    <span class="text-xl font-semibold tracking-wider">MEMBERSHIPS</span>
                </div>

                <div onclick="showStudent()"
                     class="w-96 h-28 bg-purple-500 text-white rounded-full flex items-center justify-center cursor-pointer hover:scale-105 transition shadow-xl">
                    <span class="text-xl font-semibold tracking-wider">STUDENTS</span>
                </div>
            </div>

            <!-- STUDENT NOTICE -->
            <div id="studentNotice"
                 class="hidden max-w-4xl mx-auto mb-12 bg-yellow-50 border-4 border-yellow-600 text-gray-800 p-8 rounded-3xl shadow-xl text-center">
                <h3 class="text-2xl font-bold mb-4">⚠ Important Notice: ⚠ Student ID Required!</h3>
                <p class="text-lg leading-relaxed">
                    To activate your FLYEfit Student Membership, you MUST show a valid Student ID when entering the gym for the first time.
                    No ID, no membership. It's that simple.
                    <br><br>
                    Failure to provide a valid Student ID will result in the cancellation of your membership.
                    <br><br>
                    Let’s get you ready to train 💪
                </p>
            </div>

            <!-- PLANS -->
            <div id="plans" class="hidden grid md:grid-cols-3 gap-16">

                <!-- PAY AS YOU GO -->
                <div class="bg-gradient-to-b from-blue-500 via-blue-600 to-blue-900 text-white rounded-3xl p-12 shadow-2xl min-h-[720px] flex flex-col justify-between hover:scale-105 transition">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold tracking-widest mb-4">PAY AS YOU GO</h2>
                        <p class="text-lg mb-10">SELECT A GYM - SELECT AN OPTION</p>

                        <p class="text-7xl font-extrabold">€14</p>
                        <p class="text-2xl mt-2">1 day</p>

                        <button onclick="selectPlan('payg_1day')" type="button"
                            class="mt-6 border-2 border-white rounded-full px-10 py-3 text-lg font-semibold hover:bg-white hover:text-blue-800 transition">
                            SELECT
                        </button>

                        <hr class="my-10 opacity-40">

                        <div class="flex justify-between px-6">
                            <div>
                                <p class="text-4xl font-bold">€22</p>
                                <p>2 days</p>
                            </div>
                            <div>
                                <p class="text-4xl font-bold">€29</p>
                                <p>3 days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FLYE ROAMING -->
                <div class="bg-gradient-to-b from-blue-600 via-blue-700 to-blue-950 text-white rounded-3xl p-12 shadow-2xl min-h-[760px] flex flex-col justify-between hover:scale-105 transition">
                    <div class="text-center">

                        <h2 class="text-3xl font-bold tracking-widest">FLYE ROAMING</h2>
                        <p class="text-lg mt-2 mb-8">ACCESS TO ALL GYMS</p>

                        <div id="roamingStudentBadge" class="hidden mb-6">
                            <div class="text-5xl mb-2">🎓</div>
                            <div class="bg-white text-blue-900 px-6 py-2 rounded-full font-bold inline-block">
                                STUDENT OFFER
                            </div>
                        </div>

                        <p id="roamingJoiningFee" class="text-xl">€25 joining fee</p>

                        <p class="text-8xl font-extrabold mt-4">€41</p>
                        <p class="text-2xl mt-2">Monthly</p>

                        <button onclick="selectPlan('roaming_monthly')" type="button"
                            class="mt-6 border-2 border-white rounded-full px-10 py-3 text-lg font-semibold hover:bg-white hover:text-blue-900 transition">
                            SELECT
                        </button>

                        <hr class="my-10 opacity-40">

                        <p class="text-5xl font-bold">€123</p>
                        <p class="text-xl">3 x Monthly</p>

                        <p class="mt-6 text-5xl font-bold">€395</p>
                        <p class="text-xl">Annually</p>
                    </div>
                </div>

                <!-- MEMBERSHIP -->
                <div class="bg-gradient-to-b from-blue-500 via-blue-700 to-blue-900 text-white rounded-3xl p-12 shadow-2xl min-h-[760px] flex flex-col justify-between hover:scale-105 transition">
                    <div class="text-center">

                        <h2 class="text-3xl font-bold tracking-widest">MEMBERSHIP</h2>
                        <p class="text-lg mt-2 mb-8">ACCESS TO 1 FLYEFIT GYM</p>

                        <div id="membershipStudentBadge" class="hidden mb-6">
                            <div class="text-5xl mb-2">🎓</div>
                            <div class="bg-white text-blue-900 px-6 py-2 rounded-full font-bold inline-block">
                                STUDENT OFFER
                            </div>
                        </div>

                        <p id="membershipJoiningFee" class="text-xl">€25 joining fee</p>

                        <p class="text-8xl font-extrabold mt-4">€38</p>
                        <p class="text-2xl mt-2">Monthly</p>

                        <button onclick="selectPlan('membership_monthly')" type="button"
                            class="mt-6 border-2 border-white rounded-full px-10 py-3 text-lg font-semibold hover:bg-white hover:text-blue-900 transition">
                            SELECT
                        </button>

                        <hr class="my-10 opacity-40">

                        <p class="text-5xl font-bold">€114</p>
                        <p class="text-xl">3 x Monthly</p>

                        <p class="mt-6 text-5xl font-bold">€355</p>
                        <p class="text-xl">Annually</p>
                    </div>
                </div>

            </div>

            <!-- START DATE -->
            <div id="startDateSection" class="hidden max-w-md mx-auto mt-16">
                <label class="block text-lg font-semibold mb-3">Choose Start Date</label>
                <input type="date" name="membership_start_date"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600" required>
                <input type="hidden" name="membership_type" id="membership_type">

                <button type="button" onclick="goToStep2()"
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

    document.getElementById('membershipJoiningFee').innerText = "€25 joining fee";
    document.getElementById('roamingJoiningFee').innerText = "€25 joining fee";

    document.getElementById('membershipStudentBadge').classList.add('hidden');
    document.getElementById('roamingStudentBadge').classList.add('hidden');
}

function showStudent() {
    document.getElementById('plans').classList.remove('hidden');
    document.getElementById('studentNotice').classList.remove('hidden');

    document.getElementById('membershipJoiningFee').innerText = "€12.50 joining fee";
    document.getElementById('roamingJoiningFee').innerText = "€12.50 joining fee";

    document.getElementById('membershipStudentBadge').classList.remove('hidden');
    document.getElementById('roamingStudentBadge').classList.remove('hidden');
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