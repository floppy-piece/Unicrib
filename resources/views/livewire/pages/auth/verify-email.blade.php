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

<div class="w-full max-w-md mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Verify email</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
            Please verify your email address via the link we emailed to you. If you didn't receive it, we can resend another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-300 font-medium">
            {{ __('A new verification link has been sent to your registered email address.') }}
        </div>
    @endif

    <div class="pt-2 flex items-center justify-between gap-4">
        <button wire:click="sendVerification" type="button" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition shadow-sm">
            {{ __('Resend Email') }}
        </button>

        <button wire:click="logout" type="button" class="text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 underline transition">
            {{ __('Log Out') }}
        </button>
    </div>
</div>