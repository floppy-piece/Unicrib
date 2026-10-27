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

<section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl">
    <header class="flex items-start justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <span class="text-emerald-600 text-lg">💬</span> {{ __('Connect to Unicrib WhatsApp AI') }}
            </h2>

            <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                {{ __("Link your phone number to your Unicrib account. This connects your WhatsApp number as your secure authorization for chatting with Unicrib AI, searching housing, managing storage, and inspecting property photos.") }}
            </p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ auth()->user()->phone ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
            {{ auth()->user()->phone ? 'Linked ✅' : 'Not Connected ⚠️' }}
        </span>
    </header>

    <form wire:submit="linkWhatsApp" class="mt-6 space-y-4">
        <div>
            <x-input-label for="whatsapp_phone" :value="__('WhatsApp Phone Number (e.g. 254712345678)')" />
            <x-text-input wire:model="phone" id="whatsapp_phone" name="phone" type="tel" class="mt-1 block w-full" required placeholder="254712345678" />
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800">
                {{ __('Link WhatsApp Account') }}
            </x-primary-button>

            @if (auth()->user()->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', env('WHATSAPP_DISPLAY_NUMBER', '15551793347')) }}?text={{ urlencode('Hi Unicrib, my account is linked and verified!') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 border border-emerald-600 rounded-xl text-xs font-semibold text-white hover:bg-emerald-700 shadow-sm transition">
                    <span>💬 Open WhatsApp Bot</span>
                </a>
            @endif

            <x-action-message class="me-3 text-xs font-medium text-emerald-600" on="whatsapp-linked">
                {{ __('WhatsApp linked successfully!') }}
            </x-action-message>
        </div>
    </form>
</section>