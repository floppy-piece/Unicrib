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
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#deddda" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-heart-icon lucide-house-heart"><path d="M8.62 13.8A2.25 2.25 0 1 1 12 10.836a2.25 2.25 0 1 1 3.38 2.966l-2.626 2.856a.998.998 0 0 1-1.507 0z"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
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
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#deddda" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-question-mark-icon lucide-shield-question-mark"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
        <span>About Us</span>
    </a>

    {{-- Services --}}
    <a href="{{ url('/services') }}"
       class="uc-header-link {{ request()->is('services') ? 'uc-active' : '' }}">
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#deddda" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
        <span>Services</span>
    </a>

    {{-- Contact --}}
    <a href="{{ url('/contact') }}"
       class="uc-header-link {{ request()->is('contact') ? 'uc-active' : '' }}">
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#deddda" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-icon lucide-phone"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
        <span>Contact</span>
    </a>
</div>
