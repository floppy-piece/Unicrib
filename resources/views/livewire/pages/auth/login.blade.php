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

<div class="w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
    
    
    <x-auth-session-status class="mb-4 text-sm" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-slate-700" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-rose-600" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-500 font-medium transition" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-rose-600" />
        </div>

        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 shadow-sm" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold py-3 px-4 rounded-xl transition shadow-sm">
                {{ __('Log in') }}
            </button>
        </div>

        <div class="text-center pt-2 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Don't have an account? 
                <a href="{{ route('register') }}" wire:navigate class="text-indigo-600 font-semibold hover:underline">Sign up</a>
            </p>
        </div>
    </form>
</div>