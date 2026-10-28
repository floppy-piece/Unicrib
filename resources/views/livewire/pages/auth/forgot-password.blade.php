<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {$this->validate();

    $status = Password::sendResetLink($this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Forgot password</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1">Enter your account email and we'll send you a password reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-300 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white underline transition" href="{{ route('login') }}" wire:navigate>
                {{ __('Back to login') }}
            </a>

            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 bg-[#cad8e5] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold py-2.5 px-4 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="sendPasswordResetLink">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Send Reset Link') }}</span>
            </button>
        </div>
    </form>
</div>