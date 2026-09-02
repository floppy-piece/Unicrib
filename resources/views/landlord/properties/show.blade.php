{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    {{ $property->title }}
                </h2>
                <p class="text-xs text-slate-500">
                    Property details and booking information.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
                
                {{-- Title & Description --}}
                <div class="space-y-2 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $property->title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $property->description }}
                    </p>
                </div>

                {{-- Property Meta Info Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Price</span>
                        <span class="text-emerald-600 font-bold text-sm">KES {{ number_format($property->price) }} <span class="text-[10px] text-slate-500 font-normal">/ mo</span></span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Area</span>
                        <span class="text-slate-900 font-semibold text-sm">{{ ucfirst($property->area ?? 'N/A') }}</span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Type</span>
                        <span class="text-slate-900 font-semibold text-sm">{{ ucfirst($property->property_type ?? 'N/A') }}</span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Capacity</span>
                        <span class="text-slate-900 font-semibold text-sm">{{ (int)($property->capacity ?? 0) }} rooms</span>
                    </div>
                </div>

                {{-- Gallery Grid --}}
                @if(!empty($property->images))
                    <div class="space-y-3 pt-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Property Photos</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($property->images as $img)
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50 aspect-video">
                                    <img src="{{ asset('storage/'.$img) }}" alt="{{ $property->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back Navigation --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition gap-1">
                        &larr; Back
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>