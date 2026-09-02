{{-- resources/views/landlord/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ __('Landlord Dashboard') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Overview of your properties and recent activity.
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

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $landlord   = auth()->user();
                $properties = $landlord->properties;
                
                $stats = [
                    'totalRooms'          => 0,
                    'totalRoomsBooked'    => 0,
                    'totalRoomsAvailable' => 0,
                ];

                $totalBookedCount = 0;

                foreach ($properties as $p) {
                    $capacity       = (int) ($p->capacity ?? 0);
                    $initialBooked  = (int) ($p->booked_count ?? 0);
                    $totalBookedCount += $initialBooked;

                    $approvedBooked = $p->bookings()->where('status', 'approved')->count();
                    $totalBooked    = $initialBooked + $approvedBooked;
                    $available      = max(0, $capacity - $totalBooked);

                    $stats['totalRooms']          += $capacity;
                    $stats['totalRoomsBooked']    += $totalBooked;
                    $stats['totalRoomsAvailable'] += $available;
                }
            @endphp

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4 transition-colors duration-200">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total properties</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $properties->count() }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4 transition-colors duration-200">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Rooms available</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $stats['totalRoomsAvailable'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4 transition-colors duration-200">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total rooms</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $stats['totalRooms'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex items-center gap-4 transition-colors duration-200">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Rooms booked</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $stats['totalRoomsBooked'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Earnings & Recent Bookings Snippet --}}
            @if(isset($recentBookings) && $recentBookings->count() > 0)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4 transition-colors duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                            Your Earnings: <span class="text-emerald-600 dark:text-emerald-400 font-bold">KES {{ number_format($recentBookings->sum('amount'), 0, '.', ',') }}</span>
                        </h3>
                        <span class="text-xs text-slate-500 dark:text-slate-400">From {{ $stats['totalRoomsBooked'] }} active bookings</span>
                    </div>

                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach($recentBookings as $booking)
                            <div class="min-w-[240px] p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 space-y-1.5 shrink-0 text-xs">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $booking->property->building_name }}</h4>
                                <p class="text-slate-600 dark:text-slate-300">Deposit: <span class="font-bold text-emerald-600 dark:text-emerald-400">KES {{ floor((int)($booking->amount)*0.999999999) }}</span></p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Subaccount: {{ $booking->paystack_subaccount_code }}</p>
                                <div>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">PAID</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Actions & Paystack Setup --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6 transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Quick actions</h3>
                </div>

                @if (!$landlord->paystack_subaccount_code)
                    <form action="{{ route('landlord.subaccount') }}" method="POST" class="p-5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 space-y-4">
                        @csrf
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Setup Payment Receiving (One-time)</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Configure your banking details to receive payouts automatically via Paystack.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="number" name="account_number" placeholder="Account Number" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-indigo-500" required />
                            <input type="number" name="bank_code" placeholder="Bank Code" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                                Create Unicrib Subaccount
                            </button>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Paid via Paystack Unicrib account</span>
                        </div>
                    </form>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                        <svg class="mr-1.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add new property
                    </a>
                    <a href="{{ route('landlord.properties.myProperties') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
                        <svg class="mr-1.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Manage properties
                    </a>
                </div>
            </div>

            {{-- Recent Properties Table --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Recent properties</h3>
                    <a href="{{ route('landlord.properties.myProperties') }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition">
                        View all
                        <svg class="ml-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                @php
                    $recentProperties = auth()->user()->properties()->latest()->take(5)->get();
                @endphp

                @if($recentProperties->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                                    <th class="py-3 px-4">Property</th>
                                    <th class="py-3 px-4">Area</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Rooms</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                @foreach($recentProperties as $property)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                @if($property->images && count($property->images) > 0)
                                                    <img class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0"
                                                         src="{{ asset('storage/' . $property->images[0]) }}"
                                                         alt="{{ $property->title }}">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $property->building_name }}</div>
                                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">{{ ucfirst($property->property_type) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                            @php
                                                $areaNames = [
                                                    'mjini' => 'Mjini',
                                                    'ngomongo' => 'Ngomongo',
                                                    'diaspora' => 'Diaspora',
                                                    'kutus' => 'Kutus'
                                                ];
                                            @endphp
                                            {{ $areaNames[$property->area] ?? $property->area }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-semibold text-emerald-600 dark:text-emerald-400">KES {{ number_format($property->price) }}</div>
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500">per month</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $unavailable = (int)($property->bookings()->where('status', 'approved')->count() + (int)($property->booked_count));
                                            @endphp
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                {{ $unavailable }} / {{ (int)($property->capacity ?? 0) }} rooms
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $capacity = (int) ($property->capacity ?? 0);
                                                $isFull = $capacity > 0 && $unavailable >= $capacity;
                                            @endphp

                                            <div class="flex flex-wrap gap-1 items-center">
                                                @if($isFull)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Full</span>
                                                @else
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Has rooms</span>
                                                @endif

                                                @if($property->is_verified == 1 && $property->is_available == 1)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">Verified</span>
                                                @else
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('landlord.properties.edit', $property) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('landlord.properties.destroy', $property) }}"
                                                      method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this property?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-500 transition bg-transparent border-none p-0 cursor-pointer">
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
                    <div class="text-center py-12 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100">No properties yet</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Get started by adding your first property listing.</p>
                        <a href="{{ route('landlord.properties.add') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                            <svg class="mr-1.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add your first property
                        </a>
                    </div>
                @endif
            </div>

            <p class="text-[11px] text-slate-400 dark:text-slate-500 text-center">Note: Your property must be verified for it to be displayed to tenants.</p>
        </div>
    </div>
</x-app-layout>