import { showToast } from './toast.js';

export function initPropertyMap(options = {}) {
    const mapElement = document.getElementById('locationMap');
    if (!mapElement || typeof L === 'undefined') return null;

    const landmarkLat = -0.566519;
    const landmarkLng = 37.321184;

    const landmarks = [
        { name: 'Skymart Supermarket', type: 'supermarket', lat: -0.567700, lng: 37.324526 },
        { name: 'Cooperative Bank', type: 'bank', lat: -0.5667250, lng: 37.324600 },
        { name: 'Rubis Station', type: 'petrol', lat: -0.5682000, lng: 37.3209166 },
        { name: 'Holy Rosary Church', type: 'church', lat: -0.567122, lng: 37.325788 },
        { name: 'Central Mosque', type: 'mosque', lat: -0.5630, lng: 37.3235 },
        { name: 'Hostel Zone A', type: 'building', lat: -0.5660, lng: 37.3260 }
    ];

    let map = null;
    let landmarkMarker = null;
    let propertyMarker = null;
    let routeLayer = null;

    function getLandmarkIcon(type) {
        let emoji = '📍';
        let bg = '#4b5563';
        switch (type) {
            case 'supermarket': emoji = '🛒'; bg = '#16a34a'; break;
            case 'bank': emoji = '🏦'; bg = '#0284c7'; break;
            case 'petrol': emoji = '⛽'; bg = '#ea580c'; break;
            case 'church': emoji = '⛪'; bg = '#9333ea'; break;
            case 'mosque': emoji = '🕌'; bg = '#0d9488'; break;
            case 'building': emoji = '🏢'; bg = '#ca8a04'; break;
        }
        return { emoji, bg };
    }

    function createExtraLandmarks() {
        if (!map) return;
        landmarks.forEach(l => {
            const { emoji, bg } = getLandmarkIcon(l.type);
            const marker = L.marker([l.lat, l.lng], {
                icon: L.divIcon({
                    className: 'custom-marker-extra-landmark',
                    html: `
                        <div style="background:${bg}; color:#ffffff; padding:4px 8px; border-radius:8px; border:2px solid #ffffff; display:flex; align-items:center; gap:5px; font-weight:700; font-size:10px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.2); white-space:nowrap;">
                            <span>${emoji}</span>
                            <span>${l.name}</span>
                        </div>
                    `,
                    iconSize: [130, 26],
                    iconAnchor: [65, 13]
                })
            }).addTo(map);

            marker.bindPopup(`<div style="font-size:11px; font-weight:600; color:#1e293b;">${l.name}<br><span style="color:#64748b; font-weight:400;">Category: ${l.type}</span></div>`);
        });
    }

    function createLandmarkMarker() {
        if (!map) return;
        if (landmarkMarker) map.removeLayer(landmarkMarker);

        landmarkMarker = L.marker([landmarkLat, landmarkLng], {
            icon: L.divIcon({
                className: 'custom-marker-landmark',
                html: `
                    <div style="background:#2563eb; color:#ffffff; padding:6px 10px; border-radius:10px; border:2px solid #ffffff; display:flex; align-items:center; gap:6px; font-weight:800; font-size:11px; box-shadow:0 4px 8px rgba(0,0,0,0.3); white-space:nowrap;">
                        <span>🎓</span>
                        <span>KIRINYAGA UNIVERSITY</span>
                    </div>
                `,
                iconSize: [160, 32],
                iconAnchor: [80, 16]
            })
        }).addTo(map);

        landmarkMarker.bindPopup(`<div style="font-size:11px; font-weight:600; color:#1e293b;">Kirinyaga University (Main Campus)<br><span style="color:#64748b; font-weight:400;">Reference Anchor Point</span></div>`);
    }

    async function fetchAndDrawRoute(destLat, destLng) {
        const apiKey = window.GEOAPIFY_API_KEY;
        const pathDisplay = document.getElementById('pathDisplay');
        const distanceDisplay = document.getElementById('distanceDisplay');

        if (pathDisplay) pathDisplay.textContent = 'Calculating optimal driving route...';
        if (distanceDisplay) distanceDisplay.textContent = '–';

        if (!apiKey) {
            if (pathDisplay) pathDisplay.textContent = 'Geoapify key missing; route unavailable.';
            return;
        }

        const url = `https://api.geoapify.com/v1/routing?waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}&mode=drive&details=route_details&apiKey=${encodeURIComponent(apiKey)}`;

        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Routing request failed');
            const data = await res.json();

            if (!data.features || !data.features.length) throw new Error('No route found');

            const feature = data.features[0];
            const coords = feature.geometry.coordinates[0];
            const distanceKm = (feature.properties.distance / 1000).toFixed(2);
            const timeMinutes = Math.round(feature.properties.time / 60);

            if (routeLayer) map.removeLayer(routeLayer);

            // Draw clean high-visibility gradient/colored polyline
            routeLayer = L.polyline(coords.map(c => [c[1], c[0]]), {
                color: '#6366f1', weight: 5, opacity: 0.85, lineCap: 'round'
            }).addTo(map);

            if (pathDisplay) pathDisplay.textContent = `Route active via Kutus access roads (~${timeMinutes} min drive).`;
            if (distanceDisplay) distanceDisplay.textContent = `${distanceKm} km from Campus`;
        } catch (err) {
            console.error('Error fetching route:', err);
            if (pathDisplay) pathDisplay.textContent = 'Direct route rendering failed.';
        }
    }

    function updatePropertyMarker(lat, lng) {
        lat = Number(lat);
        lng = Number(lng);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        if (propertyMarker) map.removeLayer(propertyMarker);

        propertyMarker = L.marker([lat, lng], {
            draggable: true,
            icon: L.divIcon({
                className: 'custom-marker-property',
                html: `
                    <div style="background:#dc2626; color:white; width:38px; height:38px; border-radius:50%; border:3px solid white; display:flex; align-items:center; justify-content:center; font-size:16px; box-shadow:0 4px 10px rgba(220,38,38,0.4); animation: pulse 2s infinite;">
                        🏠
                    </div>
                `,
                iconSize: [38, 38],
                iconAnchor: [19, 19]
            })
        }).addTo(map);

        // Update coordinates on drag release
        propertyMarker.on('dragend', function (event) {
            const position = event.target.getLatLng();
            updatePropertyMarker(position.lat, position.lng);
        });

        propertyMarker.bindPopup(`<div style="font-size:11px; font-weight:600; color:#1e293b;">Selected Property Location<br><span style="color:#64748b; font-weight:400;">Drag marker to adjust position</span></div>`).openPopup();

        const latDisplay = document.getElementById('latDisplay');
        const lngDisplay = document.getElementById('lngDisplay');
        const mapLat = document.getElementById('mapLat');
        const mapLng = document.getElementById('mapLng');
        const statusEl = document.getElementById('locationStatus');

        if (latDisplay) latDisplay.textContent = lat.toFixed(6);
        if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);
        if (mapLat) mapLat.value = lat;
        if (mapLng) mapLng.value = lng;

        if (statusEl) {
            statusEl.textContent = 'Exact location pinned successfully! ✓';
            statusEl.style.color = '#10b981';
        }

        fetchAndDrawRoute(lat, lng);
    }

    // Initialize Map View
    const initialLat = options.initialLat ?? landmarkLat;
    const initialLng = options.initialLng ?? landmarkLng;

    map = L.map('locationMap', { zoomControl: false }).setView([initialLat, initialLng], 15);

    // Add zoom control to top-right for better accessibility
    L.control.zoom({ position: 'topright' }).addTo(map);

    map.whenReady(() => {
        showToast('Interactive map loaded!');
        createLandmarkMarker();
        createExtraLandmarks();
        if (options.initialLat && options.initialLng) {
            updatePropertyMarker(options.initialLat, options.initialLng);
        }
        const loadingEl = document.getElementById('mapLoading');
        if (loadingEl) loadingEl.style.display = 'none';
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19,
    }).addTo(map);

    map.on('click', function (e) {
        updatePropertyMarker(e.latlng.lat, e.latlng.lng);
    });

    // Manual Coordinate Handler
    window.useManualCoordinates = function () {
        const manualLatInput = document.getElementById('manualLat');
        const manualLngInput = document.getElementById('manualLng');
        if (!manualLatInput || !manualLngInput) return;

        const lat = parseFloat(manualLatInput.value);
        const lng = parseFloat(manualLngInput.value);

        if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            alert('Please enter valid latitude and longitude coordinates.');
            return;
        }

        updatePropertyMarker(lat, lng);
        map.setView([lat, lng], 17);
        manualLatInput.value = '';
        manualLngInput.value = '';
        showToast('Coordinates applied successfully!');
    };

    return { map, updatePropertyMarker };
}