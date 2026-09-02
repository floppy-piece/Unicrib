{{-- resources/views/landlord/bookings/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    Booking #{{ $booking->id }}
                </h2>
                <p class="text-xs text-slate-500">
                    Tenant and property details for this booking.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
                
                {{-- Tenant Details Section --}}
                <div class="space-y-3">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
                        Tenant details
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Name</span>
                            <span class="text-slate-900 font-medium text-sm">{{ $booking->student->name }}</span>
                        </div>

                        @if(!empty($booking->student->email))
                            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                                <span class="text-[10px] uppercase font-semibold text-slate-400 block">Email</span>
                                <span class="text-slate-700 font-medium text-sm">{{ $booking->student->email }}</span>
                            </div>
                        @endif

                        @if(!empty($booking->student->phone))
                            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1 sm:col-span-2">
                                <span class="text-[10px] uppercase font-semibold text-slate-400 block">Phone</span>
                                <a href="tel:{{ $booking->student->phone }}" class="text-indigo-600 hover:underline font-bold text-sm inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $booking->student->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Property Details Section --}}
                <div class="space-y-3 pt-2">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
                        Property
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Title</span>
                            <span class="text-slate-900 font-medium text-sm">{{ $booking->property->title }}</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Price</span>
                            <span class="text-emerald-600 font-bold text-sm">KES {{ number_format($booking->property->price) }} / month</span>
                        </div>
                    </div>
                </div>

                {{-- Booking Info Section --}}
                <div class="space-y-3 pt-2">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
                        Booking info
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1.5 flex flex-col justify-center">
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Status</span>
                            <div>
                                @php
                                    $status = $booking->status;
                                    $statusClass = match ($status) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default    => 'bg-blue-50 text-blue-700 border-blue-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Created at</span>
                            <span class="text-slate-700 font-mono text-xs font-medium">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Back Navigation --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition gap-1">
                        &larr; Back
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>