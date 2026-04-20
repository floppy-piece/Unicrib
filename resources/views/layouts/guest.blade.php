<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniCrib') }} – Login</title>
    <x-icon/>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --bg-main: #020617;
            --bg-card: #020617;
            --primary: #6366f1;
            --primary-soft: #4f46e5;
            --accent: #ec4899;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --border-subtle: #1f2937;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top, #1f2937 0, #020617 55%),
                linear-gradient(rgba(35,0,70,0.75), rgba(3,56,89,0.85));
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .auth-shell {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background:
                linear-gradient(rgba(15,23,42,0.94), rgba(15,23,42,0.98)),
                url('{{ asset('images/application-logo.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-blend-mode: darken;
            border-radius: 1.25rem;
            border: 1px solid rgba(148,163,184,0.25);
            box-shadow: 0 24px 60px rgba(0,0,0,0.55);
            padding: 2.4rem 2.2rem 2.1rem;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .brand-link {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            height: 56px;
            width: 56px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid rgba(148,163,184,0.6);
        }

        .brand-title {
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .auth-intro {
            margin-top: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .auth-intro strong {
            color: var(--accent);
            font-weight: 500;
        }

        .auth-panel {
            margin-top: 1.85rem;
            border-radius: 1rem;
            border: 1px solid rgba(148,163,184,0.25);
            background: rgba(15,23,42,0.85);
            padding: 1.5rem 1.35rem 1.4rem;
        }

        .auth-panel__legend {
            font-size: 0.8rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .auth-panel__title {
            font-size: 1.05rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        .auth-panel__subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
        }

        .auth-slot {
            margin-top: 0.2rem;
        }

        .auth-footer {
            margin-top: 1.35rem;
            text-align: center;
            font-size: 0.83rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
        }

        .auth-footer a:hover {
            color: var(--primary-soft);
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 2rem 1.6rem 1.7rem;
            }

            .brand-title {
                font-size: 1.5rem;
            }
        }
    </style>

    
</head>

<body>
    <div class="auth-shell">
        <div class="auth-card">
            <header class="auth-header">
                <a href="{{ url('/') }}" class="brand-link">
                    <x-application-logo class="brand-logo" />
                    <h1 class="brand-title">UniCrib</h1>
                </a>
                <p class="auth-intro">
                    Sign in to access your <strong>UniCrib dashboard</strong> and manage your student housing.
                </p>
            </header>

            <section class="auth-panel">
                <div class="auth-panel__legend">Welcome back</div>
                <h2 class="auth-panel__title">You are here cause you feel to be here 🙂</h2>
                <p class="auth-panel__subtitle">
                    Never take the long way and there is a short one
                </p>

                <div class="auth-slot">
                    {{ $slot }}
                </div>
            </section>

            <div class="auth-footer">
                <span>Need to create an account?</span>
                <span> </span>
                <a href="{{ route('register') }}">Register instead</a>
            </div>
        </div>
    </div>
</body>
</html>
