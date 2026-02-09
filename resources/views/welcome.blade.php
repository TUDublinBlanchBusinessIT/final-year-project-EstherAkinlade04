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

/* ================= HEADER ================= */
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

/* ================= HAMBURGER ================= */
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
    z-index: 100;
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

/* ================= HERO ================= */
.hero {
    position: relative;
    padding: 160px 20px 130px;
    text-align: center;
    overflow: hidden;
}

/* 🔮 DARKER ANIMATED BACKGROUND */
.hero-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-bg span {
    position: absolute;
    width: 460px;
    height: 460px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        rgba(155,126,220,0.75),
        rgba(124,92,196,0.55),
        transparent 75%
    );
    filter: blur(10px);
    animation: float 20s infinite ease-in-out;
}

.hero-bg span:nth-child(1) {
    top: -160px;
    left: -160px;
    animation-duration: 26s;
}

.hero-bg span:nth-child(2) {
    bottom: -160px;
    right: -140px;
    animation-duration: 30s;
}

.hero-bg span:nth-child(3) {
    top: 35%;
    right: 20%;
    width: 340px;
    height: 340px;
    animation-duration: 22s;
}

@keyframes float {
    0%, 100% { transform: translate(0,0); }
    50% { transform: translate(60px, -60px); }
}

.hero-content {
    position: relative;
    z-index: 1;
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

/* ================= FEATURES ================= */
.features {
    padding: 100px 20px;
    background: white;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
    max-width: 1100px;
    margin: auto;
}

.feature-card {
    padding: 48px 32px;
    border-radius: 28px;
    background: var(--lilac-light);
    text-align: center;
    font-weight: 700;
    font-size: 18px;
    transition: all 0.35s ease;
}

.feature-card:hover {
    transform: translateY(-12px) scale(1.05);
    box-shadow: 0 25px 60px rgba(155,126,220,0.4);
}

/* ================= CLASSES ================= */
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
    animation: flipIn 0.6s ease;
}

.class-card.active {
    display: block;
}

.class-card h3 {
    font-size: 30px;
    color: var(--lilac-dark);
}

.class-card p {
    line-height: 1.7;
    color: var(--text-muted);
    margin: 18px 0 28px;
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

.class-actions a.secondary {
    background: white;
    color: var(--lilac-dark);
    border: 2px solid var(--lilac-dark);
}

@keyframes flipIn {
    from { opacity: 0; transform: rotateY(90deg); }
    to { opacity: 1; transform: rotateY(0); }
}

/* ================= PRICING ================= */
.pricing {
    padding: 120px 20px;
    text-align: center;
}

.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
    max-width: 1000px;
    margin: 50px auto 0;
}

.price-card {
    perspective: 1000px;
}

.price-inner {
    height: 340px;
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.price-card:hover .price-inner {
    transform: rotateY(180deg);
}

.price-front, .price-back {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 28px;
    padding: 35px;
    backface-visibility: hidden;
    background: white;
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.price {
    font-size: 38px;
    font-weight: 800;
}

.price-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
    color: white;
}

/* ================= FOOTER ================= */
footer {
    background: #111;
    color: #ccc;
    text-align: center;
    padding: 26px;
    font-size: 14px;
}
</style>
</head>
<body>

<header>
    <div class="logo"><a href="/">The Vault</a></div>

    <nav class="top-nav">
        <a href="/">Home</a>
        <a href="/login">Login</a>
        <a href="/register" class="join">Join</a>
    </nav>

    <div class="hamburger" onclick="toggleMenu(this)">
        <span></span><span></span><span></span>
    </div>

    <div class="menu" id="menu">
        <a href="/">Home</a>
        <a href="#">About Us</a>
        <a href="/login">Login</a>
        <a href="/register">Join</a>
    </div>
</header>

<section class="hero">
    <div class="hero-bg">
        <span></span><span></span><span></span>
    </div>

    <div class="hero-content">
        <h1>Train Smarter. Feel Stronger.</h1>
        <p>A modern fitness platform built around memberships, classes, and real results.</p>
        <a href="/register">Join The Vault</a>
    </div>
</section>

<section class="features">
    <div class="feature-card">Flexible Memberships</div>
    <div class="feature-card">Book Classes Easily</div>
    <div class="feature-card">Train Your Way</div>
</section>

<section class="classes">
    <h2>Our Classes</h2>

    <div class="class-slider" id="classSlider">
        <div class="class-card active">
            <h3>Pilates</h3>
            <p>Full body conditioning focusing on posture, flexibility, and core strength.</p>
            <div class="class-actions">
                <a href="#">Book Now</a>
                <a href="#" class="secondary">Watch</a>
            </div>
        </div>

        <div class="class-card">
            <h3>HIIT Training</h3>
            <p>High-intensity workouts to burn fat and boost endurance.</p>
            <div class="class-actions">
                <a href="#">Book Now</a>
                <a href="#" class="secondary">Watch</a>
            </div>
        </div>

        <div class="class-card">
            <h3>Yoga</h3>
            <p>Improve balance, flexibility, and mental focus.</p>
            <div class="class-actions">
                <a href="#">Book Now</a>
                <a href="#" class="secondary">Watch</a>
            </div>
        </div>

        <div class="class-card">
            <h3>Strength & Conditioning</h3>
            <p>Build strength and performance through functional training.</p>
            <div class="class-actions">
                <a href="#">Book Now</a>
                <a href="#" class="secondary">Watch</a>
            </div>
        </div>
    </div>
</section>

<section class="pricing">
    <h2>Membership Options</h2>

    <div class="pricing-grid">
        <div class="price-card">
            <div class="price-inner">
                <div class="price-front">
                    <h3>Student</h3>
                    <div class="price">€25 / month</div>
                </div>
                <div class="price-back">
                    <p>Gym access<br>Classes<br>Flexible booking</p>
                </div>
            </div>
        </div>

        <div class="price-card">
            <div class="price-inner">
                <div class="price-front">
                    <h3>Standard</h3>
                    <div class="price">€39 / month</div>
                </div>
                <div class="price-back">
                    <p>All gyms<br>All classes<br>Priority access</p>
                </div>
            </div>
        </div>

        <div class="price-card">
            <div class="price-inner">
                <div class="price-front">
                    <h3>Premium</h3>
                    <div class="price">€49 / month</div>
                </div>
                <div class="price-back">
                    <p>Everything<br>Personal training<br>Exclusive sessions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    © 2026 The Vault · Fitness Centre Management System
</footer>

<script>
function toggleMenu(btn) {
    btn.classList.toggle('active');
    const menu = document.getElementById('menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

const cards = document.querySelectorAll('.class-card');
let current = 0;
let timer;

function showCard(i) {
    cards.forEach(c => c.classList.remove('active'));
    cards[i].classList.add('active');
}

function startSlider() {
    timer = setInterval(() => {
        current = (current + 1) % cards.length;
        showCard(current);
    }, 3000);
}

function stopSlider() {
    clearInterval(timer);
}

showCard(current);
startSlider();

document.getElementById('classSlider').addEventListener('mouseenter', stopSlider);
document.getElementById('classSlider').addEventListener('mouseleave', startSlider);
</script>

</body>
</html>
