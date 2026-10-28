{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
      }" 
      x-init="
          $watch('darkMode', val => {
              localStorage.setItem('dark', val);
              if (val) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          })
      ">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'UniCrib'))</title>
    <x-icon/>

    <!-- Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  
    {{-- Prevent flash of light/dark mode --}}
    <script>
        if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
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
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-indigo-600 selection:text-white min-h-screen flex flex-col transition-colors duration-200">

    {{-- Global Page Loader Overlay (Automatically shows on Livewire navigation / requests) --}}
    <div wire:loading.delay.class.remove="hidden" class="hidden">
        <x-loading />
    </div>

    {{-- Initial Page Load Overlay --}}
    <x-loading />

    {{-- Livewire SPA Navigation Loading Bar --}}
    <div wire:loading.delay class="fixed top-0 left-0 right-0 z-[110] h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-indigo-600 animate-pulse"></div>

    {{-- HEADER --}}
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center overflow-hidden shadow-sm">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">UniCrib</span>
                </a>

                <div class="flex items-center space-x-4">
                    <x-non-nav/>
                    @if (Route::has('login'))
                        <livewire:welcome.navigation/>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-12 text-sm text-slate-500 dark:text-slate-400 mt-auto transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center max-w-lg mx-auto space-y-2">
                <div class="text-lg font-bold text-slate-900 dark:text-slate-100">UniCrib</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    @yield('footerDescription', 'Premium student accommodations with clear information and responsive support. UniCrib is committed to improving the housing experience for students and landlords.')
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Platform</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" wire:navigate>Properties</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" wire:navigate>Universities</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" wire:navigate>Services</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" wire:navigate>Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Company</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/about') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" wire:navigate>About us</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Careers</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Contact</h3>
                    <ul class="space-y-2 text-xs">
                        <li>Kutus – Kirinyaga</li>
                        <li><a href="tel:+254704969006" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">+254 704 969 006</a></li>
                        <li><a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">unicrib@gmail.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 dark:text-slate-500 gap-4">
                <p>© {{ date('Y') }} UniCrib. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Privacy policy</a>
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Terms of service</a>
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Cookie policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>