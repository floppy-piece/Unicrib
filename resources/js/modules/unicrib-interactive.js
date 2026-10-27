export function initUnicribInteractive() {
    // 1. Interactive 3D Card Tilt Effect
    const cards = document.querySelectorAll('.card-3d-hover');
    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xc = rect.width / 2;
            const yc = rect.height / 2;
            const dx = (x - xc) / xc;
            const dy = (y - yc) / yc;

            card.style.transform = `perspective(1000px) rotateY(${dx * 4}deg) rotateX(${-dy * 4}deg) translateY(-6px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(0px)';
        });
    });

    // 2. Dynamic Toast / Feedback Alert Popups for Authenticated Pages
    const flashMessages = document.querySelectorAll('.alert-flash, [data-flash-success]');
    flashMessages.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 5000);
    });

    // 3. Smooth active navigation enhancements & cognitive load reduction helpers
    const currentPath = window.location.pathname;
    document.querySelectorAll('nav a').forEach(link => {
        if (link.getAttribute('href') === window.location.href) {
            link.classList.add('text-indigo-600', 'font-bold');
        }
    });
}
