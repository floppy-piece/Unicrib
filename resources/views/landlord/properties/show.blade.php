{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                    {{ $property->title }}
                </h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Property details and booking information.
                </p>
            </div>
        </div>
    </x-slot>



    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6 transition-colors duration-200">
                
                {{-- Title & Description --}}
                <div class="space-y-2 border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-lg font-bold text-[#2e2f2c] dark:text-white">
                        {{ $property->title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                        {{ $property->description }}
                    </p>
                </div>

                {{-- Property Meta Info Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Price</span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">KES {{ number_format($property->price) }} <span class="text-[10px] text-[#6a6c6a] dark:text-[#cad8e5] font-normal">/ mo</span></span>
                    </div>

                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Area</span>
                        <span class="text-[#2e2f2c] dark:text-white font-semibold text-sm">{{ ucfirst($property->area ?? 'N/A') }}</span>
                    </div>

                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Type</span>
                        <span class="text-[#2e2f2c] dark:text-white font-semibold text-sm">{{ ucfirst($property->property_type ?? 'N/A') }}</span>
                    </div>

                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Capacity</span>
                        <span class="text-[#2e2f2c] dark:text-white font-semibold text-sm">{{ (int)($property->capacity ?? 0) }} rooms</span>
                    </div>
                </div>

                {{-- Gallery Grid --}}
                @if(!empty($property->images))
                    <div class="space-y-3 pt-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#6a6c6a] dark:text-[#aabdd2]">Property Photos</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($property->images as $img)
                                <div class="overflow-hidden rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee] dark:bg-[#2e2f2c] aspect-video">
                                    <img src="{{ asset('storage/'.$img) }}" 
                                        alt="{{ $property->title }}" 
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236a6c6a\'><rect width=\'100%\' height=\'100%\' fill=\'%23dae4ee\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>';">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back Navigation --}}
                <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a] flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition gap-1">
                        &larr; Back
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>