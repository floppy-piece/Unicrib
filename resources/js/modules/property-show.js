import { showToast } from './toast.js';

// Inside your resources/js/modules/property-show.js

export function initPropertyShowPage() {
    // Make modal functions globally accessible for inline onclick handlers
    window.openPaymentModal = function () {
        const paymentModal = document.getElementById('payment-modal');
        if (paymentModal) {
            paymentModal.classList.remove('hidden');
            paymentModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function () {
        const paymentModal = document.getElementById('payment-modal');
        if (paymentModal) {
            paymentModal.classList.remove('flex');
            paymentModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // --- Your other module initialization code (Leaflet maps, countdowns, etc.) ---
    initToasts();
    initPropertyMap();
    initTransportModalAndCountdown();
    initPaymentModal();
}

/**
 * Handle session & flash toasts
 */
function initToasts() {
    const toast = document.getElementById('toast');
    if (!toast) return;

    if (toast.dataset.session) {
        try {
            const data = JSON.parse(toast.dataset.session);
            const isDark = document.documentElement.classList.contains('dark');
            const message = typeof data === 'string' ? data : (data.message || '');
            const isError = data.type === 'error';

            if (!message) return;

            toast.textContent = message;
            toast.className = `fixed top-4 right-4 px-4 py-3 rounded-xl border opacity-100 transition-opacity duration-200 z-50 text-xs shadow-lg ${isError
                ? isDark ? 'bg-rose-950 text-rose-200 border-rose-800' : 'bg-rose-900 text-white border-rose-700'
                : isDark ? 'bg-emerald-950 text-emerald-200 border-emerald-800' : 'bg-emerald-900 text-white border-emerald-700'
                }`;

            setTimeout(() => {
                toast.style.opacity = '0';
            }, 4000);
        } catch (e) {
            console.error('Error parsing toast session data:', e);
        }
    }
}

/**
 * Leaflet map & Geoapify route initializer
 */
function initPropertyMap() {
    const mapElement = document.getElementById('propertyMap');
    if (!mapElement || typeof L === 'undefined') return;

    const landmarkLat = -0.566519;
    const landmarkLng = 37.321184;

    const lat = parseFloat(mapElement.dataset.lat);
    const lng = parseFloat(mapElement.dataset.lng);
    const geoapifyKey = window.GEOAPIFY_API_KEY || '';

    if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
        mapElement.innerHTML = '<div class="flex items-center justify-center h-full text-xs text-slate-400">Location not provided</div>';
        return;
    }

    let map = L.map('propertyMap').setView([landmarkLat, landmarkLng], 15);
    let landmarkMarker = null;
    let propertyMarker = null;
    let routeLine = null;

    const landmarks = [
        { name: 'Skymart Supermarket', type: 'supermarket', lat: -0.567700, lng: 37.324526 },
        { name: 'Cooperative Bank', type: 'bank', lat: -0.5667250, lng: 37.324600 },
        { name: 'Rubis Station', type: 'petrol', lat: -0.5682000, lng: 37.3209166 },
        { name: 'Holy Rosary Catholic Church', type: 'church', lat: -0.567122, lng: 37.325788 },
        { name: 'Central Mosque', type: 'mosque', lat: -0.5630, lng: 37.3235 },
        { name: 'Hostel Building A', type: 'building', lat: -0.5660, lng: 37.3260 }
    ];

    function getLandmarkIcon(type) {
        let emoji = '📍';
        let bg = '#4b5563';
        switch (type) {
            case 'supermarket': emoji = '🛒'; bg = '#22c55e'; break;
            case 'bank': emoji = '🏦'; bg = '#0ea5e9'; break;
            case 'petrol': emoji = '⛽'; bg = '#f97316'; break;
            case 'shop': emoji = '🏬'; bg = '#6366f1'; break;
            case 'church': emoji = '⛪'; bg = '#a855f7'; break;
            case 'mosque': emoji = '🕌'; bg = '#10b981'; break;
            case 'building': emoji = '🏢'; bg = '#facc15'; break;
        }
        return { emoji, bg };
    }

    function createExtraLandmarks() {
        const isDark = document.documentElement.classList.contains('dark');
        const borderColor = isDark ? '#334155' : 'white';
        const textColor = isDark ? '#f8fafc' : 'black';

        landmarks.forEach(l => {
            const { emoji, bg } = getLandmarkIcon(l.type);
            L.marker([l.lat, l.lng], {
                icon: L.divIcon({
                    className: 'custom-marker-extra-landmark',
                    html: `<div style="background:${bg};color:${textColor};max-width:30px;max-height:30px;padding:4px 8px;border-radius:9999px;border:2px solid ${borderColor};display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:bold;gap:4px;text-align:center;font-size:10px;box-shadow:0 2px 5px rgba(0,0,0,0.3);white-space:nowrap;"><span>${emoji}</span><span style="font-size:8px;line-height:1;margin-top:2px;">${l.name}</span></div>`,
                    iconSize: [40, 24],
                    iconAnchor: [20, 24]
                })
            }).addTo(map).bindTooltip(`${l.name} (${l.type})`, { permanent: false, direction: 'top' });
        });
    }

    function createLandmarkMarker() {
        if (landmarkMarker) map.removeLayer(landmarkMarker);
        const isDark = document.documentElement.classList.contains('dark');
        const borderColor = isDark ? '#334155' : 'white';

        landmarkMarker = L.marker([landmarkLat, landmarkLng], {
            icon: L.divIcon({
                className: 'custom-marker-landmark',
                html: `<div style="background:#1d4ed8;color:white;min-width:40px;min-height:40px;padding:4px 8px;border-radius:9999px;border:3px solid ${borderColor};display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:bold;font-size:10px;box-shadow:0 2px 5px rgba(0,0,0,0.3);text-align:center;">🎓<span style="font-size:8px;line-height:1;margin-top:2px;">UNIVERSITY</span></div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(map);
    }

    function updatePropertyMarker(destLat, destLng) {
        if (propertyMarker) map.removeLayer(propertyMarker);
        const isDark = document.documentElement.classList.contains('dark');
        const borderColor = isDark ? '#334155' : 'white';

        propertyMarker = L.marker([destLat, destLng], {
            icon: L.divIcon({
                className: 'custom-marker-property',
                html: `<div style="background:#ef4444;color:white;width:35px;height:35px;border-radius:50%;border:3px solid ${borderColor};display:flex;align-items:center;justify-content:center;font-weight:bold;box-shadow:0 2px 5px rgba(0,0,0,0.3);">📍</div>`,
                iconSize: [35, 35],
                iconAnchor: [17.5, 35]
            })
        }).addTo(map);
    }

    async function fetchRouteFromLandmark(destLat, destLng) {
        const pathDisplay = document.getElementById('pathDisplay');
        const distanceDisplay = document.getElementById('distanceDisplay');

        try {
            if (pathDisplay) pathDisplay.textContent = 'Calculating route...';
            if (distanceDisplay) distanceDisplay.textContent = '–';

            if (!geoapifyKey) throw new Error('Geoapify key missing');

            const url = `https://api.geoapify.com/v1/routing?waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}&mode=drive&details=route_details&apiKey=${encodeURIComponent(geoapifyKey)}`;

            const res = await fetch(url);
            if (!res.ok) throw new Error('Routing request failed');
            const data = await res.json();

            if (!data.features || !data.features.length) throw new Error('No route found');

            const feature = data.features[0];
            const coords = feature.geometry.coordinates[0];
            const distanceKm = (feature.properties.distance / 1000).toFixed(2);

            if (routeLine) map.removeLayer(routeLine);

            const latLngs = coords.map(c => [c[1], c[0]]);
            routeLine = L.polyline(latLngs, { color: '#10b981', weight: 4, opacity: 0.8 }).addTo(map);

            map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });

            if (pathDisplay) pathDisplay.textContent = 'Walking route from university to this property.';
            if (distanceDisplay) distanceDisplay.textContent = `${distanceKm} km`;
        } catch (err) {
            console.error('Error fetching route:', err);
            if (pathDisplay) pathDisplay.textContent = 'Could not calculate route.';
            if (distanceDisplay) distanceDisplay.textContent = '–';
        }
    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    map.whenReady(() => {
        showToast('Map loaded successfully!');
        createLandmarkMarker();
        createExtraLandmarks();
        updatePropertyMarker(lat, lng);
        fetchRouteFromLandmark(lat, lng);
    });
}

/**
 * Handles transport request modal and countdown calculation
 */
function initTransportModalAndCountdown() {
    const openBtn = document.getElementById('openTransportModalButton');
    const modal = document.getElementById('transportRequestModal');
    const closeBtn = document.getElementById('closeTransportModalButton');
    const closeBtn2 = document.getElementById('closeTransportModalButton2');

    if (modal && openBtn) {
        const openModal = (e) => {
            if (e) e.preventDefault();
            if (openBtn.hasAttribute('disabled')) return; // Prevent opening if disabled
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };
        const closeModal = () => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        };

        openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (closeBtn2) closeBtn2.addEventListener('click', closeModal);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    }

    // Countdown logic
    const countdownEl = document.getElementById('transport-countdown');
    if (countdownEl) {
        const deadlineStr = countdownEl.getAttribute('data-deadline');
        if (deadlineStr) {
            const deadline = new Date(deadlineStr).getTime();

            const updateCountdown = () => {
                const now = new Date().getTime();
                const diff = deadline - now;

                if (diff <= 0) {
                    countdownEl.textContent = 'Transport time reached';
                    clearInterval(timer);
                    return;
                }

                const second = 1000;
                const minute = 60 * second;
                const hour = 60 * minute;

                const hours = Math.floor(diff / hour);
                const minutes = Math.floor((diff % hour) / minute);
                const seconds = Math.floor((diff % minute) / second);

                let parts = [];
                if (hours > 0) parts.push(hours + 'h');
                if (minutes >= 0) parts.push(minutes + 'm');
                parts.push(seconds + 's');

                countdownEl.textContent = parts.join(' ');
            };

            updateCountdown();
            const timer = setInterval(updateCountdown, 1000);
        }
    }
}

/**
 * Handles payment modal triggers
 */
/**
 * Handles payment modal triggers
 */
function initPaymentModal() {
    const paymentModal = document.getElementById('payment-modal');
    const openBtn = document.getElementById('pay-and-book-btn');

    // Also support closing via a close button or cancel button inside the modal if they use inline or specific triggers
    window.closeModal = function () {
        if (paymentModal) {
            paymentModal.classList.remove('flex');
            paymentModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    if (paymentModal && openBtn) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            paymentModal.classList.remove('hidden');
            paymentModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            window.closeModal();
        }
    });
}