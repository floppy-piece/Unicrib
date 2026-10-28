<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'category' => '',
    'name' => '',
    'email' => '',
    'phone' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    'category' => ['required', 'string', 'in:student,landlord'],
    'phone' => ['required', 'string', 'max:13', 'unique:'.User::class],
]);

$register = function () {
    $validated =$this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('verification.notice', absolute: false), navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Create an account</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1">Select your account type to get started on UniCrib.</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Account Category -->
        <div>
            <x-input-label for="category" :value="__('I am a')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <select wire:model="category" name="category" id="category" class="w-full mt-1 rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3.5 py-2.5 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" required>
                <option value="" class="dark:bg-[#2e2f2c]">Select role</option>
                <option value="student" class="dark:bg-[#2e2f2c]">Student</option>
                <option value="landlord" class="dark:bg-[#2e2f2c]">Landlord / Property Manager</option>
            </select>
            <x-input-error :messages="$errors->get('category')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="text" name="name" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="email" name="email" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Phone number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" type="tel" name="phone" required autocomplete="phone" placeholder="07XXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
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

        <div class="pt-2 flex items-center justify-between">
            <a class="text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] hover:text-[#6a6c6a] dark:hover:text-white underline transition" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 bg-[#cad8e5] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold py-2.5 px-5 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="register">
                    <x-spinner size="w-3.5 h-3.5" />
                </span>
                <span>{{ __('Register') }}</span>
            </button>
        </div>
    </form>
</div>