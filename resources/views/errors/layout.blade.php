{{-- resources/views/errors/layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name', 'UniCrib') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white font-sans antialiased min-h-screen flex flex-col justify-between transition-colors duration-200">
    
    {{-- Top Header (Centered Layout) --}}
    <header class="max-w-7xl w-full mx-auto p-6 flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-4">
        <div></div> <!-- Spacer for balance on desktop -->
        
        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
            <x-application-logo class="h-10 w-auto" />
            
        </a>  

        <div>
            
        </div>
    </header>

    {{-- Main Error Content --}}
    <main class="max-w-xl w-full mx-auto px-6 py-12 text-center my-auto space-y-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 shadow-sm text-3xl font-extrabold text-[#2e2f2c] dark:text-white">
            @yield('code')
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                @yield('message_title')
            </h1>
            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed max-w-md mx-auto">
                @yield('message_description')
            </p>
        </div>

        <div class="flex items-center justify-center gap-3 pt-2">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="px-4 py-2 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-white rounded-xl text-xs font-semibold hover:bg-[#cad8e5]/50 transition shadow-sm">
                Go Back
            </a>
            <a href="{{ url('/') }}" class="px-4 py-2 bg-[#2e2f2c] hover:bg-[#6a6c6a] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] dark:hover:bg-[#aabdd2] rounded-xl text-xs font-bold transition shadow-sm">
                Return Home
            </a>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="p-6 text-center text-[11px] text-[#6a6c6a] dark:text-[#cad8e5]">
        &copy; {{ date('Y') }} UniCrib Student Housing Platform. All rights reserved.
    </footer>
</body>
</html>