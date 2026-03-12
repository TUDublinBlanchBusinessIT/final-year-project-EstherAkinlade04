<!DOCTYPE html>
<html>
<head>

<title>QR Check-In</title>

<script src="https://cdn.tailwindcss.com"></script>

<script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-xl text-center">

<h1 class="text-3xl font-bold mb-6">
QR Member Check-In
</h1>

<p class="text-gray-500 mb-6">
Scan a member QR code to mark attendance.
</p>

<div id="reader" class="mx-auto"></div>

<p id="result" class="mt-6 text-green-600 font-semibold"></p>

<a href="{{ route('admin.dashboard') }}"
class="block mt-8 text-purple-600 hover:underline">
← Back to Admin Dashboard
</a>

</div>

<script>

function onScanSuccess(decodedText){

document.getElementById("result").innerText =
"Member scanned: " + decodedText;

fetch("/admin/checkin/" + decodedText,{
method:"POST",
headers:{
"X-CSRF-TOKEN":"{{ csrf_token() }}"
}
})

}

let scanner = new Html5QrcodeScanner(
"reader",
{fps:10, qrbox:250}
);

scanner.render(onScanSuccess);

</script>

</body>
</html>