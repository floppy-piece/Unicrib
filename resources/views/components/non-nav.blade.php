{{-- resources/views/components/non-nav.blade.php --}}
<div x-data="{ open: false }" class="relative flex items-center space-x-2">
    {{-- Dark Mode Toggle Button --}}
    <x-dark-mode-toggle />

    {{-- Hamburger Trigger Button (Mobile Only) --}}
    <button @click="open = !open" type="button" class="md:hidden inline-flex items-center justify-center p-2.5 rounded-xl text-[#2e2f2c] dark:text-[#cad8e5] bg-white dark:bg-[#6a6c6a]/40 border border-[#aabdd2] dark:border-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/40 dark:hover:bg-[#6a6c6a]/60 focus:outline-none transition shadow-sm" aria-label="Toggle Navigation">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Desktop Navigation --}}
    <nav class="hidden md:flex items-center gap-2">
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->is('/') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
               <path d="M8.62 13.8A2.25 2.25 0 1 1 12 10.836a2.25 2.25 0 1 1 3.38 2.966l-2.626 2.856a.998.998 0 0 1-1.507 0z"/>
               <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
           </svg>
           <span class="text-current">Home</span>
        </a>

        <a href="{{ url('/about') }}"
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->is('about') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
               <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
               <path d="M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3"/>
               <path d="M12 17h.01"/>
           </svg>
           <span class="text-current">About Us</span>
        </a>

        <a href="{{ url('/services') }}"
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->is('services') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
               <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
               <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
               <path d="M12 11h4"/>
               <path d="M12 16h4"/>
               <path d="M8 11h.01"/>
               <path d="M8 16h.01"/>
           </svg>
           <span class="text-current">Services</span>
        </a>

        <a href="{{ url('/contact') }}"
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->is('contact') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
               <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>
           </svg>
           <span class="text-current">Contact</span>
        </a>
    </nav>

    {{-- Mobile Dropdown Menu Drawer --}}
    <div x-show="open"
         @click.outside="open = false"
         class="absolute right-0 top-12 w-56 bg-white dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] rounded-2xl shadow-xl py-3 px-2 z-50 md:hidden flex flex-col space-y-1.5"
         style="display: none;">
        
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->is('/') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c]' : 'text-[#6a6c6a] dark:text-[#cad8e5]' }}">
            <span>Home</span>
        </a>
        <a href="{{ url('/about') }}" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->is('about') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c]' : 'text-[#6a6c6a] dark:text-[#cad8e5]' }}">
            <span>About Us</span>
        </a>
        <a href="{{ url('/services') }}" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->is('services') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c]' : 'text-[#6a6c6a] dark:text-[#cad8e5]' }}">
            <span>Services</span>
        </a>
        <a href="{{ url('/contact') }}" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->is('contact') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c]' : 'text-[#6a6c6a] dark:text-[#cad8e5]' }}">
            <span>Contact</span>
        </a>
    </div>
</div>