<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use function Livewire\Volt\state;

state([
    'name' => fn () => auth()->user()->name,
    'email' => fn () => auth()->user()->email,
    'phone' => fn () => auth()->user()->phone,
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'phone' => ['required', 'string', 'max:13',Rule::unique(User::class)->ignore($user->id)]
    ]);

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    $this->dispatch('profile-updated', name: $user->name);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section>
    <style>
        .uc-profile-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 16px 18px 18px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            max-width: 640px;
        }
        .uc-profile-header-title {
            font-size: 16px;
            font-weight: 600;
            color: #f9fafb;
            margin: 0;
        }
        .uc-profile-header-text {
            margin-top: 4px;
            font-size: 13px;
            color: #9ca3af;
        }
        .uc-profile-form {
            margin-top: 16px;
        }
        .uc-profile-field {
            margin-bottom: 16px;
        }
        .uc-profile-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
        }
        .uc-profile-unverified {
            margin-top: 6px;
            font-size: 13px;
            color: #e5e7eb;
        }
        .uc-profile-verify-btn {
            border: none;
            background: none;
            padding: 0;
            margin-left: 4px;
            font-size: 13px;
            text-decoration: underline;
            color: #9ca3af;
            cursor: pointer;
        }
        .uc-profile-verify-btn:hover {
            color: #e5e7eb;
        }
        .uc-profile-success {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #6ee7b7;
        }
    </style>

    <div class="uc-profile-card">
        <header>
            <h2 class="uc-profile-header-title">
                {{ __('Profile Information') }}
            </h2>

            <p class="uc-profile-header-text">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </header>

        <form wire:submit="updateProfileInformation" class="uc-profile-form">
            <div class="uc-profile-field">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="uc-profile-field">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div>
                        <p class="uc-profile-unverified">
                            {{ __('Your email address is unverified.') }}

                            <button
                                wire:click.prevent="sendVerification"
                                class="uc-profile-verify-btn"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="uc-profile-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="uc-profile-field">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input wire:model="phone" id="phone" name="phone" type="tel" class="mt-1 block w-full" required autofocus autocomplete="phone" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div class="uc-profile-actions">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
</section>
