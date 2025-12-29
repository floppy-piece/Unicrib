{{-- resources/views/about.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>About – {{ config('app.name') }}</title>
    <x-icon/>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --bg-dark: #0b1020;
            --bg-deep: #050814;
            --primary: #6366f1;
            --primary-soft: #4f46e5;
            --accent: #ec4899;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --card-bg: #0f172a;
            --border-subtle: #1f2937;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            color: var(--text-main);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .section {
            padding: 4rem 0;
        }

        .section--dark {
            background: var(--bg-dark);
        }

        .section--deep {
            background: var(--bg-deep);
        }

        .section__heading {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 2.5rem;
        }

        .section__eyebrow {
            font-size: 0.85rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: .75rem;
        }

        .section__title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: .75rem;
        }

        .section__subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* Header (copied style) */
        .site-header {
            background: #020617;
            border-bottom: 1px solid #111827;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .site-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .brand__logo {
            border-radius: 999px;
            height: 44px;
            width: 44px;
            object-fit: cover;
        }
        .brand__name {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 0.9rem;
        }
        .header-nav a {
            color: var(--text-muted);
            transition: color 0.2s ease;
        }
        .header-nav a:hover {
            color: var(--text-main);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.4rem;
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn--primary {
            background: var(--primary);
            border-color: var(--primary-soft);
            color: #fff;
        }
        .btn--primary:hover {
            background: #4f46e5;
        }
        .btn--ghost {
            background: transparent;
            border-color: #374151;
            color: var(--text-main);
        }
        .btn--ghost:hover {
            border-color: var(--primary);
        }

        /* Hero */
        .hero {
            padding: 4.5rem 0 4rem;
            background-image:
                linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.96)),
                url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }
        .hero__inner {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: 2.5rem;
            align-items: center;
        }
        .hero__badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            background: rgba(15,23,42,0.85);
            border: 1px solid rgba(148,163,184,0.35);
            font-size: 0.75rem;
            color: var(--accent);
            margin-bottom: 1.5rem;
        }
        .hero__title {
            font-size: 2.4rem;
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero__title span {
            color: var(--accent);
        }
        .hero__text {
            font-size: 0.98rem;
            color: #e5e7eb;
            max-width: 34rem;
            margin-bottom: 1.5rem;
        }
        .hero__metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 1.4rem;
            font-size: 0.85rem;
        }
        .metric {
            min-width: 9rem;
        }
        .metric__value {
            font-weight: 600;
            color: #facc15;
        }
        .metric__label {
            color: var(--text-muted);
        }
        .hero__side-card {
            background: rgba(15,23,42,0.95);
            border-radius: 0.9rem;
            border: 1px solid var(--border-subtle);
            padding: 1rem 1.1rem;
            font-size: 0.9rem;
        }
        .hero__side-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .hero__side-text {
            font-size: 0.85rem;
            color: #cbd5f5;
        }

        /* 3‑column cards */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
        }
        .card {
            background: var(--card-bg);
            border-radius: 0.9rem;
            border: 1px solid var(--border-subtle);
            padding: 1.15rem 1.2rem;
            font-size: 0.9rem;
        }
        .card__title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }
        .card__text {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        /* Timeline / story */
        .story {
            max-width: 720px;
            margin: 0 auto;
            font-size: 0.95rem;
            color: var(--text-muted);
        }
        .story p + p {
            margin-top: 0.6rem;
        }

        /* Two‑column split */
        .split {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
            gap: 2rem;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            border: 1px solid #16a34a;
            background: rgba(22,163,74,0.12);
            color: #bbf7d0;
            font-size: 0.78rem;
            margin-bottom: 0.75rem;
        }
        .vision-card {
            background: radial-gradient(circle at top, rgba(37,99,235,0.3), #020617);
            border-radius: 0.9rem;
            border: 1px solid #1d4ed8;
            padding: 1.15rem 1.2rem;
            font-size: 0.9rem;
        }
        .vision-card__title {
            font-weight: 600;
            margin-bottom: 0.35rem;
        }
        .vision-card__text {
            font-size: 0.9rem;
            color: #c7d2fe;
        }

        /* Footer (same base style) */
        .site-footer {
            background: #020617;
            border-top: 1px solid #111827;
            padding: 2.5rem 0 2rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .footer__top {
            text-align: center;
            margin-bottom: 2rem;
        }
        .footer__brand {
            font-size: 1.15rem;
            font-weight: 600;
            color: #f9fafb;
        }
        .footer__description {
            max-width: 460px;
            margin: 0.75rem auto 0;
        }
        .footer__columns {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2.5rem;
            margin-bottom: 1.75rem;
        }
        .footer__column-title {
            color: #e5e7eb;
            font-weight: 600;
            margin-bottom: 0.9rem;
        }
        .footer__column ul {
            list-style: none;
        }
        .footer__column li + li {
            margin-top: 0.4rem;
        }
        .footer__bottom {
            border-top: 1px solid #111827;
            padding-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
        }
        .footer__links {
            display: flex;
            gap: 1.25rem;
        }
        .footer__links a {
            color: var(--text-muted);
        }
        .footer__links a:hover {
            color: var(--text-main);
        }

        @media (max-width: 960px) {
            .hero__inner {
                grid-template-columns: minmax(0,1fr);
            }
            .grid-3 {
                grid-template-columns: repeat(2, minmax(0,1fr));
            }
            .split {
                grid-template-columns: minmax(0,1fr);
            }
        }
        @media (max-width: 640px) {
            .site-header__inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .grid-3 {
                grid-template-columns: minmax(0,1fr);
            }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container">
        <div class="site-header__inner">
            <div class="brand">
                <x-application-logo class="brand__logo" />
                <span class="brand__name">UniCrib</span>
            </div>
            <div class="header-nav">
                <x-non-nav/>
                @if (Route::has('login'))
                    <livewire:welcome.navigation/>
                @endif
            </div>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container">
        <div class="hero__inner">
            <div>
                <div class="hero__badge">
                    <span>ABOUT UNICRIB</span>
                    <span>•</span>
                    <span>Built for serious student life</span>
                </div>
                <h1 class="hero__title">
                    The infrastructure behind <span>student housing</span> in Africa.
                </h1>
                <p class="hero__text">
                    UniCrib is a student‑first housing and move platform built to make finding, securing,
                    and living in off‑campus accommodation feel as predictable as registering for classes.
                </p>
                <div class="hero__metrics">
                    <div class="metric">
                        <div class="metric__value">End‑to‑end</div>
                        <div class="metric__label">Discovery, verification & moves</div>
                    </div>
                    <div class="metric">
                        <div class="metric__value">Student‑first</div>
                        <div class="metric__label">Designed around campus life</div>
                    </div>
                    <div class="metric">
                        <div class="metric__value">Built to scale</div>
                        <div class="metric__label">A platform worth billions</div>
                    </div>
                </div>
            </div>

            <div class="hero__side-card">
                <div class="hero__side-title">From one campus to a continent</div>
                <p class="hero__side-text">
                    UniCrib starts where demand is most intense—Kenyan university towns—then scales
                    across African campuses with the same promise: verified homes, safer moves, and
                    serious tools for landlords.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="section__heading">
            <div class="section__eyebrow">Mission & vision</div>
            <h2 class="section__title">Why UniCrib exists</h2>
            <p class="section__subtitle">
                Students shouldn’t gamble with safety, time, or money just to find a place to sleep.
                UniCrib gives housing the same structure and polish as a world‑class digital product.
            </p>
        </div>

        <div class="story">
            <p>
                Across African campuses, student housing is still fragmented: screenshots, WhatsApp groups,
                “ask a friend of a friend”, and last‑minute moves that depend on luck more than process.
            </p>
            <p>
                UniCrib was created to change that by combining a verified housing marketplace with move,
                storage, and landlord tools—all tuned to the realities of campus life and local cities.
            </p>
            <p>
                The long‑term vision is clear: a category‑defining platform worth billions that quietly
                powers student housing, logistics, and payments across the continent.
            </p>
        </div>
    </div>
</section>

<section class="section section--deep">
    <div class="container">
        <div class="section__heading">
            <div class="section__eyebrow">What we solve</div>
            <h2 class="section__title">A better experience for every side</h2>
            <p class="section__subtitle">
                UniCrib sits at the centre of students, landlords, and universities—aligning everyone around
                verified information, clear expectations, and smooth operations.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <h3 class="card__title">For students</h3>
                <p class="card__text">
                    Verified properties, real photos, campus‑centric search, and optional move & storage support
                    so you spend more time learning and less time negotiating.
                </p>
            </article>
            <article class="card">
                <h3 class="card__title">For landlords</h3>
                <p class="card__text">
                    A professional channel for serious student tenants, smart dashboards for listings and
                    availability, and a brand that signals trust and quality.
                </p>
            </article>
            <article class="card">
                <h3 class="card__title">For universities</h3>
                <p class="card__text">
                    A trusted off‑campus partner that raises safety and satisfaction without requiring
                    the university to build, host, and run its own tech stack.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="split">
            <div>
                <span class="pill">Product DNA</span>
                <h2 class="section__title" style="text-align:left;font-size:1.4rem;margin-bottom:0.5rem;">
                    Student‑first, landlord‑serious, investor‑grade.
                </h2>
                <p class="section__subtitle" style="text-align:left;">
                    UniCrib is designed with students in mind, built with landlord discipline, and architected
                    at a level that can support institutional partners and investors.
                </p>
            </div>
            <div class="vision-card">
                <div class="vision-card__title">Where UniCrib is going</div>
                <p class="vision-card__text">
                    Today, UniCrib helps students discover and secure better homes. Tomorrow, the same rails
                    can power credit history, insurance, community, and all the infrastructure around young
                    people building real lives—starting from where they live.
                </p>
            </div>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">UniCrib</div>
            <p class="footer__description">
                Premium student accommodations and services built on trust, clarity, and a long‑term vision for
                African campuses.
            </p>
        </div>

        <div class="footer__columns">
            <div class="footer__column">
                <h3 class="footer__column-title">Platform</h3>
                <ul>
                    <li><a href="{{ url('/dashboard') }}">Properties</a></li>
                    <li><a href="{{ url('/dashboard') }}">Universities</a></li>
                    <li><a href="{{ url('/services') }}">Services</a></li>
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                </ul>
            </div>
            <div class="footer__column">
                <h3 class="footer__column-title">Company</h3>
                <ul>
                    <li><a href="{{ url('/about') }}">About us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Press</a></li>
                </ul>
            </div>
            <div class="footer__column">
                <h3 class="footer__column-title">Contact</h3>
                <ul>
                    <li>Kutus – Kirinyaga</li>
                    <li>+254 704 969 006</li>
                    <li>unicrib@gmail.com</li>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p>© 2026 UniCrib. All rights reserved.</p>
            <div class="footer__links">
                <a href="#">Privacy policy</a>
                <a href="#">Terms of service</a>
                <a href="#">Cookie policy</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
