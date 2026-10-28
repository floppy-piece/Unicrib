{{-- resources/views/components/nav.blade.php --}}
<nav class="-mx-3 flex flex-1 justify-end items-center gap-4">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-xl px-3.5 py-2 text-xs font-semibold {{ request()->is('dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }} transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8da5c0]"
        >
            <span class="text-current">Dashboard</span>
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="text-xs font-semibold text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white transition"
        >
            Log in
        </a>

        {{-- @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="text-xs font-semibold text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white transition"
            >
                Register
            </a>
        @endif --}}
    @endauth
</nav>