{{-- resources/views/payments/storage-pay.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-[#2e2f2c] dark:text-white">
            {{ __('Storage Booking Initial Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
                <div>
                    <h1 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Pay 1st Month Storage Fee</h1>
                    <p class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] mt-1">
                        Booking ID: #{{ $booking->id }} | Total Duration: {{ $booking->months }} month(s)
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] space-y-2 text-xs">
                    <p class="font-bold text-[#2e2f2c] dark:text-white">Payment breakdown:</p>
                    <div class="flex justify-between text-[#6a6c6a] dark:text-[#cad8e5]">
                        <span>First Month Upfront (Due Now):</span>
                        <span class="font-bold text-[#2e2f2c] dark:text-[#aabdd2]">KSh {{ number_format($upfrontAmount) }}</span>
                    </div>
                    <div class="flex justify-between text-[#6a6c6a] dark:text-[#cad8e5]">
                        <span>Remaining Balance (Due Later):</span>
                        <span>KSh {{ number_format($booking->amount - $upfrontAmount) }}</span>
                    </div>
                    <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2] pt-1">
                        Your items can be stored once this first-month fee is confirmed. Subsequent months are paid at the end of each billing cycle.
                    </p>
                </div>

                <form method="POST" action="{{ route('payments.storage.pay', $booking->id) }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] shadow-sm transition">
                        Pay KSh {{ number_format($upfrontAmount) }} via Paystack
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>