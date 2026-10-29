{{-- resources/views/driver/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Driver Console</h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Manage student move-in dispatches, route schedules, and delivery completions.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Online &amp; Active
                </span>
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stat Cards Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-[#aabdd2] flex items-center justify-center shadow-sm">
                        🚚
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">My Assigned Trips</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $stats['total_assigned'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 flex items-center justify-center shadow-sm font-bold">
                        ⏱️
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Pending Pickups</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $stats['pending_pickup'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shadow-sm font-bold">
                        ✅
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Completed Rides</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $stats['completed'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 flex items-center justify-center shadow-sm font-bold">
                        📌
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Open Pool Trips</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $stats['unassigned'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Main Trips Section --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-6">
                
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4 gap-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Transport Schedule</h3>

                    {{-- Filter Tabs --}}
                    <div class="flex items-center gap-1.5 flex-wrap text-xs">
                        <a href="{{ route('driver.dashboard', ['status' => 'assigned']) }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ $filter === 'assigned' ? 'bg-[#cad8e5] text-[#2e2f2c] shadow-sm' : 'bg-[#dae4ee]/60 dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5]/50' }}">
                            My Pickups
                        </a>
                        <a href="{{ route('driver.dashboard', ['status' => 'available']) }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ $filter === 'available' ? 'bg-[#cad8e5] text-[#2e2f2c] shadow-sm' : 'bg-[#dae4ee]/60 dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5]/50' }}">
                            Available Pool ({{ $stats['unassigned'] }})
                        </a>
                        <a href="{{ route('driver.dashboard', ['status' => 'completed']) }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ $filter === 'completed' ? 'bg-[#cad8e5] text-[#2e2f2c] shadow-sm' : 'bg-[#dae4ee]/60 dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5]/50' }}">
                            Completed
                        </a>
                    </div>
                </div>

                {{-- Table View --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">Trip ID</th>
                                <th class="py-3 px-4 font-semibold">Student &amp; Contact</th>
                                <th class="py-3 px-4 font-semibold">Destination Property</th>
                                <th class="py-3 px-4 font-semibold">Scheduled Time</th>
                                <th class="py-3 px-4 font-semibold">Countdown</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($trips as $trip)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition align-middle">
                                <td class="py-3 px-4 font-bold text-[#2e2f2c] dark:text-white">#{{ $trip->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-[#2e2f2c] dark:text-white">{{ $trip->student_name }}</div>
                                    <a href="tel:{{ $trip->student_phone }}" class="text-[11px] text-[#2e2f2c] dark:text-[#aabdd2] hover:underline font-mono">
                                        📞 {{ $trip->student_phone }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-[#2e2f2c] dark:text-white">
                                        {{ optional($trip->property)->title ?? optional($trip->property)->building_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-[#6a6c6a] dark:text-[#cad8e5]">
                                        Area: {{ ucfirst(optional($trip->property)->area ?? 'n/a') }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5] font-medium">
                                    {{ optional($trip->assigned_at)->format('d M Y, H:i') ?? $trip->requested_date }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($trip->assigned_at && $trip->status !== 'completed' && $trip->status !== 'cancelled')
                                        <span class="text-[11px] font-semibold text-[#2e2f2c] dark:text-[#aabdd2]">
                                            In: <span class="transport-countdown" data-deadline="{{ $trip->assigned_at->toIso8601String() }}">calculating...</span>
                                        </span>
                                    @else
                                        <span class="text-[#6a6c6a] dark:text-[#cad8e5]">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php $st = $trip->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($st === 'in_transit' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if(is_null($trip->driver_id))
                                        {{-- Claim Trip Action --}}
                                        <form action="{{ route('driver.transports.claim', $trip->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] font-bold rounded-lg text-xs transition shadow-sm">
                                                Claim Trip
                                            </button>
                                        </form>
                                    @else
                                        {{-- Update Trip Progress Status --}}
                                        <form action="{{ route('driver.transports.updateStatus', $trip->id) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-1 px-2 focus:border-[#8da5c0]">
                                                <option value="confirmed" {{ $trip->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="in_transit" {{ $trip->status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                                <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $trip->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-8 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No trips found in this category.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                    {{ $trips->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Live Countdown Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdownEls = document.querySelectorAll('.transport-countdown');
        if (!countdownEls.length) return;

        function updateAllCountdowns() {
            const now = new Date().getTime();

            countdownEls.forEach(function (el) {
                const deadlineStr = el.getAttribute('data-deadline');
                if (!deadlineStr) return;

                const deadline = new Date(deadlineStr).getTime();
                const diff = deadline - now;

                if (diff <= 0) {
                    el.textContent = 'due now';
                    return;
                }

                const second = 1000;
                const minute = 60 * second;
                const hour   = 60 * minute;

                const hours   = Math.floor(diff / hour);
                const minutes = Math.floor((diff % hour) / minute);
                const seconds = Math.floor((diff % minute) / second);

                let parts = [];
                if (hours > 0) parts.push(hours + 'h');
                parts.push(minutes + 'm');
                parts.push(seconds + 's');

                el.textContent = parts.join(' ');
            });
        }

        updateAllCountdowns();
        setInterval(updateAllCountdowns, 1000);
    });
    </script>
</x-app-layout>