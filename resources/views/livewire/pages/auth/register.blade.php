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
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('verification.notice', absolute: false), navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Create an account</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Select your account type to get started on UniCrib.</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Account Category -->
        <div>
            <x-input-label for="category" :value="__('I am a')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <select wire:model="category" name="category" id="category" class="w-full mt-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400" required>
                <option value="" class="dark:bg-slate-900">Select role</option>
                <option value="student" class="dark:bg-slate-900">Student</option>
                <option value="landlord" class="dark:bg-slate-900">Landlord / Property Manager</option>
            </select>
            <x-input-error :messages="$errors->get('category')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="text" name="name" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="email" name="email" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Phone number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="tel" name="phone" required autocomplete="phone" placeholder="07XXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600 dark:text-rose-400" />
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline transition" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 dark:hover:bg-indigo-500 text-white text-xs font-semibold py-2.5 px-5 rounded-xl transition shadow-sm">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</div>