{{-- resources/views/landlord/bookings/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                    Booking #{{ $booking->id }}
                </h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Tenant and property details for this booking.
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

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6 transition-colors duration-200">
                
                {{-- Tenant Details Section --}}
                <div class="space-y-3">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">
                        Tenant details
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Name</span>
                            <span class="text-[#2e2f2c] dark:text-white font-medium text-sm">{{ $booking->student->name }}</span>
                        </div>

                        @if(!empty($booking->student->email))
                            <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                                <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Email</span>
                                <span class="text-[#2e2f2c] dark:text-[#cad8e5] font-medium text-sm">{{ $booking->student->email }}</span>
                            </div>
                        @endif

                        @if(!empty($booking->student->phone))
                            <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1 sm:col-span-2">
                                <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Phone</span>
                                <a href="tel:{{ $booking->student->phone }}" class="text-[#2e2f2c] dark:text-white hover:underline font-bold text-sm inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $booking->student->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Property Details Section --}}
                <div class="space-y-3 pt-2">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">
                        Property
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Title</span>
                            <span class="text-[#2e2f2c] dark:text-white font-medium text-sm">{{ $booking->property->title }}</span>
                        </div>
                        <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Price</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">KES {{ number_format($booking->property->price) }} / month</span>
                        </div>
                    </div>
                </div>

                {{-- Booking Info Section --}}
                <div class="space-y-3 pt-2">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">
                        Booking info
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1.5 flex flex-col justify-center">
                            <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Status</span>
                            <div>
                                @php
                                    $status = $booking->status;
                                    $statusClass = match ($status) {
                                        'approved' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                        'rejected' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                        default    => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-[#6a6c6a] dark:text-[#aabdd2] block">Created at</span>
                            <span class="text-[#2e2f2c] dark:text-[#cad8e5] font-mono text-xs font-medium">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Back Navigation --}}
                <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a] flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition gap-1">
                        &larr; Back
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>