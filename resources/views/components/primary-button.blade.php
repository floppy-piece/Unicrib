<style>
    .uc-btn-main {
        display: block;
        margin-left: auto;
        margin-right: auto;
        padding: 10px 24px;
        border-radius: 999px;
        border: 1px solid #4f46e5;
        background: linear-gradient(90deg, #6366f1 0%, #4f46e5 50%, #8b5cf6 100%);
        color: #f9fafb;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: .02em;
        cursor: pointer;
        box-shadow: 0 18px 40px rgba(79,70,229,0.55);
        transition:
            background-color .18s ease,
            box-shadow .18s ease,
            transform .12s ease,
            border-color .18s ease;
    }
    .uc-btn-main:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 50px rgba(79,70,229,0.7);
        border-color: #818cf8;
    }
    .uc-btn-main:active {
        transform: translateY(0);
        box-shadow: 0 14px 30px rgba(15,23,42,0.8);
        background: linear-gradient(90deg, #4f46e5 0%, #4338ca 50%, #7c3aed 100%);
    }
    .uc-btn-main:disabled {
        opacity: .65;
        cursor: not-allowed;
        box-shadow: none;
    }
</style>

<button {{ $attributes->merge(['type' => 'submit','class' =>'uc-btn-main']) }}>
    {{ $slot }}
</button>
