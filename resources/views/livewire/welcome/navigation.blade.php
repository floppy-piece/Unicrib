{{-- resources/views/components/nav.blade.php --}}
<nav class="-mx-3 flex flex-1 justify-end items-center gap-4">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-xl px-3.5 py-2 text-xs font-semibold {{ request()->is('dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
        >
            <span class="text-current">Dashboard</span>
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="text-xs font-semibold text-slate-300 hover:text-purple-400 transition"
        >
            Log in
        </a>

        {{-- @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="text-xs font-semibold text-slate-300 hover:text-purple-400 transition"
            >
                Register
            </a>
        @endif --}}
    @endauth
</nav>