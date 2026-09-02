{{-- resources/views/store-housing.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ __('Store Housing') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Book secure storage for your belongings during holidays at our store house for KSh {{ $monthlyPrice }} per month.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Verification Status Pill --}}
            @if(auth()->user()->category === 'student')
                <div>
                    @if(auth()->user()->hasValidStudentAccess())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50 shadow-sm">
                            Your student account is verified. You can book storage.
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50 shadow-sm">
                            Your account is not fully verified. Some services may be limited.
                        </span>
                    @endif
                </div>
            @endif

            {{-- Booking Form Card --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 transition-colors duration-200">
                {{-- Messages --}}
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-xs text-rose-800 dark:text-rose-300 font-medium">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Book storage space
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Choose your start date and the number of months. You will be charged KSh {{ $monthlyPrice }} per month.
                    </p>
                </div>

                <form action="{{ route('storage.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Start date</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400"
                                required
                            >
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                                The day you drop your belongings at the storage facility.
                            </p>
                        </div>

                        <div>
                            <label for="months" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Months (1–12)</label>
                            <input
                                type="number"
                                id="months"
                                name="months"
                                min="1"
                                max="12"
                                value="{{ old('months', 1) }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400"
                                required
                            >
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                                Storage duration. Total cost is months × KSh {{ $monthlyPrice }}.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 pb-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                        <span class="text-slate-600 dark:text-slate-400 font-medium">Estimated total:</span>
                        <span id="storageTotal" class="font-bold text-slate-900 dark:text-slate-100 text-sm">KSh {{ $monthlyPrice }}</span>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 dark:hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
                        Proceed to payment
                    </button>
                </form>
            </div>

            {{-- Existing Storage Bookings Card --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 transition-colors duration-200">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Your storage bookings
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Track active and past storage periods linked to your {{ config('app.name') }} account.
                    </p>
                </div>

                @if($bookings->isEmpty())
                    <p class="text-xs text-slate-500 dark:text-slate-400 py-4 text-center">
                        You have not booked any storage yet. Use the form above to create your first booking.
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                        @foreach($bookings as $booking)
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40 space-y-2 hover:border-indigo-300 dark:hover:border-indigo-500 transition shadow-xs">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">
                                    Storage #{{ $booking->id }}
                                </h4>
                                <div class="text-xs text-slate-600 dark:text-slate-300 space-y-1">
                                    <p>{{ $booking->start_date }} – {{ $booking->end_date }}</p>
                                    <p>Duration: <span class="font-medium text-slate-900 dark:text-slate-100">{{ $booking->months }} month(s)</span></p>
                                    <p>Total: <span class="font-bold text-emerald-600 dark:text-emerald-400">KSh {{ number_format($booking->amount) }}</span></p>
                                </div>
                                <div class="pt-2 flex items-center justify-between border-t border-slate-200/60 dark:border-slate-700/80 text-[11px]">
                                    <span class="inline-flex px-2 py-0.5 rounded-full font-semibold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if($booking->reference)
                                        <span class="text-slate-400 dark:text-slate-500 truncate max-w-[140px]" title="{{ $booking->reference }}">
                                            Ref: {{ $booking->reference }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        (function () {
            const monthly = {{ $monthlyPrice }};
            const monthsInput = document.getElementById('months');
            const totalSpan = document.getElementById('storageTotal');

            if (monthsInput && totalSpan) {
                function updateTotal() {
                    const m = parseInt(monthsInput.value || '1', 10);
                    const total = m * monthly;
                    totalSpan.textContent = 'KSh ' + total.toLocaleString();
                }

                monthsInput.addEventListener('input', updateTotal);
                updateTotal();
            }
        })();
    </script>
</x-app-layout>