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

<section>
    <style>
        .uc-pass-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 16px 18px 18px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            max-width: 640px;
            margin-top: 16px;
        }
        .uc-pass-header-title {
            font-size: 16px;
            font-weight: 600;
            color: #f9fafb;
            margin: 0;
        }
        .uc-pass-header-text {
            margin-top: 4px;
            font-size: 13px;
            color: #9ca3af;
        }
        .uc-pass-form {
            margin-top: 16px;
        }
        .uc-pass-field {
            margin-bottom: 16px;
        }
        .uc-pass-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
        }
    </style>

    <div class="uc-pass-card">
        <header>
            <h2 class="uc-pass-header-title">
                {{ __('Update Password') }}
            </h2>

            <p class="uc-pass-header-text">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </header>

        <form wire:submit="updatePassword" class="uc-pass-form">
            <div class="uc-pass-field">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input
                    wire:model="current_password"
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div class="uc-pass-field">
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input
                    wire:model="password"
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="uc-pass-field">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input
                    wire:model="password_confirmation"
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="uc-pass-actions">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
</section>
