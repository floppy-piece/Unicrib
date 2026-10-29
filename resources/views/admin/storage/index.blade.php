{{-- resources/views/admin/storage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Admin • Store housing</h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Manage student storage bookings, payment status, and completion.
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Admin Navigation Tabs --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-3 shadow-sm flex items-center gap-2 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Users
                </a>
                <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.properties.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Properties
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.bookings.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Bookings
                </a>
                <a href="{{ route('admin.transports.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.transports.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Transports
                </a>
                <a href="{{ route('admin.storage.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.storage.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Store Housing
                </a>
            </div>

            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-6 transition-colors duration-200">
                
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
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Storage bookings</h3>

                    {{-- Status filter links --}}
                    <div class="flex items-center gap-1.5 flex-wrap text-xs">
                        <a href="{{ route('admin.storage.index') }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ !$status ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'bg-[#dae4ee]/60 dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5]/50 dark:hover:bg-[#6a6c6a]' }}">
                            All ({{ array_sum($statusCounts) }})
                        </a>
                        @foreach (['pending','paid','completed','cancelled'] as $st)
                            <a href="{{ route('admin.storage.index', ['status' => $st]) }}"
                               class="px-3 py-1 rounded-lg font-semibold transition {{ $status === $st ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'bg-[#dae4ee]/60 dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5]/50 dark:hover:bg-[#6a6c6a]' }}">
                                {{ ucfirst($st) }} ({{ $statusCounts[$st] ?? 0 }})
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Metric cards summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-4 transition-colors duration-200">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Total storage bookings</p>
                        <p class="text-xl font-bold text-[#2e2f2c] dark:text-white">{{ $totalStorageBookings }}</p>
                    </div>
                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-4 transition-colors duration-200">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Active (paid)</p>
                        <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $storageStatusCounts['paid'] ?? 0 }}</p>
                    </div>
                    <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-4 transition-colors duration-200">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Completed</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $storageStatusCounts['completed'] ?? 0 }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Period</th>
                                <th class="py-3 px-4 font-semibold">Months</th>
                                <th class="py-3 px-4 font-semibold">Amount</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Reference</th>
                                <th class="py-3 px-4 font-semibold text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($storageBookings as $booking)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition align-middle">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">#{{ $booking->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-[#2e2f2c] dark:text-white">
                                        {{ optional($booking->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">
                                        {{ optional($booking->user)->email ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5] font-medium">
                                    {{ $booking->start_date }} – {{ $booking->end_date }}
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ $booking->months }}</td>
                                <td class="py-3 px-4 font-bold text-[#2e2f2c] dark:text-white">KES {{ number_format($booking->amount) }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'paid' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($st === 'completed' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5] font-mono text-[11px]">
                                    {{ $booking->reference ?? '—' }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.storage.update', $booking->id) }}"
                                          method="POST"
                                          class="inline-flex items-center justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="rounded-xl border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-1 px-3 focus:border-[#8da5c0] focus:ring-[#8da5c0] shadow-sm">
                                            @foreach (['pending','paid','completed','cancelled'] as $st)
                                                <option value="{{ $st }}" {{ $booking->status === $st ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">
                                                    {{ ucfirst($st) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No storage bookings found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                    {{ $storageBookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>