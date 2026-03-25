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
    justify-content:center; /* CENTER EVERYTHING */
    align-items:center;
    padding:25px 60px;
    z-index:100;
}

/* NAV CENTER */
.nav {
    display:flex;
    align-items:center;
    gap:60px; /* 🔥 spread out */
}

/* LOGO */
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

/* LINKS */
.nav a {
    color:white;
    text-decoration:none;
    font-weight:600;
    letter-spacing:1px;
}

/* BUTTONS */
.btn {
    padding:10px 24px;
    border-radius:30px;
}

.join {
    background:white;
    color:black !important;
}

.login {
    border:1px solid white;
}

/* HERO */
.hero {
    height:100vh;
    display:flex;
}

/* LEFT */
.hero-left {
    width:35%;
    background:linear-gradient(180deg,var(--main),var(--dark));
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:60px;
}

.hero-left h1 {
    font-size:70px;
}

.hero-left p {
    margin-top:20px;
    font-size:18px;
}

.hero-left a {
    margin-top:25px;
    padding:14px 34px;
    background:white;
    color:var(--dark);
    border-radius:40px;
    text-decoration:none;
    font-weight:700;
}

/* RIGHT */
.hero-right {
    width:65%;
    position:relative;
    overflow:hidden;
}

/* SLIDER */
.slider {
    display:flex;
    height:100%;
    transition:0.8s ease;
}

.slide {
    min-width:100%;
    height:100%;
    background-size:cover;
    background-position:center;
}

/* OVERLAY */
.hero-right::after {
    content:'';
    position:absolute;
    width:100%;
    height:100%;
    top:0;
    left:0;
    background:linear-gradient(90deg, rgba(0,0,0,0.6), transparent);
}

/* STEPS */
.steps {
    position:absolute;
    bottom:40px;
    left:50px;
    display:flex;
    gap:40px;
    color:white;
    font-weight:600;
}

.step {
    opacity:0.5;
    cursor:pointer;
}

.step.active {
    opacity:1;
    border-bottom:3px solid var(--main);
}

/* PROGRESS */
.progress {
    position:absolute;
    bottom:0;
    left:0;
    height:4px;
    background:var(--main);
    width:0%;
}
</style>
</head>

<body>

<header class="header">

    <div class="logo">
        <a href="#">The Vault</a>
    </div>

    <nav class="nav">
        <a href="#" class="btn join">Join</a>
        <a href="#" class="btn login">Login</a>
        <a href="#">Gyms</a>
        <a href="#">Classes</a>
        <a href="#">Help</a>
    </nav>

</header>

<section class="hero">

<div class="hero-left">
    <h1 id="heroTitle">JOIN TODAY</h1>
    <p id="heroText">Start your fitness journey now.</p>
    <a href="#">Join Now</a>
</div>

<div class="hero-right" id="heroArea">

    <div class="slider" id="slider">

        <!-- 🔥 ONLY 4 BIG CLEAN SLIDES -->
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1554284126-aa88f22d8b74')"></div>

        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b')"></div>

        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1517838277536-f5f99be501cd')"></div>

        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1540497077202-7c8a3999166f')"></div>

    </div>

    <div class="steps">
        <div class="step active" onclick="goToSlide(0)">01 JOIN</div>
        <div class="step" onclick="goToSlide(1)">02 CLASSES</div>
        <div class="step" onclick="goToSlide(2)">03 STUDENTS</div>
        <div class="step" onclick="goToSlide(3)">04 RESULTS</div>
    </div>

    <div class="progress" id="progress"></div>

</div>

</section>

<script>
const slider = document.getElementById('slider');
const steps = document.querySelectorAll('.step');
const progress = document.getElementById('progress');

const titles = [
    "JOIN TODAY",
    "CLASSES 1000+",
    "STUDENT DISCOUNT",
    "REAL RESULTS"
];

const texts = [
    "Start your fitness journey now.",
    "Over 1000 classes every week.",
    "Exclusive student offers.",
    "Transform your body."
];

let i = 0;
let interval;

function update(){
    slider.style.transform = `translateX(-${i * 100}%)`;

    document.getElementById("heroTitle").innerText = titles[i];
    document.getElementById("heroText").innerText = texts[i];

    steps.forEach(s => s.classList.remove("active"));
    steps[i].classList.add("active");

    resetProgress();
}

function goToSlide(index){
    i = index;
    update();
}

function startAuto(){
    interval = setInterval(()=>{
        i = (i+1)%titles.length;
        update();
    },5000);
}

function resetProgress(){
    progress.style.transition = "none";
    progress.style.width = "0%";

    setTimeout(()=>{
        progress.style.transition = "5s linear";
        progress.style.width = "100%";
    },50);
}

const hero = document.getElementById("heroArea");

hero.addEventListener("mouseenter",()=>clearInterval(interval));
hero.addEventListener("mouseleave",startAuto);

startAuto();
resetProgress();
</script>

</body>
</html>