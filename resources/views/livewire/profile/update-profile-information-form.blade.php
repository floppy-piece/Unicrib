{{-- resources/views/livewire/profile/update-profile-information-form.blade.php --}}
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
        'phone' => ['required', 'string', 'max:13', Rule::unique(User::class)->ignore($user->id)]
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

<section class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl transition-colors duration-200">
    <header>
        <h2 class="text-sm font-bold text-[#2e2f2c] dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-4">
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full dark:bg-[#2e2f2c] dark:border-[#6a6c6a] dark:text-white" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full dark:bg-[#2e2f2c] dark:border-[#6a6c6a] dark:text-white" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                        {{ __('Your email address is unverified.') }}

                        <button
                            wire:click.prevent="sendVerification"
                            class="text-xs font-medium text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] underline transition ml-1"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="tel" class="mt-1 block w-full dark:bg-[#2e2f2c] dark:border-[#6a6c6a] dark:text-white" required autocomplete="phone" />
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="pt-2 flex items-center gap-4">
            <x-primary-button target="updateProfileInformation">{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3 text-xs text-emerald-600 dark:text-emerald-400 font-medium" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>