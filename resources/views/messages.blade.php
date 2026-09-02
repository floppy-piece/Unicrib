{{-- resources/views/landlord/tenants.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ __('Tenants') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
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

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 sm:p-8 space-y-6">
                    @php $user = auth()->user(); @endphp

                    {{-- Page Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ $user && $user->category === 'landlord' ? 'Booking Requests' : 'Booking Updates' }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ $user && $user->category === 'landlord'
                                    ? 'Review and manage student booking requests for your properties.'
                                    : 'Track confirmations and updates for your property bookings.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Landlord: Pending Requests --}}
                    @if($user && $user->category === 'landlord')
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Pending requests</h4>

                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($pendingBookings as $booking)
                                    <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                                        <div>
                                            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">
                                                Request #{{ $booking->id }}
                                            </div>
                                            <div class="text-xs text-slate-700 dark:text-slate-300 space-y-1">
                                                <div><span class="font-medium text-slate-400 dark:text-slate-500">Student:</span> {{ $booking->student->name }}</div>
                                                <div><span class="font-medium text-slate-400 dark:text-slate-500">Property:</span> {{ $booking->property->building_name }}</div>
                                                <div class="flex items-center gap-1.5 pt-1">
                                                    <span class="font-medium text-slate-400 dark:text-slate-500">Status:</span>
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
                                                    <button type="submit" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('landlord.bookings.reject', $booking->id) }}">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-xs text-slate-500 dark:text-slate-400">No pending booking requests at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Landlord: Current Tenants --}}
                        <div class="space-y-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Your tenants</h4>

                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($approvedBookings as $booking)
                                    <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                                        <div>
                                            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">
                                                Tenant #{{ $booking->student->id }} • Booking #{{ $booking->id }}
                                            </div>
                                            <div class="text-xs text-slate-700 dark:text-slate-300 space-y-1">
                                                <div><span class="font-medium text-slate-400 dark:text-slate-500">Name:</span> {{ $booking->student->name }}</div>

                                                @if(!empty($booking->student->email))
                                                    <div><span class="font-medium text-slate-400 dark:text-slate-500">Email:</span> {{ $booking->student->email }}</div>
                                                @endif

                                                @if(!empty($booking->student->phone))
                                                    <div>
                                                        <span class="font-medium text-slate-400 dark:text-slate-500">Phone:</span>
                                                        <a href="tel:{{ $booking->student->phone }}" class="underline font-bold text-slate-900 dark:text-slate-100">{{ $booking->student->phone }}</a>
                                                    </div>
                                                @endif

                                                <div><span class="font-medium text-slate-400 dark:text-slate-500">Property:</span> {{ $booking->property->building_name }}</div>
                                                <div><span class="font-medium text-slate-400 dark:text-slate-500">Since:</span> {{ $booking->created_at->format('d M Y') }}</div>

                                                <div class="pt-1">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                        Active tenant
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2 pt-1">
                                            <a href="{{ route('landlord.properties.show', $booking->property->id) }}"
                                               class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
                                                View property
                                            </a>

                                            <a href="{{ route('landlord.bookings.show', $booking->id) }}"
                                               class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
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
                                        <p class="text-xs text-slate-500 dark:text-slate-400">You have no active tenants yet. Approved bookings will appear here.</p>
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