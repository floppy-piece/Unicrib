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

<div>
    
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" style="margin-left:auto;margin-right:auto;padding:5px;">
        
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>
<br>
       
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <span id="toggle-password">👁️</span>

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>
<br>
        
        <div class="block mt-4">
            <label for="remember" style="display:inline-flex;align-items:center;" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" style=" accent-color: #8b5cf6; width: 20px;height: 20px;" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>
<br>
        <div>
            @if (Route::has('password.request'))
                <a style="all:unset;display:block;text-decoration:underline;cursor:pointer;color:white;text-align:center;" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
<br>
    
            <x-primary-button >
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
