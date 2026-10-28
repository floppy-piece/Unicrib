<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state('token')->locked();

state([
    'email' => fn () => request()->string('email')->value(),
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'token' => ['required'],
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$resetPassword = function () {$this->validate();

    $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) {$user->forceFill([
                'password' => Hash::make($this->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    if ($status != Password::PASSWORD_RESET) {
        $this->addError('email', __($status));

        return;
    }

    Session::flash('status', __($status));

    $this->redirectRoute('login', navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Reset password</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1">Please enter your new password below.</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="pt-2 flex items-center justify-end">
            <x-primary-button target="resetPassword" class="bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c]">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</div>