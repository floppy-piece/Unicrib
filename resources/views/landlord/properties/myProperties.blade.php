{{-- resources/views/landlord/properties/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                    {{ __('My Properties') }}
                </h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-0.5">
                    Manage all your rental properties and keep them up to date.
                </p>
            </div>
        </div>
    </x-slot>

    @push('scripts')
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    @endpush

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 sm:p-8 space-y-6">
                    
                    {{-- Header & Add Button --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                        <div>
                            <h1 class="text-lg font-bold text-[#2e2f2c] dark:text-white">My Properties</h1>
                            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-0.5">Manage all your rental properties.</p>
                        </div>
                        <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Property
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    @php
                        $recentProperties = auth()->user()->properties()->latest()->take(5)->get();
                    @endphp

                    @if($recentProperties->count() > 0)
                        <div class="overflow-x-auto border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 text-[#6a6c6a] dark:text-[#cad8e5] font-semibold uppercase text-[10px] tracking-wider border-b border-[#cad8e5] dark:border-[#6a6c6a]">
                                    <tr>
                                        <th class="py-3 px-4">Property</th>
                                        <th class="py-3 px-4">Area</th>
                                        <th class="py-3 px-4">Price</th>
                                        <th class="py-3 px-4">Rooms</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a] text-[#2e2f2c] dark:text-[#cad8e5]">
                                    @foreach($recentProperties as $property)
                                    <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                @if($property->images && count($property->images) > 0)
                                                    @php $path = preg_replace('/^public\//', '', $property->images[0]); @endphp
                                                    <img class="w-16 h-12 rounded-lg object-cover border border-[#cad8e5] dark:border-[#6a6c6a] shrink-0"
                                                         src="{{ asset('storage/' . $path) }}"
                                                         alt="{{ $property->title }}">
                                                @else
                                                    <div class="w-16 h-12 rounded-lg bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] flex items-center justify-center text-[#6a6c6a] dark:text-[#aabdd2] shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-[#2e2f2c] dark:text-white">{{ $property->building_name ?: $property->title }}</div>
                                                    <div class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">{{ ucfirst($property->property_type) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $areaNames = [
                                                    'mjini' => 'Mjini',
                                                    'ngomongo' => 'Ngomongo',
                                                    'diaspora' => 'Diaspora',
                                                    'kutus' => 'Kutus'
                                                ];
                                            @endphp
                                            <span class="font-medium text-[#2e2f2c] dark:text-white">{{ $areaNames[$property->area] ?? $property->area }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-emerald-600 dark:text-emerald-400">
                                                KES {{ number_format($property->price) }}
                                            </div>
                                            <div class="text-[10px] text-[#6a6c6a] dark:text-[#aabdd2]">per month</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $unavailable = (int)($property->bookings()->where('status', 'approved')->count() + (int)($property->booked_count));
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] border border-[#aabdd2] dark:border-[#6a6c6a]">
                                                {{ $unavailable }} / {{ (int)($property->capacity ?? 0) }} rooms
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $currentBooked = $unavailable;
                                                $capacity = (int) ($property->capacity ?? 0);
                                                $isFull = $capacity > 0 && $currentBooked >= $capacity;
                                            @endphp

                                            <div class="flex flex-wrap gap-1 items-center">
                                                @if($isFull)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Full</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Has rooms</span>
                                                @endif

                                                @if($property->is_verified == 1 && $property->is_available == 1)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#cad8e5] dark:bg-[#6a6c6a] text-[#2e2f2c] dark:text-white border border-[#aabdd2] dark:border-[#6a6c6a]">Verified</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('landlord.properties.edit', $property) }}" class="font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('landlord.properties.destroy', $property) }}"
                                                      method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this property?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-500 bg-transparent border-none p-0 cursor-pointer transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 px-4 border border-dashed border-[#cad8e5] dark:border-[#6a6c6a] rounded-2xl bg-white/50 dark:bg-[#2e2f2c]/50">
                            <div class="w-12 h-12 mx-auto mb-3 text-[#6a6c6a] dark:text-[#aabdd2] bg-white dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-2xl flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-1">No properties yet</h3>
                            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mb-4">Get started by adding your first property listing.</p>
                            <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add your first property
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>