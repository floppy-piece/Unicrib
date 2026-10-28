{{-- resources/views/admin/bookings/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Admin • Bookings</h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Inspect and override booking statuses across the platform.</p>
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

                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">All bookings</h3>
                    <span class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] font-medium">Manage student reservations (5% Booking Deposit)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Landlord</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Payment Status (5% Deposit)</th>
                                <th class="py-3 px-4 font-semibold">Created</th>
                                <th class="py-3 px-4 font-semibold text-right">Change status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($bookings as $booking)
                            @php
                                $s = $booking->status;
                                $paymentStatus = $booking->payment_status;
                                // Calculate 5% deposit from total amount
                                $depositAmount = $booking->amount * 0.05;
                            @endphp
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition align-middle">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">#{{ $booking->id }}</td>
                                <td class="py-3 px-4 text-[#2e2f2c] dark:text-white font-medium">{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($booking->property)->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional(optional($booking->property)->landlord)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $s === 'approved' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($s === 'rejected' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                                {{ $paymentStatus === 'paid' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($paymentStatus === 'refunded' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800') }}">
                                                {{ ucfirst($paymentStatus) }}
                                            </span> 
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">KES {{ number_format($depositAmount, 0, '.', ',') }}</span>
                                        </div>
                                        <div class="text-[10px] text-[#6a6c6a] dark:text-[#cad8e5]">
                                            (5% of total KES {{ number_format($booking->amount, 0, '.', ',') }})
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5] whitespace-nowrap">{{ $booking->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.bookings.updateStatus', $booking) }}"
                                          method="POST"
                                          class="inline-flex items-center justify-end gap-2">
                                        @csrf
                                        <select name="status" class="rounded-xl border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-1.5 px-3 focus:border-[#8da5c0] focus:ring-[#8da5c0] shadow-sm">
                                            <option value="rejected" {{ $booking->status === 'rejected' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Rejected</option>
                                            <option value="approved" {{ $booking->status === 'approved' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Approved</option>
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Pending</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center justify-center bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold px-3 py-1.5 rounded-xl transition shadow-sm">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No bookings found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                    {{ $bookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>