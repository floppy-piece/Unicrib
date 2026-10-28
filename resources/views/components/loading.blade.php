{{-- resources/views/components/loading.blade.php --}}
<div 
    x-data="{ show: true }" 
    x-init="window.addEventListener('load', () => show = false); setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    wire:loading.delay.remove
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-50/90 dark:bg-slate-950/90 backdrop-blur-md transition-colors duration-200"
>
    <div class="relative flex flex-col items-center">
        <!-- Outer Glowing Ring -->
        <div class="w-20 h-20 rounded-full border-4 border-indigo-100 dark:border-slate-800 border-t-indigo-600 dark:border-t-indigo-500 animate-spin"></div>
        
        <!-- Center Animated Logo -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center overflow-hidden shadow-inner animate-pulse">
                <x-application-logo class="h-full w-full object-cover" />
            </div>
        </div>
    </div>

    <!-- Loading Status Text -->
    <div class="mt-6 text-center space-y-1">
        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-wide">
            UniCrib
        </h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 animate-pulse">
            Loading student housing...
        </p>
    </div>
</div>