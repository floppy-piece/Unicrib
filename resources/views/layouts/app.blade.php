{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
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

        <script>
            // Configure Tailwind to support class-based dark mode
            tailwind.config = {
                darkMode: 'class',
            }
            
            // Check and apply saved theme on initial load to prevent flashing
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased min-h-screen flex flex-col selection:bg-indigo-600 selection:text-white transition-colors duration-200">
        
        {{-- Navigation (Livewire component) --}}
        <livewire:layout.navigation />

        {{-- Page Heading --}}
        @if (isset($header))
            <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                        {{ $header }}
                    </div>
                    @auth
                        <div class="flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-xl w-fit border border-slate-200/60 dark:border-slate-700/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">Signed in as</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ Auth()->user()->name }}</span>
                        </div>
                    @endauth
                </div>
            </header>
        @endif

        {{-- Page Content --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>
        @stack('scripts')

        {{-- Global Toast Notification Container --}}
        <div id="toast" 
            @if(session('booking_status')) 
                data-session='@json(session('booking_status'))' 
            @elseif(session('success')) 
                data-session='@json(['type' => 'success', 'message' => session('success')])' 
            @elseif(session('error')) 
                data-session='@json(['type' => 'error', 'message' => session('error')])' 
            @endif 
            class="fixed top-4 right-4 bg-slate-900 dark:bg-slate-800 text-white px-4 py-3 rounded-xl border border-slate-700 dark:border-slate-700 opacity-0 transition-opacity duration-300 z-50 text-xs shadow-lg pointer-events-none">
        </div>
        
        {{-- Footer --}}
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 text-xs text-slate-500 dark:text-slate-400 mt-auto transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'CampusNest') }}. Built for student housing.</p>
                <div class="flex space-x-6">
                    <span class="hover:text-indigo-600 dark:hover:text-indigo-400 transition cursor-pointer">Support</span>
                    <span class="hover:text-indigo-600 dark:hover:text-indigo-400 transition cursor-pointer">Privacy</span>
                    <span class="hover:text-indigo-600 dark:hover:text-indigo-400 transition cursor-pointer">Terms</span>
                </div>
            </div>
        </footer>
    </body>
</html>