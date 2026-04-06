
@props(['active' => false])

@php
$classes = $active
    ? 'nav-link-active'
    : 'nav-link-inactive';
@endphp

<style>
    .nav-link-base {
        display: inline-flex;
        align-items: center;
        padding: 0 0.25rem;
        padding-top: 0.25rem;
        border-bottom: 2px solid;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25rem;
        transition: all 150ms cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        text-decoration: none;
    }
    
    .nav-link-active {
        composes: nav-link-base;
        border-bottom-color: #818cf8;
        color: #111827;
    }
    
    .dark .nav-link-active {
        border-bottom-color: #4f46e5;
        color: #f3f4f6;
    }
    
    .nav-link-active:focus {
        border-bottom-color: #4338ca;
    }
    
    .nav-link-inactive {
        composes: nav-link-base;
        border-bottom-color: transparent;
        color: #6b7280;
    }
    
    .dark .nav-link-inactive {
        color: #9ca3af;
    }
    
    .nav-link-inactive:hover {
        color: #374151;
        border-bottom-color: #d1d5db;
    }
    
    .dark .nav-link-inactive:hover {
        color: #d1d5db;
        border-bottom-color: #4b5563;
    }
    
    .nav-link-inactive:focus {
        color: #374151;
        border-bottom-color: #d1d5db;
    }
    
    .dark .nav-link-inactive:focus {
        color: #d1d5db;
        border-bottom-color: #4b5563;
    }
</style>

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>