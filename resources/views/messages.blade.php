{{-- resources/views/landlord/tenants.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                    {{ __('Tenants') }}
                </h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-0.5">
                    Review and manage booking requests and active tenants.
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

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 sm:p-8 space-y-6">
                    @php $user = auth()->user(); @endphp

                    {{-- Page Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-6 border-b border-[#cad8e5] dark:border-[#6a6c6a]">
                        <div>
                            <h3 class="text-lg font-bold text-[#2e2f2c] dark:text-white">
                                {{ $user && $user->category === 'landlord' ? 'Booking Requests' : 'Booking Updates' }}
                            </h3>
                            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-0.5">
                                {{ $user && $user->category === 'landlord'
                                    ? 'Review and manage student booking requests for your properties.'
                                    : 'Track confirmations and updates for your property bookings.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Landlord: Pending Requests --}}
                    @if($user && $user->category === 'landlord')
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#6a6c6a] dark:text-[#aabdd2]">Pending requests</h4>

                            <div class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                                @forelse($pendingBookings as $booking)
                                    <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                                        <div>
                                            <div class="text-[10px] font-semibold uppercase tracking-wider text-[#6a6c6a] dark:text-[#aabdd2] mb-1">
                                                Request #{{ $booking->id }}
                                            </div>
                                            <div class="text-xs text-[#2e2f2c] dark:text-[#cad8e5] space-y-1">
                                                <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Student:</span> {{ $booking->student->name }}</div>
                                                <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Property:</span> {{ $booking->property->building_name }}</div>
                                                <div class="flex items-center gap-1.5 pt-1">
                                                    <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Status:</span>
                                                    @if($booking->status === 'pending')
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending</span>
                                                    @elseif($booking->status === 'approved')
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Approved</span>
                                                    @else
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Rejected</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($booking->status === 'pending')
                                            <div class="flex flex-wrap gap-2 pt-1">
                                                <form method="POST" action="{{ route('landlord.bookings.approve', $booking->id) }}">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold transition shadow-sm">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('landlord.bookings.reject', $booking->id) }}">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] text-[#2e2f2c] dark:text-white text-xs font-semibold transition">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">No pending booking requests at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Landlord: Current Tenants --}}
                        <div class="space-y-4 pt-6 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#6a6c6a] dark:text-[#aabdd2]">Your tenants</h4>

                            <div class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                                @forelse($approvedBookings as $booking)
                                    <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                                        <div>
                                            <div class="text-[10px] font-semibold uppercase tracking-wider text-[#6a6c6a] dark:text-[#aabdd2] mb-1">
                                                Tenant #{{ $booking->student->id }} • Booking #{{ $booking->id }}
                                            </div>
                                            <div class="text-xs text-[#2e2f2c] dark:text-[#cad8e5] space-y-1">
                                                <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Name:</span> {{ $booking->student->name }}</div>

                                                @if(!empty($booking->student->email))
                                                    <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Email:</span> {{ $booking->student->email }}</div>
                                                @endif

                                                @if(!empty($booking->student->phone))
                                                    <div>
                                                        <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Phone:</span>
                                                        <a href="tel:{{ $booking->student->phone }}" class="underline font-bold text-[#2e2f2c] dark:text-white">{{ $booking->student->phone }}</a>
                                                    </div>
                                                @endif

                                                <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Property:</span> {{ $booking->property->building_name }}</div>
                                                <div><span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2]">Since:</span> {{ $booking->created_at->format('d M Y') }}</div>

                                                <div class="pt-1">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                        Active tenant
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2 pt-1">
                                            <a href="{{ route('landlord.properties.show', $booking->property->id) }}"
                                               class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] text-[#2e2f2c] dark:text-white text-xs font-semibold transition">
                                                View property
                                            </a>

                                            <a href="{{ route('landlord.bookings.show', $booking->id) }}"
                                               class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold transition shadow-sm">
                                                View booking
                                            </a>

                                            <form action="{{ route('landlord.bookings.cancelStudent', ['booking' => $booking->id]) }}"
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to cancel this tenant?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold transition shadow-sm">
                                                    Cancel tenant
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">You have no active tenants yet. Approved bookings will appear here.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>