<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>The Vault Elite</title>

<style>
:root {
    --main: #9b7edc;
    --dark: #6f54c6;
}

body {
    margin:0;
    font-family:'Segoe UI', sans-serif;
}

/* HEADER */
.header {
    position:absolute;
    top:0;
    width:100%;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:25px 60px;
    z-index:100;
}

.logo {
    position:absolute;
    left:40px;
}

.logo a {
    font-size:26px;
    font-weight:800;
    text-decoration:none;
    color:white;
}

.nav {
    display:flex;
    gap:60px;
}

.nav a {
    color:white;
    text-decoration:none;
    font-weight:600;
}

.btn {
    padding:10px 24px;
    border-radius:30px;
}

.join { background:white; color:black !important; }
.login { border:1px solid white; }

/* HERO */
.hero { height:100vh; display:flex; }

.hero-left {
    width:35%;
    background:linear-gradient(180deg,var(--main),var(--dark));
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:60px;
}

.hero-left h1 { font-size:70px; }

.hero-left a {
    margin-top:25px;
    padding:14px 34px;
    background:white;
    color:var(--dark);
    border-radius:40px;
    text-decoration:none;
}

/* RIGHT */
.hero-right {
    width:65%;
    position:relative;
    overflow:hidden;
}

.hero-right::after {
    content:'';
    position:absolute;
    width:100%;
    height:100%;
    background:linear-gradient(90deg, rgba(0,0,0,0.6), transparent);
}

.slider {
    display:flex;
    height:100%;
    transition:0.8s ease;
}

.slide {
    min-width:100%;
    background-size:cover;
    background-position:center;
}

.steps {
    position:absolute;
    bottom:40px;
    left:50px;
    display:flex;
    gap:50px;
}

.step {
    color:white;
    opacity:0.6;
    cursor:pointer;
}

.step.active {
    opacity:1;
    border-bottom:3px solid var(--main);
}

/* VAULT+ */
.vaultplus {
    display:flex;
    justify-content:space-between;
    padding:120px 60px;
    background:#f5f1fb;
}

.circle {
    width:260px;
    height:260px;
    border-radius:50%;
    overflow:hidden;
    position:relative;
}

.circle img { width:100%; height:100%; object-fit:cover; }

.play {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:70px;
    height:70px;
    border:2px solid white;
    border-radius:50%;
    cursor:pointer;
}

.play::after {
    content:'';
    position:absolute;
    left:28px;
    top:20px;
    border-left:18px solid white;
    border-top:12px solid transparent;
    border-bottom:12px solid transparent;
}

/* VIDEO MODAL */
.video-modal {
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.85);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.video-content {
    position:relative;
    width:80%;
    max-width:800px;
}

.video-content video {
    width:100%;
    border-radius:10px;
}

.close {
    position:absolute;
    top:-40px;
    right:0;
    color:white;
    font-size:30px;
    cursor:pointer;
}

/* CLASS SLIDER */
.class-slider-section {
    padding:80px 40px;
}

.class-slider {
    display:flex;
    gap:20px;
    overflow-x:auto;
}

.class-card-horizontal {
    min-width:250px;
    background:#f5f1fb;
    border-radius:20px;
}

.class-card-horizontal img {
    width:100%;
    height:150px;
    object-fit:cover;
}
</style>
</head>

<body>
@php
$selectedGym = auth()->check() ? auth()->user()->gym_location : null;
@endphp
<header class="header">
    <div class="logo">
        <a href="{{ route('home') }}">The Vault</a>
    </div>

    <nav class="nav">
        <a href="{{ route('register') }}" class="btn join">Join</a>
        <a href="{{ route('login') }}" class="btn login">Login</a>
        <a href="{{ auth()->check() ? route('classes.index') : route('login') }}">Classes</a>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
<div class="hero-left">
    <h1 id="heroTitle">JOIN TODAY</h1>
    <p id="heroText">Start your fitness journey now.</p>
    <a href="{{ route('register') }}">Join Now</a>
</div>

<div class="hero-right" id="heroArea">
    <div class="slider" id="slider">
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1554284126-aa88f22d8b74')"></div>
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b')"></div>
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1517838277536-f5f99be501cd')"></div>
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1540497077202-7c8a3999166f')"></div>
    </div>

    <div class="steps">
        <div class="step active" onclick="goToSlide(0)">JOIN</div>
        <div class="step" onclick="goToSlide(1)">CLASSES</div>
        <div class="step" onclick="goToSlide(2)">STUDENTS</div>
        <div class="step" onclick="goToSlide(3)">RESULTS</div>
    </div>
</div>
</section>

<!-- VAULT+ -->
<section class="vaultplus">
    <div>
        <h2>THE VAULT+</h2>
        <p>150+ Vault classes anytime. Suitable for all levels.</p>
    </div>

    <div class="circle">
        <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e">
        <div class="play" onclick="openVideo()"></div>
    </div>
