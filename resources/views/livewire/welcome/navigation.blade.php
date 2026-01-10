<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 text-gray-400 ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            style="color:rgb(244,244,244);margin-right:20px;" onmouseover="this.style.color='purple'" onmouseout="this.style.color='rgb(244,244,244)'"
        >
            Log in
        </a>

        {{-- @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                style="color:rgb(244,244,244);" onmouseover="this.style.color='purple'" onmouseout="this.style.color='rgb(244,244,244)'"
            >
                Register
            </a>
        @endif --}}
    @endauth
</nav>
