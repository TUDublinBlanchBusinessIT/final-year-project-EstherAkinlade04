<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Vault | Fitness Centre</title>

    <style>
        :root {
            --lilac-main: #9b7edc;
            --lilac-dark: #7c5cc4;
            --lilac-light: #f5f1fb;
            --text-dark: #1c1c1c;
            --text-muted: #555;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
            background-color: var(--lilac-light);
            color: var(--text-dark);
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
            padding: 22px 40px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            position: relative;
        }

        /* Logo */
        .logo {
            font-size: 26px;
            font-weight: 700;
            color: white;
        }

        /* Centre navigation */
        .top-nav {
            display: flex;
            justify-content: center;
            gap: 18px;
        }

        .top-nav a {
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            transition: all 0.25s ease;
        }

        .top-nav a:hover {
            background-color: rgba(255,255,255,0.18);
            transform: translateY(-1px);
        }

        .top-nav a.join {
            background-color: white;
            color: var(--lilac-dark);
        }

        .top-nav a.join:hover {
            background-color: #eee9fb;
        }

        /* Animated hamburger */
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
            width: 100%;
            background-color: white;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        /* Hamburger active (turns into X) */
        .hamburger.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Dropdown menu */
        .menu {
            display: none;
            position: absolute;
            top: 80px;
            right: 40px;
            background-color: white;
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: slideDown 0.25s ease;
            z-index: 100;
        }

        .menu a {
            display: block;
            padding: 16px 40px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            text-align: center;
        }

        .menu a:hover {
            background-color: var(--lilac-light);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Hero */
        .hero {
            padding: 120px 20px;
            text-align: center;
            background-color: var(--lilac-light);
        }

        .hero h2 {
            font-size: 46px;
            margin-bottom: 20px;
            color: var(--lilac-dark);
        }

        .hero p {
            font-size: 18px;
            max-width: 760px;
            margin: 0 auto 40px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .hero a {
            display: inline-block;
            padding: 16px 48px;
            background: linear-gradient(135deg, var(--lilac-main), var(--lilac-dark));
            color: white;
            text-decoration: none;
            font-weight: 700;
            border-radius: 40px;
            box-shadow: 0 14px 30px rgba(155,126,220,0.45);
            transition: all 0.25s ease;
        }

        .hero a:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(155,126,220,0.6);
        }

        /* Info */
        .info {
            padding: 90px 20px;
            text-align: center;
            background-color: white;
        }

        .info h3 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--lilac-dark);
        }

        .info p {
            max-width: 780px;
            margin: 0 auto;
            font-size: 16px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* Footer */
        footer {
            background-color: #111;
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

    <!-- Animated hamburger -->
    <div class="hamburger" onclick="toggleMenu(this)">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="menu" id="menu">
        <a href="/">Home</a>
        <a href="/login">Login</a>
        <a href="/register">Join</a>
    </div>
</header>

<section class="hero">
    <h2>Fitness. Simplified.</h2>

    <p>
        The Vault is a fitness centre management system designed to give members
        secure access to their accounts and services — all in one place.
    </p>

    <a href="/register">Join The Vault</a>
</section>

<section class="info">
    <h3>Built for modern fitness centres</h3>

    <p>
        The Vault is being developed step by step, starting with secure member
        accounts. More features will be added over time to support memberships,
        classes, and bookings.
    </p>
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
