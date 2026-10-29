{{-- resources/views/layouts/guest.blade.php --}}
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniCrib') }} – Authentication</title>
    <x-icon/>

    <!-- Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(0.5deg); }
        }
        .animate-float {
            animation: floatSlow 6s ease-in-out infinite;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#cad8e5] antialiased min-h-screen flex items-center justify-center p-4 selection:bg-[#8da5c0] selection:text-white transition-colors duration-300">

    {{-- Global Page Loader Overlay --}}
    <x-loading />

    {{-- Livewire SPA Navigation Loading Bar --}}
    <div wire:loading.delay class="fixed top-0 left-0 right-0 z-[110] h-1 bg-gradient-to-r from-[#8da5c0] via-[#aabdd2] to-[#8da5c0] animate-pulse"></div>
    
    <div class="w-full max-w-md animate-float">
        <!-- Main Card with 3D Shadow Depth -->
        <div class="bg-white dark:bg-[#6a6c6a]/30 rounded-3xl border border-[#cad8e5] dark:border-[#8da5c0]/40 shadow-2xl p-8 sm:p-10 relative overflow-hidden transition-all duration-300">
            
            <!-- Header Section -->
            <header class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3 group" wire:navigate>
                    <div class="h-14 w-14 rounded-2xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center overflow-hidden shadow-sm transition-transform duration-300 group-hover:scale-105">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-[#2e2f2c] dark:text-white group-hover:text-[#8da5c0] transition">UniCrib</h1>
                </a>
            </header>

            <!-- Inner Content Slot -->
            <div class="space-y-4">
                {{ $slot }}
            </div>

            <!-- Footer Link -->
            <div class="mt-6 pt-6 text-center text-xs text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                <span>Feel free to come again.</span>
            </div>

        </div>
    </div>

</body>
</html>