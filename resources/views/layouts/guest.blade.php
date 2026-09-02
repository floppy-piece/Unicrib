{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniCrib') }} – Login</title>
    <x-icon/>

    <!-- Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 selection:bg-indigo-600 selection:text-white">

    <div class="w-full max-w-md">
        <!-- Main Card with Optional Subtle Background Overlay for Logo -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 p-8 sm:p-10 relative overflow-hidden">
            
            <!-- Header Section -->
            <header class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3 group">
                    <div class="h-14 w-14 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center overflow-hidden shadow-sm">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-indigo-600 transition">UniCrib</h1>
                </a>
                
            </header>

            <!-- Inner Panel -->
            <section class="bg-slate-50 border border-slate-200/60 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Welcome back</div>
                <h2 class="text-base font-semibold text-slate-900 mb-1">You are here cause you feel to be here 🙂</h2>
                <p class="text-xs text-slate-500 mb-5">
                    Never take the long way and there is a short one
                </p>

                <div class="space-y-4">
                    {{ $slot }}
                </div>
            </section>

            <!-- Footer Link -->
            <div class="mt-6 text-center text-xs text-slate-500">
                <span>Need to create an account?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 ml-1 transition">Register instead</a>
            </div>

        </div>
    </div>

</body>
</html>