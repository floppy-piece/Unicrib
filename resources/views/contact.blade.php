{{-- resources/views/contact.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Contact – UniCrib</title>
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

        /* Header (same style as welcome) */
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

        .badge {
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

        /* Layout */
        .contact-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.9fr);
            gap: 2.5rem;
            align-items: flex-start;
        }
        @media (max-width: 960px) {
            .contact-layout {
                grid-template-columns: minmax(0,1fr);
            }
        }

        .card {
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid var(--border-subtle);
            padding: 1.5rem 1.5rem 1.6rem;
            box-shadow: 0 18px 45px rgba(15,23,42,0.85);
        }
        .card__title {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }
        .card__text {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
        }

        /* Form */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 1rem;
        }
        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: minmax(0,1fr);
            }
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        .field label {
            font-size: 0.85rem;
            color: #e5e7eb;
        }
        .field span.helper {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .input,
        .textarea,
        .select {
            width: 100%;
            border-radius: 0.65rem;
            border: 1px solid #111827;
            background: #020617;
            color: var(--text-main);
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
        }
        .input:focus,
        .textarea:focus,
        .select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 1px rgba(99,102,241,0.7);
            background: #020617;
        }
        .textarea {
            min-height: 130px;
            resize: vertical;
        }

        .error-text {
            font-size: 0.78rem;
            color: #fecaca;
            margin-top: 0.15rem;
        }
        .error-border {
            border-color: #b91c1c !important;
        }

        .flash-success {
            margin-bottom: 0.9rem;
            padding: 0.6rem 0.75rem;
            border-radius: 0.75rem;
            background: #022c22;
            border: 1px solid #16a34a;
            font-size: 0.85rem;
            color: #bbf7d0;
        }

        /* Contact info card */
        .info-card {
            background: radial-gradient(circle at top, rgba(37,99,235,0.32), #020617);
            border-radius: 1rem;
            border: 1px solid #1d4ed8;
            padding: 1.4rem 1.4rem 1.5rem;
            font-size: 0.9rem;
        }
        .info-card__title {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .info-card__text {
            font-size: 0.88rem;
            color: #c7d2fe;
            margin-bottom: 0.8rem;
        }
        .info-list {
            list-style: none;
            font-size: 0.88rem;
        }
        .info-list li + li {
            margin-top: 0.35rem;
        }
        .info-tag {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #93c5fd;
            margin-bottom: 0.4rem;
        }

        .inline-links {
            margin-top: 0.7rem;
            font-size: 0.86rem;
        }
        .inline-links a {
            color: #bfdbfe;
            text-decoration: underline;
        }
        .inline-links a:hover {
            color: #e5e7eb;
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

        @media (max-width: 640px) {
            .site-header__inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .footer__bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
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

<section class="section section--dark">
    <div class="container">
        <div class="section__heading" style="margin-bottom:2rem;">
            <div class="section__eyebrow">Contact UniCrib</div>
            <h1 class="section__title">
                Talk to the team behind UniCrib.
            </h1>
            <p class="section__subtitle">
                Use the form below for detailed enquiries, or reach us directly via email or phone for urgent issues.
            </p>
        </div>

        <div class="contact-layout">
            {{-- Contact form --}}
            <div>
                <div class="card">
                    <div class="badge">
                        <span>Secure contact</span>
                        <span>•</span>
                        <span>Protected with CSRF & validation</span>
                    </div>

                    @if(session('success'))
                        <div class="flash-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h2 class="card__title">Send a message</h2>
                    <p class="card__text">
                        Share a bit about who you are and what you need. We’ll route your message to the right
                        person on the UniCrib team and respond as quickly as possible.
                    </p>

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf {{-- Laravel CSRF protection [web:180][web:314] --}}

                        <div class="form-grid">
                            <div class="field">
                                <label for="name">Full name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    class="input @error('name') error-border @enderror"
                                    required
                                    autocomplete="name"
                                >
                                @error('name')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="email">Email address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="input @error('email') error-border @enderror"
                                    required
                                    autocomplete="email"
                                >
                                @error('email')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid" style="margin-top:1rem;">
                            <div class="field">
                                <label for="role">Who are you?</label>
                                <select
                                    id="role"
                                    name="role"
                                    class="select @error('role') error-border @enderror"
                                >
                                    <option value="">Select an option</option>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="landlord" {{ old('role') === 'landlord' ? 'selected' : '' }}>Landlord / Property manager</option>
                                    <option value="university" {{ old('role') === 'university' ? 'selected' : '' }}>University / Institution</option>
                                    <option value="other" {{ old('role') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('role')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="subject">Subject</label>
                                <input
                                    id="subject"
                                    name="subject"
                                    type="text"
                                    value="{{ old('subject') }}"
                                    class="input @error('subject') error-border @enderror"
                                    required
                                >
                                @error('subject')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="field" style="margin-top:1rem;">
                            <label for="message">How can we help?</label>
                            <textarea
                                id="message"
                                name="message"
                                class="textarea @error('message') error-border @enderror"
                                required
                            >{{ old('message') }}</textarea>
                            <span class="helper">
                                Please avoid sharing passwords or sensitive financial details. Describe your campus, timelines, and what you need.
                            </span>
                            @error('message')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="margin-top:1.3rem;display:flex;justify-content:space-between;align-items:center;gap:.75rem;flex-wrap:wrap;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">
                                By submitting, you agree that UniCrib may contact you about this enquiry.
                            </span>
                            <button type="submit" class="btn btn--primary">
                                Send message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Direct contact options --}}
            <aside>
                <div class="info-card">
                    <div class="info-tag">Direct contact</div>
                    <h3 class="info-card__title">Email or call UniCrib</h3>
                    <p class="info-card__text">
                        For urgent issues like account access, billing, or live bookings, reach us directly using the links below.
                        On mobile, the call link will open your phone app automatically. [web:315][web:320]
                    </p>
                    <ul class="info-list">
                        <li>
                            <strong>Email:</strong>
                            <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" style="text-decoration:underline;color:#bfdbfe;">
                                unicrib@gmail.com
                            </a>
                        </li>
                        <li>
                            <strong>Phone / WhatsApp:</strong>
                            <a href="tel:+254704969006" style="text-decoration:underline;color:#bfdbfe;">
                                +254 704 969 006
                            </a>
                        </li>
                        <li><strong>Location:</strong> Kutus – Kirinyaga, Kenya</li>
                    </ul>

                    <div class="inline-links">
                        <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Support">
                            Email UniCrib support
                        </a>
                        &nbsp;•&nbsp;
                        <a href="tel:+254704969006">
                            Tap to call from your phone
                        </a>
                    </div>
                </div>

                <div style="margin-top:1.2rem;" class="info-card">
                    <div class="info-tag">Typical reasons to contact</div>
                    <h3 class="info-card__title">How UniCrib can help</h3>
                    <ul class="info-list">
                        <li>Students: help with housing search, bookings, or a specific property.</li>
                        <li>Landlords: onboarding new properties or improving occupancy.</li>
                        <li>Universities: pilots, integrations, or campus‑wide housing solutions.</li>
                        <li>Partners & investors: product demos and collaboration discussions.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">UniCrib</div>
            <p class="footer__description">
                Premium student accommodations and services with a human team behind every message you send.
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
