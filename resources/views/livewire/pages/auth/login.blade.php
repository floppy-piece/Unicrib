<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {$this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Welcome back</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1">Sign in to manage your bookings and properties.</p>
    </div>

    <x-auth-session-status class="mb-4 text-xs" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full rounded-xl border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0] text-xs shadow-sm" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#2e2f2c] dark:text-[#cad8e5] hover:text-[#6a6c6a] font-medium transition" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full rounded-xl border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0] text-xs shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] focus:ring-[#8da5c0] w-4 h-4 shadow-sm" name="remember">
                <span class="ms-2 text-xs text-[#6a6c6a] dark:text-[#cad8e5]">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled" class="w-full inline-flex items-center justify-center gap-2 bg-[#cad8e5] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold py-3 px-4 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="login">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Log in') }}</span>
            </button>
        </div>

        <div class="text-center pt-3 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                Don't have an account? 
                <a href="{{ route('register') }}" wire:navigate class="text-[#2e2f2c] dark:text-white font-semibold hover:underline">Sign up</a>
            </p>
        </div>
    </form>
</div>