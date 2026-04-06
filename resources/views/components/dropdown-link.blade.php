<a {{ $attributes->merge(['class' => 'uc-dd-link']) }}>
    {{ $slot }}
</a>

<style>
    .uc-dd-link {
        display: block;
        width: 100%;
        padding: 8px 12px;
        font-size: 13px;
        text-align: left;
        color: #e5e7eb;
        text-decoration: none;
        background-color: #020617;
        border-radius: 6px;
        cursor: pointer;
        transition:
            background-color .18s ease,
            color .18s ease,
            transform .1s ease;
    }
    .uc-dd-link:hover {
        background-color: rgba(129, 140, 248, 0.18);
        color: #a855f7;
        transform: translateX(2px);
    }
    .uc-dd-link:focus-visible {
        outline: 2px solid #4f46e5;
        outline-offset: 2px;
    }
</style>
