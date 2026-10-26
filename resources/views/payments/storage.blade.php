<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ __('Storage Booking Initial Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100">Pay 1st Month Storage Fee</h1>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">
                        Booking ID: #{{ $booking->id }} | Total Duration: {{ $booking->months }} month(s)
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-slate-800/60 border border-indigo-100 dark:border-slate-700 space-y-2 text-xs">
                    <p class="font-bold text-slate-900 dark:text-slate-100">Payment breakdown:</p>
                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                        <span>First Month Upfront (Due Now):</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">KSh {{ number_format($upfrontAmount) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                        <span>Remaining Balance (Due Later):</span>
                        <span>KSh {{ number_format($booking->amount - $upfrontAmount) }}</span>
                    </div>
                    <p class="text-[11px] text-slate-400 pt-1">
                        Your items can be stored once this first-month fee is confirmed. Subsequent months are paid at the end of each billing cycle.
                    </p>
                </div>

                <form method="POST" action="{{ route('payments.storage.pay', $booking->id) }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 transition">
                        Pay KSh {{ number_format($upfrontAmount) }} via Paystack
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>