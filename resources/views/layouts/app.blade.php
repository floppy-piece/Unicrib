{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    <body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-indigo-600 selection:text-white">
        
        {{-- Navigation (Livewire component) --}}
        <livewire:layout.navigation />

        {{-- Page Heading --}}
        @if (isset($header))
            <header class="bg-white border-b border-slate-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="text-xl font-bold text-slate-900 tracking-tight">
                        {{ $header }}
                    </div>
                    @auth
                        <div class="flex items-center space-x-2 text-sm text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl w-fit">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-medium text-slate-700">Signed in as</span>
                            <span class="font-semibold text-slate-900">{{ Auth()->user()->name }}</span>
                        </div>
                    @endauth
                </div>
            </header>
        @endif

        {{-- Page Content --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'CampusNest') }}. Built for student housing.</p>
                <div class="flex space-x-6">
                    <span class="hover:text-indigo-600 transition cursor-pointer">Support</span>
                    <span class="hover:text-indigo-600 transition cursor-pointer">Privacy</span>
                    <span class="hover:text-indigo-600 transition cursor-pointer">Terms</span>
                </div>
            </div>
        </footer>
    </body>
</html>