{{-- resources/views/payments/landlord-pay.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">
            {{ __('Landlord Verification Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 space-y-6">
                    
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Verify your property as a landlord</h1>
                        <p class="text-xs font-medium text-slate-500 mt-1">
                            Pay once per semester to unlock property uploads and verification.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 space-y-2">
                        <h3 class="text-sm font-bold text-slate-900">Pay KES 500 per semester</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            This payment verifies your landlord account for one semester and allows you to upload and manage verified properties for students.
                        </p>
                    </div>

                    @if(session('error'))
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pay') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="amount" class="block text-xs font-medium text-slate-700 mb-1">Amount (KES)</label>
                            <input type="number" name="amount" id="amount" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 @error('amount') border-rose-500 @enderror" placeholder="Amount (ksh 500)" min="500" required>
                            @error('amount')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition">
                                Pay & Verify
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>