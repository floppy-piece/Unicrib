<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;

layout('layouts.guest');

$sendVerification = function () {
    if (Auth::user()->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

        return;
    }

    Auth::user()->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Verify your email address</h2>
        <p class="text-xs text-slate-500 mt-1">Thanks for signing up! Please verify your email via the link we just sent.</p>
    </div>

    <div class="mb-4 text-xs text-slate-600 leading-relaxed">
        {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 font-medium">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="pt-2 flex items-center justify-between">
        <x-primary-button wire:click="sendVerification">
            {{ __('Resend Verification Email') }}
        </x-primary-button>

        <button wire:click="logout" type="button" class="text-xs font-medium text-slate-600 hover:text-slate-900 underline transition">
            {{ __('Log Out') }}
        </button>
    </div>
</div>