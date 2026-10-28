{{-- resources/views/payments/landlord-pay.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-[#2e2f2c] dark:text-white">
            {{ __('Landlord Verification Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 space-y-6">
                    
                    <div>
                        <h1 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Verify your property as a landlord</h1>
                        <p class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] mt-1">
                            Pay once per semester to unlock property uploads and verification.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] space-y-2">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">Pay KES 500 per semester</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            This payment verifies your landlord account for one semester and allows you to upload and manage verified properties for students.
                        </p>
                    </div>

                    @if(session('error'))
                        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-xs text-rose-700 dark:text-rose-300 font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pay') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="amount" class="block text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] mb-1">Amount (KES)</label>
                            <input type="number" name="amount" id="amount" class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3 py-2 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0] @error('amount') border-rose-500 @enderror" placeholder="Amount (ksh 500)" min="500" required>
                            @error('amount')
                                <p class="text-[11px] text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] shadow-sm transition">
                                Pay &amp; Verify
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>