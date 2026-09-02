{{-- resources/views/components/nav.blade.php (or your navigation partial) --}}
<nav class="-mx-3 flex flex-1 justify-end items-center gap-4">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
        >
            Dashboard
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