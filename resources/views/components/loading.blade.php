{{-- resources/views/components/loading.blade.php --}}
<div 
    x-data="{ show: true }" 
    x-init="window.addEventListener('load', () => show = false); setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    wire:loading.delay.remove
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-[#dae4ee]/90 dark:bg-[#2e2f2c]/90 backdrop-blur-md transition-colors duration-200"
>
    <div class="relative flex flex-col items-center">
        <!-- Outer Glowing Ring -->
        <div class="w-20 h-20 rounded-full border-4 border-[#cad8e5] dark:border-[#6a6c6a] border-t-[#2e2f2c] dark:border-t-[#cad8e5] animate-spin"></div>
        
        <!-- Center Animated Logo -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="h-10 w-10 rounded-full bg-white dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center overflow-hidden shadow-inner animate-pulse">
                <x-application-logo class="h-full w-full object-cover" />
            </div>
        </div>
    </div>

    <!-- Loading Status Text -->
    <div class="mt-6 text-center space-y-1">
        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white tracking-wide">
            UniCrib
        </h3>
        <p class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] animate-pulse">
            Loading student housing...
        </p>
    </div>
</div>