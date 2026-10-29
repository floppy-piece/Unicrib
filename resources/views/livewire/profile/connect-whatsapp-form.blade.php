{{-- resources/views/livewire/profile/whatsapp-link.blade.php --}}
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function Livewire\Volt\state;

state([
    'phone' => fn () => auth()->user()->phone,
    'isLinked' => fn () => !empty(auth()->user()->phone),
]);

$linkWhatsApp = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'phone' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
    ]);

    $user->update([
        'phone' => $validated['phone'],
    ]);

    $user->refresh();
    $this->phone = $user->phone;
    $this->isLinked = true;

    $this->dispatch('whatsapp-linked');
};

?>

<section class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl transition-colors duration-200">
    <header class="flex items-start justify-between">
        <div>
            <h2 class="text-sm font-bold text-[#2e2f2c] dark:text-white">
                {{ __('WhatsApp Account Integration') }}
            </h2>

            <p class="mt-1 text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                {{ __("Link your mobile number to authorize secure interaction with the Unicrib WhatsApp assistant for housing searches, storage management, and account updates.") }}
            </p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $isLinked ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50' }}">
            {{ $isLinked ? 'Linked' : 'Not Connected' }}
        </span>
    </header>

    <form wire:submit="linkWhatsApp" class="mt-6 space-y-4">
        <div>
            <x-input-label for="whatsapp_phone" :value="__('Phone Number')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="phone" id="whatsapp_phone" name="phone" type="tel" class="mt-1 block w-full dark:bg-[#2e2f2c] dark:border-[#6a6c6a] dark:text-white" required placeholder="254712345678" />
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <x-primary-button class="bg-[#2e2f2c] hover:bg-[#6a6c6a] focus:bg-[#2e2f2c] active:bg-[#2e2f2c] dark:bg-[#8da5c0] dark:text-[#2e2f2c] dark:hover:bg-[#aabdd2]">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if ($isLinked)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', env('WHATSAPP_DISPLAY_NUMBER', '15551793347')) }}?text={{ urlencode('Hi Unicrib, my account is linked and verified.') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl text-xs font-semibold text-[#2e2f2c] dark:text-white hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]/50 shadow-sm transition">
                    <span>Open WhatsApp Assistant</span>
                </a>
            @endif

            <x-action-message class="me-3 text-xs font-medium text-emerald-600 dark:text-emerald-400" on="whatsapp-linked">
                {{ __('Successfully updated.') }}
            </x-action-message>
        </div>
    </form>
</section>