</section>
<!-- GOOGLE MAP -->
<section style="padding:100px 60px; background:#f5f1fb;">

    <h2 style="text-align:center; margin-bottom:30px;">
        📍 Our Locations
    </h2>

    <div id="map" style="height:500px; border-radius:20px;"></div>

</section>
<!-- CLASS SLIDER -->
<section class="class-slider-section">
<h2>Popular Classes</h2>

<div class="class-slider">
    <div class="class-card-horizontal">
        <img src="https://images.unsplash.com/photo-1518611012118-fc6d3d3b5d4c">
    </div>
    <div class="class-card-horizontal">
        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b">
    </div>
    <div class="class-card-horizontal">
        <img src="https://images.unsplash.com/photo-1517838277536-f5f99be501cd">
    </div>
</div>
</section>

<!-- VIDEO MODAL -->
<div class="video-modal" id="videoModal">
    <div class="video-content">
        <span class="close" onclick="closeVideo()">✕</span>

        <!-- FIXED VIDEO -->
        <video id="vaultVideo" controls muted playsinline>
            <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
        </video>

    </div>
</div>

<script>
let i=0;

function goToSlide(index){
    i=index;
    document.getElementById('slider').style.transform=`translateX(-${i*100}%)`;
}

setInterval(()=>{
    i=(i+1)%4;
    goToSlide(i);
},5000);

/* VIDEO FIX */
function openVideo(){
    const modal = document.getElementById("videoModal");
    const video = document.getElementById("vaultVideo");

    modal.style.display="flex";
    video.currentTime = 0;
    video.play();
}

function closeVideo(){
    const modal = document.getElementById("videoModal");
    const video = document.getElementById("vaultVideo");

    modal.style.display="none";
    video.pause();
}
</script>
<script>
function initMap() {

    const selectedGym = @json($selectedGym);

    const gyms = [
        { name: "Tallaght", lat: 53.2886, lng: -6.3732 },
        { name: "Dundrum", lat: 53.2923, lng: -6.2456 },
        { name: "Stillorgan", lat: 53.2895, lng: -6.1988 },
        { name: "Sandyford", lat: 53.2745, lng: -6.2165 },
        { name: "Foxrock", lat: 53.2667, lng: -6.1742 },
        { name: "Crumlin", lat: 53.3194, lng: -6.3145 },
        { name: "Blanchardstown", lat: 53.3881, lng: -6.3756 },
        { name: "Cork City", lat: 51.8985, lng: -8.4756 },
        { name: "Galway", lat: 53.2707, lng: -9.0568 },
        { name: "Swords", lat: 53.4597, lng: -6.2181 }
    ];

    let map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 53.3498, lng: -6.2603 }
    });

    let markers = [];

    function renderMarkers(activeGymName = null) {

        markers.forEach(m => m.setMap(null));
        markers = [];

        gyms.forEach(gym => {

            const isSelected = gym.name === activeGymName;

            const marker = new google.maps.Marker({
                position: { lat: gym.lat, lng: gym.lng },
                map: map,
                title: gym.name,
                icon: {
                    url: isSelected
                        ? "http://maps.google.com/mapfiles/ms/icons/purple-dot.png"
                        : "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });

            const info = new google.maps.InfoWindow({
                content: `
                    <div style="padding:10px;">
                        <h3>${gym.name}</h3>
                        <button onclick="window.location.href='/classes?gym=${gym.name}'"
                            style="background:#6d28d9;color:white;padding:6px 10px;border:none;border-radius:6px;">
                            View Classes
                        </button>
                    </div>
                `
            });

            marker.addListener("click", () => {
                info.open(map, marker);
            });

            markers.push(marker);
        });
    }

    // 🔥 STEP 1: If user has saved gym → use that
    let centerGym = gyms.find(g => g.name === selectedGym);

    if (centerGym) {
        map.setCenter(centerGym);
        map.setZoom(11);
        renderMarkers(centerGym.name);
    } 
    else {
        renderMarkers();

        // 🚀 STEP 2: Use GPS
        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(position => {

                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                let nearest = null;
                let minDistance = Infinity;

                gyms.forEach(gym => {

                    const dist = Math.sqrt(
                        Math.pow(gym.lat - userLat, 2) +
                        Math.pow(gym.lng - userLng, 2)
                    );

                    if (dist < minDistance) {
                        minDistance = dist;
                        nearest = gym;
                    }

                });

                if (nearest) {
                    map.setCenter(nearest);
                    map.setZoom(12);
                    renderMarkers(nearest.name);
                }

            });
        }
    }

}
</script>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALHNH5TxEEr2iei6vuBS3yDEAmdgZgfdA&callback=initMap">
</script>
</body>
</html>