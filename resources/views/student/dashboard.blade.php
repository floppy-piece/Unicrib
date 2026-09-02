<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                {{ __('Student') }}
            </h2>
            <p class="mt-0.5 text-xs font-medium text-slate-500">
                Browse and book available properties near campus, ordered by distance.
            </p>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(auth()->user()->category === 'student')
                <div>
                    @if(auth()->user()->hasValidStudentAccess())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                            Your account is Verified (enjoy the hunt)
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                            Your account is Not verified (let loose to enjoy)
                        </span>
                    @endif
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                @if($properties->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <div class="group border border-slate-200 bg-white rounded-2xl p-4 transition-all duration-200 hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-50/50 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">
                                        {{ $property->building_name }}
                                    </h3>
                                    
                                    <div class="mt-3 overflow-hidden rounded-xl bg-slate-100 border border-slate-100">
                                        @if($property->images && count($property->images) > 0)
                                            <img class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                                                 src="{{ asset('storage/' . $property->images[0]) }}"
                                                 alt="{{ $property->building_name }}">
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 space-y-1">
                                        <p class="text-xs font-semibold text-slate-700">
                                            {{ ucfirst($property->property_type) }} • <span class="text-indigo-600 font-bold">KES {{ number_format($property->price) }}</span>
                                        </p>
                                        <p class="text-xs font-medium text-slate-500">
                                            Area: {{ ucfirst($property->area) }}
                                        </p>
                                        <p class="text-xs text-slate-500 line-clamp-2 mt-1">
                                            {{ $property->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 pt-3 border-t border-slate-100">
                                    <a href="{{ route('properties.show', $property->id) }}" wire:navigate class="block w-full">
                                        <button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition">
                                            View details
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-8 pt-4 border-t border-slate-100 text-xs font-medium text-slate-600">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-xs font-semibold text-slate-500">
                            No properties available yet.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>