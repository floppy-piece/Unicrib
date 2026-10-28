<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Forgot password</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Enter your account email and we'll send you a password reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-300 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a class="text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 underline transition" href="{{ route('login') }}" wire:navigate>
                {{ __('Back to login') }}
            </a>

            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="sendPasswordResetLink">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Send Reset Link') }}</span>
            </button>
        </div>
    </form>
</div>