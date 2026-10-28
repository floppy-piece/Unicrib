{{-- resources/views/components/dark-mode-toggle.blade.php --}}
<button 
    x-data="{ 
        darkMode: localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
    }"
    x-init="
        $watch('darkMode', val => {
            localStorage.setItem('dark', val);
            document.documentElement.classList.toggle('dark', val);
        });
        document.documentElement.classList.toggle('dark', darkMode);
    "
    @click="darkMode = !darkMode"
    type="button"
    aria-label="Toggle dark mode"
    class="p-2 rounded-xl border border-[#aabdd2] dark:border-[#6a6c6a] bg-white dark:bg-[#6a6c6a]/40 text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#cad8e5]/40 dark:hover:bg-[#6a6c6a]/60 shadow-sm transition"
>
    <!-- Sun Icon (Visible in Dark Mode to switch back to Light) -->
    <svg x-show="darkMode" class="w-4 h-4 text-[#cad8e5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>

    <!-- Moon Icon (Visible in Light Mode to switch to Dark) -->
    <svg x-show="!darkMode" class="w-4 h-4 text-[#2e2f2c]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
    </svg>
</button>