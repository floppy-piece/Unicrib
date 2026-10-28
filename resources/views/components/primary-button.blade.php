{{-- resources/views/components/primary-button.blade.php --}}
@props(['target' => null])

<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition'
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