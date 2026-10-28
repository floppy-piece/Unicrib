{{-- resources/views/livewire/profile/telegram-link.blade.php --}}
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\state;

state([
    'telegram_chat_id' => fn () => auth()->user()->telegram_chat_id,
    'isLinked' => fn () => !empty(auth()->user()->telegram_chat_id),
]);

$linkTelegram = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'telegram_chat_id' => ['required', 'string', 'max:50'],
    ]);

    $user->update([
        'telegram_chat_id' => $validated['telegram_chat_id'],
    ]);

    // Refresh the authenticated user instance in session/container
    $user->refresh();
    
    // Update local state variables
    $this->telegram_chat_id = $user->telegram_chat_id;
    $this->isLinked = true;

    $this->dispatch('telegram-linked');
};

?>

<section class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 sm:p-8 max-w-2xl transition-colors duration-200">
    <header class="flex items-start justify-between">
        <div>
            <h2 class="text-sm font-bold text-[#2e2f2c] dark:text-white flex items-center gap-2">
                <span class="text-sky-500 text-lg">🤖</span> {{ __('Connect to Unicrib Telegram AI') }}
            </h2>

            <p class="mt-1 text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                {{ __("Link your Telegram Chat ID to your Unicrib account. This enables seamless authorization for chatting with Unicrib AI, searching student housing, tracking transport, and managing storage directly via Telegram.") }}
            </p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $isLinked ? 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-900/50' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50' }}">
            {{ $isLinked ? 'Linked ✅' : 'Not Connected ⚠️' }}
        </span>
    </header>

    <form wire:submit="linkTelegram" class="mt-6 space-y-4">
        <div>
            <x-input-label for="telegram_chat_id" :value="__('Telegram Chat ID (e.g. 123456789)')" class="text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]" />
            <x-text-input wire:model="telegram_chat_id" id="telegram_chat_id" name="telegram_chat_id" type="text" class="mt-1 block w-full dark:bg-[#2e2f2c] dark:border-[#6a6c6a] dark:text-white" required placeholder="123456789" />
            <x-input-error class="mt-1" :messages="$errors->get('telegram_chat_id')" />
            <p class="mt-1 text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">
                Tip: You can find your Telegram Chat ID by messaging a bot like <span class="font-semibold text-[#2e2f2c] dark:text-white">@userinfobot</span> on Telegram.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <x-primary-button class="bg-sky-600 hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 dark:bg-sky-600 dark:hover:bg-sky-500">
                {{ __('Link Telegram Account') }}
            </x-primary-button>

            @if ($isLinked)
                <a href="https://t.me/{{ config('services.telegram.bot_username', env('TELEGRAM_BOT_USERNAME')) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-sky-600 rounded-xl text-xs font-semibold text-white hover:bg-sky-700 shadow-sm transition">
                    <span>🤖 Open Telegram Bot </span>
                </a>
            @endif

            <x-action-message class="me-3 text-xs font-medium text-sky-600 dark:text-sky-400" on="telegram-linked">
                {{ __('Telegram linked successfully!') }}
            </x-action-message>
        </div>
    </form>
</section>