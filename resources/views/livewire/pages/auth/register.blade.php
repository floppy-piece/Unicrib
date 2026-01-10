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
    'category'=>'',
    'name' => '',
    'email' => '',
    'phone'=>'',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    'category' => ['required', 'string', 'in:student,landlord,admin'],
    'phone'=> ['required','string','max:13','unique:'.User::class],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <form wire:submit="register">
        <!--category-->
        
        <div>
        <x-input-label for="catogory" :value="__('Category')" />
        <select wire:model="category" name="category" id="category" style="width:100%; background-color:gray;border-radius:5px;text-align:left;height:30px;align-itens:center;" required>
            <option value="">Select category</option>
            <option value="student">Student</option>
            <option value="landlord">Landlord</option>
            
        </select>
        <x-input-error :messages="$errors->get('category')"/>
            
        </div>
        <br>
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" place-holder="select"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
<br>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

<br>
        <!-- Phone number-->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Phone number')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

  <br>      <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
<br>
        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
<br>
        <div>
            <a style="all:unset;display:block;text-decoration:underline;color:white;cursor:pointer;" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>
<br>
            <x-primary-button style="display:block;">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
