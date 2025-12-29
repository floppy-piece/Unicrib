{{-- resources/views/services.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Services – {{ config('app.name') }}</title>
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

        .hero {
            padding: 4.5rem 0 4rem;
            background-image:
                linear-gradient(rgba(15,23,42,0.93), rgba(15,23,42,0.97)),
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
        .hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .hero__note {
            margin-top: 0.9rem;
            font-size: 0.85rem;
            color: #9ca3af;
        }

        .hero__side {
            background: rgba(15,23,42,0.95);
            border-radius: 0.9rem;
            border: 1px solid var(--border-subtle);
            padding: 1rem 1.1rem;
            font-size: 0.9rem;
        }
        .hero__side-title {
            font-weight: 600;
            margin-bottom: 0.35rem;
        }
        .hero__side-list {
            font-size: 0.85rem;
            color: #cbd5f5;
            padding-left: 1rem;
        }
        .hero__side-list li + li {
            margin-top: 0.25rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 1.5rem;
        }
        .service-card {
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid var(--border-subtle);
            padding: 1.2rem 1.25rem 1.3rem;
            box-shadow: 0 18px 45px rgba(15,23,42,0.8);
            font-size: 0.9rem;
        }
        .service-card__icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #111827;
            border: 1px solid #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.6rem;
        }
        .service-card__title {
            font-size: 0.98rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }
        .service-card__text {
            font-size: 0.88rem;
            color: var(--text-muted);
        }
        .service-card__list {
            margin-top: 0.6rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            padding-left: 1rem;
        }
        .service-card__list li + li {
            margin-top: 0.25rem;
        }

        .split {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
            gap: 2rem;
        }
        .callout {
            background: radial-gradient(circle at top, rgba(37,99,235,0.3), #020617);
            border-radius: 0.9rem;
            border: 1px solid #1d4ed8;
            padding: 1.15rem 1.2rem;
            font-size: 0.9rem;
        }
        .callout__title {
            font-weight: 600;
            margin-bottom: 0.35rem;
        }
        .callout__text {
            font-size: 0.9rem;
            color: #c7d2fe;
        }

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
            .services-grid {
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
            .services-grid {
                grid-template-columns: minmax(0,1fr);
            }
        }
    </style>
</head>
<body>
<header class="site-header"  style="overflow:scroll;">
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
                    <span>UNICRIB SERVICES</span>
                    <span>•</span>
                    <span>End‑to‑end student housing</span>
                </div>
                <h1 class="hero__title">
                    A full stack of <span>student housing</span> services.
                </h1>
                <p class="hero__text">
                    UniCrib is more than listings. It combines a curated housing marketplace, landlord tools,
                    move & storage support, and campus partnerships into a single platform built to scale into
                    a billion‑dollar category leader.
                </p>
                <div class="hero__actions">
                    <a href="{{ url('/register') }}" class="btn btn--primary">
                        Get started as a student
                    </a>
                    <a href="{{ url('/login') }}" class="btn btn--ghost">
                        List properties as a landlord
                    </a>
                </div>
                <p class="hero__note">
                    One account unlocks discovery, messaging, move support, and more.
                </p>
            </div>

            <aside class="hero__side">
                <div class="hero__side-title">What UniCrib handles for you</div>
                <ul class="hero__side-list">
                    <li>Discovery: campus‑centric search for verified homes</li>
                    <li>Operations: move‑in, move‑out, and storage rails</li>
                    <li>Trust: verification, ratings, and structured payments</li>
                    <li>Insights: data to help universities and landlords plan</li>
                </ul>
            </aside>
        </div>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="section__heading">
            <div class="section__eyebrow">Core services</div>
            <h2 class="section__title">Built for the full housing journey</h2>
            <p class="section__subtitle">
                From the moment a student starts searching to the day they move out, UniCrib is the operating
                system behind every step.
            </p>
        </div>

        <div class="services-grid">
            <article class="service-card">
                <div class="service-card__icon">🏠</div>
                <h3 class="service-card__title">Student housing marketplace</h3>
                <p class="service-card__text">
                    A curated catalogue of student‑ready homes with real photos, amenities, and distance‑to‑campus
                    details instead of vague descriptions.
                </p>
                <ul class="service-card__list">
                    <li>Verified listings and landlord profiles</li>
                    <li>Filters for budget, distance, and property type</li>
                    <li>Shortlists to compare options with parents/friends</li>
                </ul>
            </article>

            <article class="service-card">
                <div class="service-card__icon">📊</div>
                <h3 class="service-card__title">Landlord tools & dashboards</h3>
                <p class="service-card__text">
                    A modern control centre for landlords to manage units, availability, and communication with
                    student tenants from any device.
                </p>
                <ul class="service-card__list">
                    <li>Create and edit listings in minutes</li>
                    <li>Track availability, verification, and status</li>
                    <li>View enquiries and respond inside UniCrib</li>
                </ul>
            </article>

            <article class="service-card">
                <div class="service-card__icon">🚚</div>
                <h3 class="service-card__title">Move‑in & move‑out logistics</h3>
                <p class="service-card__text">
                    Designed for student realities: small loads, tight timelines, and the chaos of new‑semester weekends.
                </p>
                <ul class="service-card__list">
                    <li>Scheduled and on‑demand moves</li>
                    <li>Coordinated pick‑ups from campus or previous housing</li>
                    <li>Clear pricing before move day</li>
                </ul>
            </article>

            <article class="service-card">
                <div class="service-card__icon">📦</div>
                <h3 class="service-card__title">Short‑term storage for breaks</h3>
                <p class="service-card__text">
                    Students can store belongings securely during holidays, internships, or campus transfers instead
                    of re‑starting from scratch.
                </p>
                <ul class="service-card__list">
                    <li>Storage plans matched to academic calendars</li>
                    <li>Pickup, storage, and drop‑off options</li>
                    <li>Digital inventory and reminders</li>
                </ul>
            </article>

            <article class="service-card">
                <div class="service-card__icon">🤝</div>
                <h3 class="service-card__title">Campus & enterprise solutions</h3>
                <p class="service-card__text">
                    University‑level views of off‑campus housing, safety standards, and student flows without building
                    and maintaining custom software.
                </p>
                <ul class="service-card__list">
                    <li>University‑branded UniCrib experiences</li>
                    <li>Curated pools of approved properties</li>
                    <li>Reports on demand trends and pricing</li>
                </ul>
            </article>

            <article class="service-card">
                <div class="service-card__icon">💳</div>
                <h3 class="service-card__title">Trust, payments & records</h3>
                <p class="service-card__text">
                    A safer way to handle rent, deposits, and documentation so that students, parents, and landlords
                    have a single source of truth.
                </p>
                <ul class="service-card__list">
                    <li>Digitised rent and deposit flows</li>
                    <li>Receipts and timelines for every transaction</li>
                    <li>Foundation for future credit and loyalty products</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="section section--deep">
    <div class="container">
        <div class="split">
            <div>
                <div class="section__eyebrow" style="text-align:left;">Scale & impact</div>
                <h2 class="section__title" style="text-align:left;font-size:1.5rem;">
                    Designed for thousands today, millions tomorrow.
                </h2>
                <p class="section__subtitle" style="text-align:left;">
                    UniCrib’s services are architected to support a single campus launch and then scale to handle
                    entire countries of students and landlords without losing trust or performance.
                </p>
            </div>
            <div class="callout">
                <div class="callout__title">Partner with UniCrib</div>
                <p class="callout__text">
                    Whether you manage a single building or a university portfolio, UniCrib is the partner that
                    brings product‑grade discipline to student housing. Talk to the team about pilots, integrations,
                    or city‑wide rollouts.
                </p>
                <a href="{{ url('/contact') }}" class="btn btn--primary" style="margin-top:0.85rem;">
                    Talk to the UniCrib team
                </a>
            </div>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">UniCrib</div>
            <p class="footer__description">
                Premium student accommodations and services, operated with the focus and ambition of a billion‑dollar
                infrastructure company.
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
