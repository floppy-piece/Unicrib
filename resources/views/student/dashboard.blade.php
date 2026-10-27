<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                    {{ __('Student Housing & Dashboard') }}
                </h2>
                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                    Explore available student properties near campus, check verification status, and connect with Unicrib WhatsApp AI.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 transition transform hover:-translate-y-0.5">
                    <span>💬 Connect WhatsApp AI</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(auth()->user()->category === 'student')
                <div class="flex flex-wrap items-center justify-between gap-4 p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-lg">
                            🎓
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">Account Authorization & Status</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Linked Phone: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ auth()->user()->phone ?: 'Not linked' }}</span></p>
                        </div>
                    </div>
                    <div>
                        @if(auth()->user()->hasValidStudentAccess())
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Verified Access Active ✅
                            </div>
                        @else
                            <div class="inline-flex items-center gap-3">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 shadow-sm">
                                    Unverified Access ⚠️
                                </span>
                                <a href="{{ route('pay.form') }}" wire:navigate class="px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition">
                                    Verify Payment (KSh 50)
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100">Available Campus Properties</h3>
                    <p class="text-xs text-slate-500">Showing verified student housing</p>
                </div>

                @if($properties->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <div class="card-3d-hover group border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-2xl p-5 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                            {{ $property->building_name }}
                                        </h3>
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-md bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                            {{ ucfirst($property->property_type) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80">
                                        @if($property->images && count($property->images) > 0)
                                            <img class="w-full h-48 object-cover group-hover:scale-105 transition duration-500"
                                                 src="{{ asset('storage/' . $property->images[0]) }}"
                                                 alt="{{ $property->building_name }}">
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800">
                                                <svg class="w-8 h-8 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-extrabold text-indigo-600 dark:text-indigo-400">KES {{ number_format($property->price) }}/mo</span>
                                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Area: {{ ucfirst($property->area) }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                            {{ $property->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <a href="{{ route('properties.show', $property->id) }}" wire:navigate class="block w-full">
                                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                                            View Details & Book
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="text-center py-16 space-y-3">
                        <div class="text-4xl">🏡</div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">No properties available yet</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Check back soon or use Unicrib WhatsApp AI to search listings and snap house photos for details.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
