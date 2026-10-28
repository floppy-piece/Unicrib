{{-- resources/views/layouts/app.blade.php --}}
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

        <title>{{ config('app.name', 'UniCrib') }}</title>

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
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#cad8e5] antialiased min-h-screen flex flex-col selection:bg-[#8da5c0] selection:text-white transition-colors duration-200">
        
        {{-- Global Page Loader Overlay --}}
        <x-loading />

        {{-- Livewire SPA Navigation Loading Bar --}}
        <div wire:loading.delay class="fixed top-0 left-0 right-0 z-[110] h-1 bg-gradient-to-r from-[#8da5c0] via-[#aabdd2] to-[#8da5c0] animate-pulse"></div>

        {{-- Navigation (Livewire component) --}}
        <livewire:layout.navigation />

        {{-- Page Heading --}}
        @if (isset($header))
            <header class="bg-[#cad8e5] dark:bg-[#6a6c6a] border-b border-[#aabdd2] dark:border-[#2e2f2c] shadow-sm transition-colors duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="text-xl font-bold text-[#2e2f2c] dark:text-white tracking-tight">
                        {{ $header }}
                    </div>
                    @auth
                        <div class="flex items-center space-x-2 text-sm text-[#6a6c6a] dark:text-[#cad8e5] bg-[#dae4ee] dark:bg-[#2e2f2c] px-3 py-1.5 rounded-xl w-fit border border-[#aabdd2] dark:border-[#6a6c6a]">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-medium text-[#2e2f2c] dark:text-[#cad8e5]">Signed in as</span>
                            <span class="font-semibold text-[#2e2f2c] dark:text-white">{{ Auth()->user()->name }}</span>
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
            class="fixed top-4 right-4 bg-[#2e2f2c] dark:bg-[#6a6c6a] text-white px-4 py-3 rounded-xl border border-[#6a6c6a] dark:border-[#8da5c0] opacity-0 transition-opacity duration-300 z-50 text-xs shadow-lg pointer-events-none">
        </div>
        
        {{-- Footer --}}
        <footer class="bg-[#cad8e5] dark:bg-[#6a6c6a] border-t border-[#aabdd2] dark:border-[#2e2f2c] py-6 text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-auto transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'UniCrib') }}. Built for student housing.</p>
                <div class="flex space-x-6">
                    <span class="hover:text-[#2e2f2c] dark:hover:text-white transition cursor-pointer">Support</span>
                    <span class="hover:text-[#2e2f2c] dark:hover:text-white transition cursor-pointer">Privacy</span>
                    <span class="hover:text-[#2e2f2c] dark:hover:text-white transition cursor-pointer">Terms</span>
                </div>
            </div>
        </footer>
    </body>
</html>