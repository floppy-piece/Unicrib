{{-- resources/views/payments/student-pay.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ __('Student Access Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 space-y-6">
                    
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100">Unlock full property details</h1>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">
                            One-time access fee that covers all properties for 6 hours.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-slate-800/60 border border-indigo-100 dark:border-slate-700/80 space-y-2">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Pay KES 50 to view property details</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            This payment unlocks full property details, landlord contacts, and maps for 6 hours on your account.
                        </p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">
                            You will be redirected back to dashboard after payment to continue browsing.
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
                            <label for="amount" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Amount (KES)</label>
                            <input type="number" name="amount" id="amount" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 @error('amount') border-rose-500 dark:border-rose-500 @enderror" placeholder="Amount (e.g. 50)" min="50" required>
                            @error('amount')
                                <p class="text-[11px] text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-500 transition">
                                Pay & Verify
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>