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

<body class="bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#cad8e5] antialiased selection:bg-[#8da5c0] selection:text-white min-h-screen flex flex-col transition-colors duration-200">

    {{-- Global Page Loader Overlay (Automatically shows on Livewire navigation / requests) --}}
    <div wire:loading.delay.class.remove="hidden" class="hidden">
        <x-loading />
    </div>

    {{-- Initial Page Load Overlay --}}
    <x-loading />

    {{-- Livewire SPA Navigation Loading Bar --}}
    <div wire:loading.delay class="fixed top-0 left-0 right-0 z-[110] h-1 bg-gradient-to-r from-[#8da5c0] via-[#aabdd2] to-[#8da5c0] animate-pulse"></div>

    {{-- HEADER --}}
    <header class="bg-[#cad8e5] dark:bg-[#6a6c6a] border-b border-[#aabdd2] dark:border-[#2e2f2c] sticky top-0 z-50 transition-colors duration-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <div class="h-10 w-10 rounded-full bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center overflow-hidden shadow-sm">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">UniCrib</span>
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
    <footer class="bg-[#cad8e5] dark:bg-[#6a6c6a] border-t border-[#aabdd2] dark:border-[#2e2f2c] py-12 text-sm text-[#6a6c6a] dark:text-[#cad8e5] mt-auto transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center max-w-lg mx-auto space-y-2">
                <div class="text-lg font-bold text-[#2e2f2c] dark:text-white">UniCrib</div>
                <p class="text-xs text-[#6a6c6a] dark:text-[#aabdd2]">
                    @yield('footerDescription', 'Premium student accommodations with clear information and responsive support. UniCrib is committed to improving the housing experience for students and landlords.')
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="font-semibold text-[#2e2f2c] dark:text-white text-xs tracking-wider uppercase mb-3">Platform</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-[#2e2f2c] dark:hover:text-white transition" wire:navigate>Properties</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-[#2e2f2c] dark:hover:text-white transition" wire:navigate>Universities</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-[#2e2f2c] dark:hover:text-white transition" wire:navigate>Services</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-[#2e2f2c] dark:hover:text-white transition" wire:navigate>Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-[#2e2f2c] dark:text-white text-xs tracking-wider uppercase mb-3">Company</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/about') }}" class="hover:text-[#2e2f2c] dark:hover:text-white transition" wire:navigate>About us</a></li>
                        <li><a href="#" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-[#2e2f2c] dark:text-white text-xs tracking-wider uppercase mb-3">Contact</h3>
                    <ul class="space-y-2 text-xs">
                        <li>Kutus – Kirinyaga</li>
                        <li><a href="tel:+254704969006" class="text-[#2e2f2c] dark:text-white font-medium hover:underline">+254 704 969 006</a></li>
                        <li><a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" class="text-[#2e2f2c] dark:text-white font-medium hover:underline">unicrib@gmail.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-[#aabdd2] dark:border-[#2e2f2c] pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-[#6a6c6a] dark:text-[#aabdd2] gap-4">
                <p>© {{ date('Y') }} UniCrib. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="/privacy-policy" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Privacy policy</a>
                    <a href="/terms-of-service" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Terms of service</a>
                    <a href="/cookie-policy" class="hover:text-[#2e2f2c] dark:hover:text-white transition">Cookie policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>