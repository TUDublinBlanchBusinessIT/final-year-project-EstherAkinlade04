<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>The Vault | Fitness Centre</title>

<style>
:root {
    --lilac-main: #9b7edc;
    --lilac-dark: #6f54c6;
    --lilac-light: #f5f1fb;
    --text-dark: #1c1c1c;
    --text-muted: #555;
}

* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: "Segoe UI", Helvetica, Arial, sans-serif;
    background-color: var(--lilac-light);
    color: var(--text-dark);
}

/* HEADER */
header {
    background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
    padding: 22px 40px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    position: relative;
}

.logo a {
    font-size: 26px;
    font-weight: 800;
    color: white;
    text-decoration: none;
}

.top-nav {
    display: flex;
    justify-content: center;
    gap: 18px;
}

.top-nav a {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 700;
    text-decoration: none;
    color: white;
    border: 2px solid rgba(255,255,255,0.4);
    transition: all 0.25s ease;
}

.top-nav a:hover {
    background-color: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.top-nav a.join {
    background-color: white;
    color: var(--lilac-dark);
}

/* HAMBURGER */
.hamburger {
    width: 30px;
    height: 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    cursor: pointer;
}

.hamburger span {
    height: 3px;
    background: white;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.hamburger.active span:nth-child(1) {
    transform: translateY(9px) rotate(45deg);
}
.hamburger.active span:nth-child(2) {
    opacity: 0;
}
.hamburger.active span:nth-child(3) {
    transform: translateY(-9px) rotate(-45deg);
}

.menu {
    display: none;
    position: absolute;
    top: 80px;
    right: 40px;
    background: white;
    border-radius: 14px;
    box-shadow: 0 18px 40px rgba(0,0,0,0.18);
    overflow: hidden;
}

.menu a {
    display: block;
    padding: 16px 40px;
    text-align: center;
    text-decoration: none;
    color: var(--text-dark);
    font-weight: 600;
}

.menu a:hover {
    background-color: var(--lilac-light);
}

/* HERO */
.hero {
    position: relative;
    padding: 160px 20px 130px;
    text-align: center;
}

.hero h1 {
    font-size: 56px;
    color: var(--lilac-dark);
}

.hero p {
    max-width: 720px;
    margin: 20px auto 40px;
    font-size: 18px;
    color: var(--text-muted);
}

.hero a {
    padding: 16px 52px;
    border-radius: 40px;
    font-weight: 800;
    text-decoration: none;
    color: white;
    background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
    box-shadow: 0 18px 45px rgba(124,92,196,0.65);
    transition: all 0.3s ease;
}

.hero a:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 70px rgba(124,92,196,0.9);
}

/* CLASSES */
.classes {
    padding: 120px 20px;
    text-align: center;
}

.class-slider {
    max-width: 820px;
    margin: 50px auto 0;
}

.class-card {
    display: none;
    padding: 58px;
    border-radius: 28px;
    background: white;
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.class-card.active {
    display: block;
}

.class-actions a {
    display: inline-block;
    margin: 0 10px;
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: 700;
    text-decoration: none;
    color: white;
    background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
}

footer {
    background: #111;
    color: #ccc;
    text-align: center;
    padding: 26px;
}
</style>
</head>
<body>

<header>
    <div class="logo">
        <a href="{{ route('home') }}">The Vault</a>
    </div>

    <nav class="top-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}" class="join">Join</a>
    </nav>

    <div class="hamburger" onclick="toggleMenu(this)">
        <span></span><span></span><span></span>
    </div>

    <div class="menu" id="menu">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Join</a>
    </div>
</header>

<section class="hero">
    <h1>Train Smarter. Feel Stronger.</h1>
    <p>A modern fitness platform built around memberships, classes, and real results.</p>
    <a href="{{ route('register') }}">Join The Vault</a>
</section>

<section class="classes">
    <h2>Our Classes</h2>

    <div class="class-slider" id="classSlider">

        <div class="class-card active">
            <h3>Pilates</h3>
            <p>Full body conditioning focusing on posture and core strength.</p>
            <div class="class-actions">
                <a href="{{ route('classes.index') }}">Book Now</a>
            </div>
        </div>

        <div class="class-card">
            <h3>HIIT Training</h3>
            <p>High-intensity workouts to burn fat and boost endurance.</p>
            <div class="class-actions">
                <a href="{{ route('classes.index') }}">Book Now</a>
            </div>
        </div>

        <div class="class-card">
            <h3>Yoga</h3>
            <p>Improve balance, flexibility and mental focus.</p>
            <div class="class-actions">
                <a href="{{ route('classes.index') }}">Book Now</a>
            </div>
        </div>

    </div>
</section>

<footer>
    © 2026 The Vault
</footer>

<script>
function toggleMenu(btn) {
    btn.classList.toggle('active');
    const menu = document.getElementById('menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

const cards = document.querySelectorAll('.class-card');
let current = 0;

setInterval(() => {
    cards[current].classList.remove('active');
    current = (current + 1) % cards.length;
    cards[current].classList.add('active');
}, 3000);
</script>

</body>
</html>
