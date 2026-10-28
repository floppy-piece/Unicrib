<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state([
    'current_password' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'current_password' => ['required', 'string', 'current_password'],
    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
]);

$updatePassword = function () {
    try {
        $validated = $this->validate();
    } catch (ValidationException $e) {
        $this->reset('current_password', 'password', 'password_confirmation');

        throw $e;
    }

    Auth::user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    $this->reset('current_password', 'password', 'password_confirmation');

    $this->dispatch('password-updated');
};

?>

<section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl transition-colors duration-200">
    <header>
        <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-4">
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input
                wire:model="current_password"
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input
                wire:model="password"
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2 flex items-center gap-4">
            <x-primary-button target="updatePassword">{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3 text-xs text-emerald-600 dark:text-emerald-400 font-medium" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>