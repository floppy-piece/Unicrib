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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased min-h-screen flex items-center justify-center p-4 selection:bg-indigo-600 selection:text-white transition-colors duration-300">

    <div class="w-full max-w-md animate-float">
        <!-- Main Card with 3D Shadow Depth -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-2xl shadow-indigo-500/10 dark:shadow-none p-8 sm:p-10 relative overflow-hidden transition-all duration-300 hover:shadow-indigo-500/20">
            
            <!-- Header Section (Clean, no slop pills) -->
            <header class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3 group" wire:navigate>
                    <div class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center overflow-hidden shadow-sm transition-transform duration-300 group-hover:scale-105">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">UniCrib</h1>
                </a>
            </header>

            <!-- Inner Content Slot -->
            <div class="space-y-4">
                {{ $slot }}
            </div>

            <!-- Footer Link -->
            <div class="mt-6 pt-6 text-center text-xs text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800">
                <span>Need an account?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline ml-1 transition" wire:navigate>Register instead</a>
            </div>

        </div>
    </div>

</body>
</html>