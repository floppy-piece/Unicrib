<style>
    .uc-header-links {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: 10px;
        width: max-content;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .uc-header-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        color: #e5e7eb;
        text-decoration: none;
        font-size: 13px;
        line-height: 1.3;
        opacity: 0.8;
        transition:
            background-color .18s ease,
            color .18s ease,
            opacity .18s ease,
            transform .12s ease,
            box-shadow .18s ease;
    }

    .uc-header-link img {
        height: 20px;
        width: 20px;
        border-radius: 999px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 0 0 1px #111827;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .uc-header-link span {
        white-space: nowrap;
    }

    .uc-header-link:hover {
        background-color: rgba(129, 140, 248, 0.14);
        color: #a855f7;
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.6);
    }

    .uc-header-link:hover img {
        transform: scale(1.05);
        box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.7);
    }

    .uc-header-link:focus-visible {
        outline: 2px solid #4f46e5;
        outline-offset: 2px;
    }

    .uc-header-link.uc-active {
        background: linear-gradient(90deg, #4f46e5, #8b5cf6);
        color: #f9fafb;
        opacity: 1;
        box-shadow: 0 10px 24px rgba(79, 70, 229, 0.55);
    }

    .uc-header-link.uc-active img {
        box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.9);
        transform: scale(1.03);
    }
</style>

<div class="uc-header-links">
    {{-- Home --}}
    <a href="{{ url('/') }}"
       class="uc-header-link {{ request()->is('/') ? 'uc-active' : '' }}">
        <img src="{{ asset('images/home.jpeg') }}" alt="Home">
        <span>Home</span>
    </a>

    {{-- Universities (adjust URL when you have a real route) 
    <a href="{{ url('/universities') }}"
       class="uc-header-link {{ request()->is('universities*') ? 'uc-active' : '' }}">
        <img src="{{ asset('images/property.jpeg') }}" alt="Properties">
        <span>Universities</span>
    </a> --}}

    {{-- About --}}
    <a href="{{ url('/about') }}"
       class="uc-header-link {{ request()->is('about') ? 'uc-active' : '' }}">
        <img src="{{ asset('images/university.jpeg') }}" alt="About Us">
        <span>About Us</span>
    </a>

    {{-- Services --}}
    <a href="{{ url('/services') }}"
       class="uc-header-link {{ request()->is('services') ? 'uc-active' : '' }}">
        <img src="{{ asset('images/services.jpeg') }}" alt="Services">
        <span>Services</span>
    </a>

    {{-- Contact --}}
    <a href="{{ url('/contact') }}"
       class="uc-header-link {{ request()->is('contact') ? 'uc-active' : '' }}">
        <img src="{{ asset('images/contact.jpeg') }}" alt="Contact">
        <span>Contact</span>
    </a>
</div>
