{{-- resources/views/components/secondary-button.blade.php --}}
@props(['target' => null])

<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150'
]) }}
@if($target) wire:loading.attr="disabled" wire:target="{{ $target }}" @endif
>
    @if($target)
        <span wire:loading wire:target="{{ $target }}">
            <x-spinner size="w-3.5 h-3.5" />
        </span>
    @endif

    <span>{{ $slot }}</span>
</button>