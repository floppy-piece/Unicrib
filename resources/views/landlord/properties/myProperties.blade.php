{{-- resources/views/landlord/properties/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    {{ __('My Properties') }}
                </h2>
                <p class="text-xs text-slate-500">
                    Manage all your rental properties and keep them up to date.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 space-y-6">
                    
                    {{-- Header & Add Button --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h1 class="text-lg font-bold text-slate-900">My Properties</h1>
                            <p class="text-xs text-slate-500 mt-0.5">Manage all your rental properties.</p>
                        </div>
                        <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Property
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-700 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    @php
                        $recentProperties = auth()->user()->properties()->latest()->take(5)->get();
                    @endphp

                    @if($recentProperties->count() > 0)
                        <div class="overflow-x-auto border border-slate-200 rounded-xl">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4">Property</th>
                                        <th class="py-3 px-4">Area</th>
                                        <th class="py-3 px-4">Price</th>
                                        <th class="py-3 px-4">Rooms</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @foreach($recentProperties as $property)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                @if($property->images && count($property->images) > 0)
                                                    @php $path = preg_replace('/^public\//', '', $property->images[0]); @endphp
                                                    <img class="w-16 h-12 rounded-lg object-cover border border-slate-200 shrink-0"
                                                         src="{{ asset('storage/' . $path) }}"
                                                         alt="{{ $property->title }}">
                                                @else
                                                    <div class="w-16 h-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-slate-900">{{ $property->building_name ?: $property->title }}</div>
                                                    <div class="text-[11px] text-slate-500">{{ ucfirst($property->property_type) }}</div>
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
                                            <span class="font-medium text-slate-700">{{ $areaNames[$property->area] ?? $property->area }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-emerald-600">
                                                KES {{ number_format($property->price) }}
                                            </div>
                                            <div class="text-[10px] text-slate-400">per month</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $unavailable = (int)($property->bookings()->where('status', 'approved')->count() + (int)($property->booked_count));
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
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
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">Full</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Has rooms</span>
                                                @endif

                                                @if($property->is_verified == 1 && $property->is_available == 1)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">Verified</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('landlord.properties.edit', $property) }}" class="font-semibold text-indigo-600 hover:text-indigo-900 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('landlord.properties.destroy', $property) }}"
                                                      method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this property?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-semibold text-rose-600 hover:text-rose-900 bg-transparent border-none p-0 cursor-pointer transition">
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
                        <div class="text-center py-12 px-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <div class="w-12 h-12 mx-auto mb-3 text-slate-400 bg-white border border-slate-200 rounded-2xl flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">No properties yet</h3>
                            <p class="text-xs text-slate-500 mb-4">Get started by adding your first property listing.</p>
                            <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
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