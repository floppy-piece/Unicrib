<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                {{ __('Student') }}
            </h2>
            <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
                Browse and book available properties near campus, ordered by distance.
            </p>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(auth()->user()->category === 'student')
                <div>
                    @if(auth()->user()->hasValidStudentAccess())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50 shadow-sm">
                            Your account is Verified (enjoy the hunt)
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50 shadow-sm">
                            Your account is Not verified (let loose to enjoy)
                        </span>
                    @endif
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                @if($properties->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <div class="group border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-2xl p-4 transition-all duration-200 hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-50/50 dark:hover:shadow-none flex flex-col justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                        {{ $property->building_name }}
                                    </h3>
                                    
                                    <div class="mt-3 overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80">
                                        @if($property->images && count($property->images) > 0)
                                            <img class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                                                 src="{{ asset('storage/' . $property->images[0]) }}"
                                                 alt="{{ $property->building_name }}">
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center text-slate-400 dark:text-slate-500">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 space-y-1">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            {{ ucfirst($property->property_type) }} • <span class="text-indigo-600 dark:text-indigo-400 font-bold">KES {{ number_format($property->price) }}</span>
                                        </p>
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                            Area: {{ ucfirst($property->area) }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">
                                            {{ $property->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <a href="{{ route('properties.show', $property->id) }}" wire:navigate class="block w-full">
                                        <button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-500 transition">
                                            View details
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
                    <div class="text-center py-12">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                            No properties available yet.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>