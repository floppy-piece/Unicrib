{{-- resources/views/livewire/auth/confirm-password.blade.php --}}
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['password' => '']);

rules(['password' => ['required', 'string']]);

$confirmPassword = function () {
    $this->validate();

    if (! Auth::guard('web')->validate([
        'email' => Auth::user()->email,
        'password' => $this->password,
    ])) {
        throw ValidationException::withMessages([
            'password' => __('auth.password'),
        ]);
    }

    session(['auth.password_confirmed_at' => time()]);

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Confirm password</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1 leading-relaxed">
            This is a secure action. Please confirm your password to proceed.
        </p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-4">
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="password"
                          id="password"
                          class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="pt-2 flex items-center justify-end">
            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 bg-[#cad8e5] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold py-2.5 px-5 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="confirmPassword">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Confirm') }}</span>
            </button>
        </div>
    </form>
</div>