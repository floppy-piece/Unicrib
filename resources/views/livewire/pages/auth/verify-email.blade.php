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

$logout = function (Logout $logout) {$logout();

    $this->redirect('/', navigate: true);
};

?>

<div class="w-full max-w-md mx-auto bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 transition-colors duration-200">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-[#2e2f2c] dark:text-white">Verify email</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1 leading-relaxed">
            Please verify your email address via the link we emailed to you. If you didn't receive it, we can resend another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-300 font-medium">
            {{ __('A new verification link has been sent to your registered email address.') }}
        </div>
    @endif

    <div class="pt-2 flex items-center justify-between gap-4">
        <button wire:click="sendVerification" wire:loading.attr="disabled" type="button" class="inline-flex items-center justify-center gap-2 bg-[#cad8e5] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold py-2.5 px-4 rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading wire:target="sendVerification">
                <x-spinner size="w-3.5 h-3.5" />
            </span>
            <span>{{ __('Resend Email') }}</span>
        </button>

        <button wire:click="logout" wire:loading.attr="disabled" type="button" class="inline-flex items-center gap-1.5 text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white underline transition disabled:opacity-50">
            <span wire:loading wire:target="logout">
                <x-spinner size="w-3.5 h-3.5" />
            </span>
            <span>{{ __('Log Out') }}</span>
        </button>
    </div>
</div>