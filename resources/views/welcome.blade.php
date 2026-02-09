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

        * {
            box-sizing: border-box;
        }

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

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: white;
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
            font-size: 15px;
            text-decoration: none;
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            transition: all 0.25s ease;
        }

        .top-nav a:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }

        .top-nav a.join {
            background-color: white;
            color: var(--lilac-dark);
        }

        /* Hamburger */
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
            padding: 140px 20px 120px;
            text-align: center;
            animation: fadeUp 1s ease;
        }

        .hero h1 {
            font-size: 52px;
            margin-bottom: 20px;
            color: var(--lilac-dark);
        }

        .hero p {
            max-width: 720px;
            margin: 0 auto 40px;
            font-size: 18px;
            line-height: 1.7;
            color: var(--text-muted);
        }

        .hero a {
            display: inline-block;
            padding: 16px 50px;
            border-radius: 40px;
            font-weight: 800;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
            box-shadow: 0 16px 40px rgba(155,126,220,0.45);
            transition: all 0.3s ease;
        }

        .hero a:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 50px rgba(155,126,220,0.65);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            padding: 40px 30px;
            border-radius: 24px;
            background: var(--lilac-light);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.12);
        }

        .feature-card h3 {
            color: var(--lilac-dark);
            margin-bottom: 14px;
        }

        /* ================= CLASSES ================= */
        .classes {
            padding: 110px 20px;
            text-align: center;
        }

        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 50px auto 0;
        }

        .class-card {
            background: white;
            border-radius: 22px;
            padding: 30px;
            font-weight: 700;
            transition: transform 0.3s ease;
        }

        .class-card:hover {
            transform: scale(1.05);
        }

        /* ================= CTA ================= */
        .cta {
            padding: 100px 20px;
            background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
            text-align: center;
            color: white;
        }

        .cta h2 {
            font-size: 38px;
            margin-bottom: 20px;
        }

        .cta a {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 44px;
            border-radius: 30px;
            background: white;
            color: var(--lilac-dark);
            font-weight: 800;
            text-decoration: none;
        }

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
    <div class="logo">The Vault</div>

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
        <a href="/login">Login</a>
        <a href="/register">Join</a>
    </div>
</header>

<section class="hero">
    <h1>Train Smarter. Move Better.</h1>
    <p>
        The Vault is a modern fitness platform designed to give members full control
        over their training experience — from memberships to classes and bookings.
    </p>
    <a href="/register">Join The Vault</a>
</section>

<section class="features">
    <div class="feature-card">
        <h3>Memberships</h3>
        <p>Flexible fitness memberships designed around your lifestyle.</p>
    </div>

    <div class="feature-card">
        <h3>Classes</h3>
        <p>Browse and book fitness classes that match your training goals.</p>
    </div>

    <div class="feature-card">
        <h3>Bookings</h3>
        <p>Manage your sessions and schedule through one simple system.</p>
    </div>
</section>

<section class="classes">
    <h2>Popular Training Styles</h2>

    <div class="class-grid">
        <div class="class-card">HIIT Training</div>
        <div class="class-card">Strength & Conditioning</div>
        <div class="class-card">Yoga & Mobility</div>
        <div class="class-card">Functional Fitness</div>
    </div>
</section>

<section class="cta">
    <h2>Start Your Fitness Journey Today</h2>
    <a href="/register">Join Now</a>
</section>

<footer>
    © 2026 The Vault · Fitness Centre Management System
</footer>

<script>
    function toggleMenu(button) {
        const menu = document.getElementById('menu');
        button.classList.toggle('active');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
</script>

</body>
</html>
