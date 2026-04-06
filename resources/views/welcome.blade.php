<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}– Premium Student Housing</title>
    <x-icon/>

    <!-- Font -->
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

        /* Layout helpers */
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

        /* Header */
        .site-header {
            background: #020617;
            border-bottom: 1px solid #111827;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 20;
            overflow:scroll;
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
                linear-gradient(rgba(15,23,42,0.95), rgba(15,23,42,0.9)),
                url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
        }

        .hero__inner {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
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
            margin-bottom: 1.75rem;
        }

        .hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }

        .hero__metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 0.85rem;
        }

        .metric {
            min-width: 8rem;
        }

        .metric__value {
            font-weight: 600;
            color: #facc15;
        }

        .metric__label {
            color: var(--text-muted);
        }

        .hero__side {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .hero-card {
            background: rgba(15,23,42,0.95);
            border-radius: 0.9rem;
            border: 1px solid var(--border-subtle);
            padding: 1.15rem 1.2rem;
        }

        .hero-card__title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .hero-card__label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.45rem;
        }

        .hero-card__body {
            font-size: 0.85rem;
            color: #cbd5f5;
        }

        /* Features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            border: 1px solid var(--border-subtle);
            padding: 1.1rem 1.15rem;
            font-size: 0.9rem;
        }

        .feature-card__title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .feature-card__title--pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: #111827;
            border: 1px solid #1f2937;
            margin-bottom: 0.6rem;
        }

        .feature-card p {
            color: var(--text-muted);
        }

        /* Properties */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.5rem;
        }

        .property-card {
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid var(--border-subtle);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .property-card__image-wrap {
            padding: 1.25rem 1.25rem 0.25rem;
            text-align: center;
        }

        .property-card__image {
            width: 100%;
            max-width: 260px;
            height: 260px;
            border-radius: 200px;
            object-fit: cover;
            margin: 0 auto;
        }

        .property-card__price {
            margin: 0 1.25rem;
            margin-bottom: 0.7rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: #1e293b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .property-card__body {
            padding: 0 1.25rem 1.25rem;
            font-size: 0.9rem;
        }

        .property-card__title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .property-card__location {
            font-size: 0.83rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .property-card__meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            margin-bottom: 0.9rem;
        }

        .property-card__meta span {
            color: var(--text-muted);
        }

        .property-card__tag {
            font-size: 0.75rem;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            background: #1e293b;
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.75rem;
            text-align: center;
        }

        .stat__value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .stat__label {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Testimonials */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .testimonial-card {
            background: var(--card-bg);
            border-radius: 0.9rem;
            border: 1px solid var(--border-subtle);
            padding: 1.2rem 1.3rem;
            font-size: 0.9rem;
        }

        .testimonial-card__header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
        }

        .testimonial-card__avatar {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .testimonial-card__name {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .testimonial-card__school {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .testimonial-card__body {
            font-size: 0.88rem;
            color: #e5e7eb;
        }

        /* CTA */
        .cta {
            text-align: center;
            max-width: 560px;
            margin: 0 auto;
        }

        .cta__actions {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .cta__secondary-link {
            font-size: 0.9rem;
            color: #d1d5db;
            text-decoration: underline;
        }

        /* Footer */
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

        /* Responsive */
        @media (max-width: 960px) {
            .hero__inner {
                grid-template-columns: minmax(0,1fr);
            }
            .hero__side {
                max-width: 420px;
            }
            .features-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .properties-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .testimonials-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .site-header__inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .hero {
                padding-top: 3.5rem;
            }
            .hero__title {
                font-size: 2rem;
            }
            .hero__metrics {
                gap: 1rem;
            }
            .features-grid,
            .properties-grid,
            .stats-grid,
            .testimonials-grid {
                grid-template-columns: minmax(0,1fr);
            }
            .footer__bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <header class="site-header">
        <div class="container">
            <div class="site-header__inner" style="overflow:scroll;">
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

    {{-- HERO --}}
    <section class="hero">
        <div class="container">
            <div class="hero__inner">
                <div>
                    <div class="hero__badge">
                        <span>ELITE ACCOMMODATIONS</span>
                        <span>•</span>
                        <span>Built for University Life</span>
                    </div>

                    <h1 class="hero__title">
                        Find your <span>perfect</span> student home.
                    </h1>

                    <p class="hero__text">
                        Discover carefully curated student accommodations near your university.
                        UniCrib gives you transparent options, real details, and a smoother way
                        to secure your next home.
                    </p>

                    <div class="hero__actions">
                        <a href="{{ url('/register') }}" class="btn btn--primary">
                            Get started – it’s free
                        </a>
                        <a href="#" class="btn btn--ghost">
                            Watch platform overview
                        </a>
                    </div>

                    <div class="hero__metrics">
                        <div class="metric">
                            <div class="metric__value">4.9 / 5</div>
                            <div class="metric__label">Average student rating</div>
                        </div>
                        <div class="metric">
                            <div class="metric__value">{{ $totalProperties }}+</div>
                            <div class="metric__label">Verified properties</div>
                        </div>
                        <div class="metric">
                            <div class="metric__value">24/7</div>
                            <div class="metric__label">Student support</div>
                        </div>
                    </div>
                </div>

                <aside class="hero__side">
                    <div class="hero-card">
                        <div class="hero-card__title">Campus proximity</div>
                        <div class="hero-card__label">Walking distance</div>
                        <p class="hero-card__body">
                            Short walks, safe routes. Many listings are within a 10–15 minute walk to campus gates.
                        </p>
                    </div>

                    <div class="hero-card">
                        <div class="hero-card__title">Verified &amp; secure</div>
                        <div class="hero-card__label">Trust &amp; safety</div>
                        <p class="hero-card__body">
                            Every property is reviewed and verified, so you can focus on your studies, not scams.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="section section--dark">
        <div class="container">
            <div class="section__heading">
                <div class="section__eyebrow">Why UniCrib</div>
                <h2 class="section__title">
                    A simpler way to secure student housing
                </h2>
                <p class="section__subtitle">
                    UniCrib removes the noise from the search process. You get relevant options,
                    clear information, and support from enquiry to move‑in.
                </p>
            </div>

            <div class="features-grid">
                <article class="feature-card">
                    <div class="feature-card__title--pill">Curated selection</div>
                    <p>
                        Every listing is reviewed for quality, location, and suitability for student life.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-card__title--pill">Time‑saving</div>
                    <p>
                        Filter by budget, distance, and preferences. Shortlist in minutes instead of weeks.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-card__title--pill">Student community</div>
                    <p>
                        Discover shared housing, connect with roommates, and build your campus network.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-card__title--pill">24/7 support</div>
                    <p>
                        Our team is available around the clock to help with viewings, questions, and issues.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- FEATURED PROPERTIES --}}
    <section class="section section--deep">
    
        <div class="container">

            <div class="section__heading">
                <div class="section__eyebrow">Featured properties</div>
                <h2 class="section__title">
                    A closer look at our accommodations
                </h2>
                <p class="section__subtitle">
                    A taste of the verified student homes available near major universities.
                </p>
            </div>
            
            <div class="properties-grid">
            @foreach($properties as $property)
                {{-- Property 1 --}}
                <article class="property-card">
                    <div class="property-card__image-wrap">
                        @if($property->images && count($property->images) > 0)
                            <img class="property-card__image"
                                    src="{{ asset('storage/' . $property->images[0]) }}"
                                    alt="{{ $property->title }}">
                        @else
                            <div class="property-card__image">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                        @endif
                        
                            
                            
                            <div class="property-card__price">Ksh{{ $property->price }}</div>
                            <div class="property-card__body">
                            <h3 class="property-card__title" >{{ $property->building_name }}</h3>                        
                            <p class="property-card__location">walking distance</p>

                            <div class="property-card__meta">
                                <span>&bull; {{ $property->property_type }}</span>
                                <span class="property-card__tag">{{ $property->title }}</span>
                            </div>                     
                    
                        <a  href="{{ route('properties.show', $property) }}" class="btn btn--primary" style="width: 100%; justify-content: center;">
                            View details
                        </a>
                    </div>
                </article>
            @endforeach
            </div>
           
        </div>
        
    </section>

    {{-- STATS --}}
    <section class="section section--dark">
        <div class="container">
            <div class="section__heading">
                <h2 class="section__title">
                    Trusted by students and landlords
                </h2>
                <p class="section__subtitle">
                    UniCrib is growing with every intake. The numbers below represent real matches
                    between students and verified homes.
                </p>
            </div>

            <div class="stats-grid">
                <div>
                    <div class="stat__value">{{ number_format($totalStudents) }}+</div>
                    <div class="stat__label">Students served</div>
                </div>
                <div>
                    <div class="stat__value">{{ number_format($totalProperties) }}+</div>
                    <div class="stat__label">Active properties</div>
                </div>
                <div>
                    <div class="stat__value">1+</div>
                    <div class="stat__label">Universities covered</div>
                </div>
                <div>
                    <div class="stat__value">98.9%</div>
                    <div class="stat__label">Satisfaction rating</div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="section section--deep">
        <div class="container">
            <div class="section__heading">
                <div class="section__eyebrow">Student feedback</div>
                <h2 class="section__title">
                    Students share their UniCrib experience
                </h2>
                <p class="section__subtitle">
                    Real stories from students who secured housing quickly and confidently using UniCrib.
                </p>
            </div>

            <div class="testimonials-grid">
                <article class="testimonial-card">
                    <div class="testimonial-card__header">
                        <div class="testimonial-card__avatar">BO</div>
                        <div>
                            <div class="testimonial-card__name">Benson Okwemba</div>
                            <div class="testimonial-card__school">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="testimonial-card__body">
                        “UniCrib saved me weeks of searching. I found a great settlement within walking
                        distance of campus in just one day at an affordable price.”
                    </p>
                </article>

                <article class="testimonial-card">
                    <div class="testimonial-card__header">
                        <div class="testimonial-card__avatar">DO</div>
                        <div>
                            <div class="testimonial-card__name">Daivine Oluoch</div>
                            <div class="testimonial-card__school">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="testimonial-card__body">
                        “As a student, verified listings and support made the move feel safe.
                        I could focus on classes instead of worrying about housing.”
                    </p>
                </article>

                <article class="testimonial-card">
                    <div class="testimonial-card__header">
                        <div class="testimonial-card__avatar">RM</div>
                        <div>
                            <div class="testimonial-card__name">Raymond Mwenda</div>
                            <div class="testimonial-card__school">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="testimonial-card__body">
                        “Money refund on booking rejection is a very good feature which has boost my trust towards unicrib on my money.”
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="section section--deep">
        <div class="container">
            <div class="cta">
                <h2 class="section__title">
                    Ready to secure your next home?
                </h2>
                <p class="section__subtitle">
                    Create your UniCrib account, explore properties, and move into a place that fits
                    your studies and your budget.
                </p>

                <div class="cta__actions">
                    <a href="{{ url('/register') }}" class="btn btn--primary">
                        Get started now
                    </a>
                    <a href="{{ url('/dashboard') }}" class="cta__secondary-link">
                        View properties on the dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="container">
            <div class="footer__top">
                <div class="footer__brand">UniCrib</div>
                <p class="footer__description">
                    Premium student accommodations with clear information and responsive support.
                    UniCrib is committed to improving the housing experience for students and landlords.
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
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>

                <div class="footer__column">
                    <h3 class="footer__column-title">Contact</h3>
                    <ul>
                        <li>Kutus – Kirinyaga</li>
                        <li>
                            <a href="tel:+254704969006" style="text-decoration:underline;color:#bfdbfe;">
                                +254 704 969 006
                            </a>
                        </li>
                        <li>
                            <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" style="text-decoration:underline;color:#bfdbfe;">
                                unicrib@gmail.com
                            </a>
                        </li>
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
