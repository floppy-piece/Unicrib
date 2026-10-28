<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function Livewire\Volt\state;

state([
    'phone' => fn () => auth()->user()->phone,
]);

$linkWhatsApp = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'phone' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
    ]);

    $user->update([
        'phone' => $validated['phone'],
    ]);

    $this->dispatch('whatsapp-linked');
};

?>

<section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl transition-colors duration-200">
    <header class="flex items-start justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span class="text-emerald-600 text-lg">💬</span> {{ __('Connect to Unicrib WhatsApp AI') }}
            </h2>

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ __("Link your phone number to your Unicrib account. This connects your WhatsApp number as your secure authorization for chatting with Unicrib AI, searching housing, managing storage, and inspecting property photos.") }}
            </p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ auth()->user()->phone ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50' }}">
            {{ auth()->user()->phone ? 'Linked ✅' : 'Not Connected ⚠️' }}
        </span>
    </header>

    <form wire:submit="linkWhatsApp" class="mt-6 space-y-4">
        <div>
            <x-input-label for="whatsapp_phone" :value="__('WhatsApp Phone Number (e.g. 254712345678)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="phone" id="whatsapp_phone" name="phone" type="tel" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200" required placeholder="254712345678" />
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <x-primary-button target="linkWhatsApp" class="bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-500">
                {{ __('Link WhatsApp Account') }}
            </x-primary-button>

            @if (auth()->user()->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', env('WHATSAPP_DISPLAY_NUMBER', '15551793347')) }}?text={{ urlencode('Hi Unicrib, my account is linked and verified!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 border border-emerald-600 rounded-xl text-xs font-semibold text-white hover:bg-emerald-700 shadow-sm transition">
                    <span>💬 Open WhatsApp Bot</span>
                </a>
            @endif

            <x-action-message class="me-3 text-xs font-medium text-emerald-600 dark:text-emerald-400" on="whatsapp-linked">
                {{ __('WhatsApp linked successfully!') }}
            </x-action-message>
        </div>
    </form>
</section>