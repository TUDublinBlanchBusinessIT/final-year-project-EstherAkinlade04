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
}

.menu {
    display: none;
    position: absolute;
    top: 80px;
    right: 40px;
    background: white;
    border-radius: 14px;
}

.menu a {
    display: block;
    padding: 16px 40px;
    text-decoration: none;
    color: black;
}

/* HERO SLIDER */
.hero-slider {
    position: relative;
    height: 90vh;
    overflow: hidden;
}

.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease;
}

.slide.active {
    opacity: 1;
}

.overlay {
    background: rgba(0,0,0,0.6);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color:white;
    text-align:center;
}

.overlay h1 {
    font-size: 60px;
}

.overlay a {
    margin-top:20px;
    padding:14px 40px;
    background: var(--lilac-main);
    color:white;
    border-radius:30px;
    text-decoration:none;
}

/* STATS */
.stats {
    display: flex;
    justify-content: space-around;
    padding: 80px 20px;
    background: white;
}

.stat h2 {
    font-size: 40px;
    color: var(--lilac-dark);
}

/* CLASSES */
.classes {
    padding: 100px 20px;
    text-align: center;
}

.class-card {
    max-width: 400px;
    margin: 20px auto;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    transition: transform 0.3s;
}

.class-card:hover {
    transform: scale(1.05);
}

.class-card img {
    width:100%;
    height:200px;
    object-fit:cover;
}

.class-content {
    padding:20px;
}

/* SCROLL ANIMATION */
.hidden {
    opacity:0;
    transform:translateY(40px);
    transition: all 0.6s;
}

.show {
    opacity:1;
    transform:translateY(0);
}

footer {
    background:#111;
    color:#ccc;
    text-align:center;
    padding:20px;
}
</style>
</head>

<body>

<header>
    <div class="logo">
        <a href="#">The Vault</a>
    </div>

    <nav class="top-nav">
        <a href="#">Home</a>
        <a href="#">Login</a>
        <a href="#" class="join">Join</a>
    </nav>

    <div class="hamburger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
    </div>

    <div class="menu" id="menu">
        <a href="#">Home</a>
        <a href="#">Login</a>
        <a href="#">Join</a>
    </div>
</header>

<!-- HERO -->
<section class="hero-slider">
    <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1554284126-aa88f22d8b74')">
        <div class="overlay">
            <h1>Train Hard</h1>
            <a href="#">Join Now</a>
        </div>
    </div>

    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b')">
        <div class="overlay">
            <h1>Build Strength</h1>
            <a href="#">Start Today</a>
        </div>
    </div>

    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1599058917212-d750089bc07e')">
        <div class="overlay">
            <h1>Join The Vault</h1>
            <a href="#">Sign Up</a>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats">
    <div class="stat">
        <h2>500+</h2>
        <p>Members</p>
    </div>
    <div class="stat">
        <h2>40+</h2>
        <p>Classes</p>
    </div>
    <div class="stat">
        <h2>10</h2>
        <p>Trainers</p>
    </div>
</section>

<!-- CLASSES -->
<section class="classes">
    <h2>Our Classes</h2>

    <div class="class-card">
        <img src="https://images.unsplash.com/photo-1518611012118-fc6d3d3b5d4c">
        <div class="class-content">
            <h3>Pilates</h3>
            <p>Core strength & flexibility</p>
        </div>
    </div>

    <div class="class-card">
        <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2">
        <div class="class-content">
            <h3>HIIT</h3>
            <p>High intensity fat burning</p>
        </div>
    </div>

    <div class="class-card">
        <img src="https://images.unsplash.com/photo-1552196563-55cd4e45efb3">
        <div class="class-content">
            <h3>Yoga</h3>
            <p>Balance & mindfulness</p>
        </div>
    </div>
</section>

<footer>
    © 2026 The Vault
</footer>

<script>
// MENU
function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

// HERO SLIDER
const slides = document.querySelectorAll('.slide');
let index = 0;

setInterval(()=>{
    slides[index].classList.remove('active');
    index = (index+1) % slides.length;
    slides[index].classList.add('active');
},4000);

// SCROLL ANIMATION
const observer = new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
        if(entry.isIntersecting){
            entry.target.classList.add('show');
        }
    });
});

document.querySelectorAll('section').forEach(sec=>{
    sec.classList.add('hidden');
    observer.observe(sec);
});
</script>

</body>
</html>