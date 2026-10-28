<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Welcome back</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sign in to manage your bookings and properties.</p>
    </div>

    <x-auth-session-status class="mb-4 text-xs" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 text-xs shadow-sm" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 font-medium transition" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 text-xs shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500 w-4 h-4 shadow-sm" name="remember">
                <span class="ms-2 text-xs text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 dark:hover:bg-indigo-500 text-white text-xs font-semibold py-3 px-4 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="login">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Log in') }}</span>
            </button>
        </div>

        <div class="text-center pt-3 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Sign up</a>
            </p>
        </div>
    </form>
</div>