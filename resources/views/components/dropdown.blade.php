@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700'])

@php
$alignmentClasses = match ($align) {
    'left' => 'uc-dd-align-left',
    'top' => 'uc-dd-align-top',
    default => 'uc-dd-align-right',
};

$width = match ($width) {
    '48' => 'uc-dd-w-48',
    default => $width,
};
@endphp

<style>
    .uc-dd-relative {
        position: relative;
        display: inline-block;
    }
    .uc-dd-panel {
        position: absolute;
        z-index: 50;
        margin-top: 0.5rem;
        border-radius: 0.375rem;
        box-shadow: 0 10px 25px rgba(15,23,42,0.7);
        transform-origin: top right;
    }
    .uc-dd-w-48 {
        width: 12rem;
    }
    .uc-dd-align-right {
        right: 0;
    }
    .uc-dd-align-left {
        left: 0;
    }
    .uc-dd-align-top {
        left: 50%;
        transform: translateX(-50%);
    }
    .uc-dd-inner {
        border-radius: 0.375rem;
        border: 1px solid rgba(15,23,42,0.8);
        background-color: #020617;
    }
</style>

<div class="uc-dd-relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="uc-dd-panel {{ $width }} {{ $alignmentClasses }}"
         style="display: none;"
         @click="open = false">
        <div class="uc-dd-inner {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
