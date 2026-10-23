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
        { name: 'Holy Rosary Catholic Church', type: 'church', lat: -0.567122, lng: 37.325788 },
        { name: 'Central Mosque', type: 'mosque', lat: -0.5630, lng: 37.3235 },
        { name: 'Hostel Building A', type: 'building', lat: -0.5660, lng: 37.3260 }
    ];

    let map = null;
    let landmarkMarker = null;
    let propertyMarker = null;
    let routeLayer = null;

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
        if (!map) return;
        landmarks.forEach(l => {
            const { emoji, bg } = getLandmarkIcon(l.type);
            const marker = L.marker([l.lat, l.lng], {
                icon: L.divIcon({
                    className: 'custom-marker-extra-landmark',
                    html: `
                        <div style="background:${bg}; color:#fff; padding:3px 6px; border-radius:6px; border:1.5px solid white; display:flex; align-items:center; gap:4px; font-weight:bold; font-size:10px; box-shadow:0 2px 4px rgba(0,0,0,0.3); white-space:nowrap;">
                            <span>${emoji}</span>
                            <span>${l.name}</span>
                        </div>
                    `,
                    iconSize: [120, 24],
                    iconAnchor: [60, 12]
                })
            }).addTo(map);

            marker.bindTooltip(`${l.name} (${l.type})`, { permanent: false, direction: 'top' });
        });
    }

    function createLandmarkMarker() {
        if (!map) return;
        if (landmarkMarker) map.removeLayer(landmarkMarker);

        landmarkMarker = L.marker([landmarkLat, landmarkLng], {
            icon: L.divIcon({
                className: 'custom-marker-landmark',
                html: `
                    <div style="background:#1d4ed8; color:#fff; padding:4px 8px; border-radius:8px; border:2px solid white; display:flex; align-items:center; gap:4px; font-weight:bold; font-size:10px; box-shadow:0 2px 5px rgba(0,0,0,0.3); white-space:nowrap;">
                        <span>🎓</span>
                        <span>UNIVERSITY</span>
                    </div>
                `,
                iconSize: [100, 30],
                iconAnchor: [50, 15]
            })
        }).addTo(map);
    }

    async function fetchAndDrawRoute(destLat, destLng) {
        const apiKey = window.GEOAPIFY_API_KEY;
        if (!apiKey) return;

        const pathDisplay = document.getElementById('pathDisplay');
        const distanceDisplay = document.getElementById('distanceDisplay');

        if (pathDisplay) pathDisplay.textContent = 'Calculating route...';
        if (distanceDisplay) distanceDisplay.textContent = '–';

        const url = `https://api.geoapify.com/v1/routing?waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}&mode=drive&details=route_details&apiKey=${encodeURIComponent(apiKey)}`;

        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Routing request failed');
            const data = await res.json();

            if (!data.features || !data.features.length) throw new Error('No route found');

            const feature = data.features[0];
            const coords = feature.geometry.coordinates[0];
            const distanceKm = (feature.properties.distance / 1000).toFixed(2);

            if (routeLayer) map.removeLayer(routeLayer);

            routeLayer = L.polyline(coords.map(c => [c[1], c[0]]), {
                color: '#10b981', weight: 4, opacity: 0.8
            }).addTo(map);

            if (pathDisplay) pathDisplay.textContent = 'Driving route from university to selected property.';
            if (distanceDisplay) distanceDisplay.textContent = `${distanceKm} km`;
        } catch (err) {
            console.error('Error fetching route:', err);
            if (pathDisplay) pathDisplay.textContent = 'Could not calculate route.';
        }
    }

    function updatePropertyMarker(lat, lng) {
        lat = Number(lat);
        lng = Number(lng);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        if (propertyMarker) map.removeLayer(propertyMarker);

        propertyMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'custom-marker-property',
                html: `
                    <div style="background:#ef4444; color:white; width:32px; height:32px; border-radius:50%; border:2px solid white; display:flex; align-items:center; justify-content:center; font-weight:bold; box-shadow:0 2px 5px rgba(0,0,0,0.3);">
                        📍
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            })
        }).addTo(map);

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
            statusEl.textContent = 'Location set! ✓';
            statusEl.style.color = '#10b981';
        }

        fetchAndDrawRoute(lat, lng);
    }

    // Initialize Map View
    const initialLat = options.initialLat ?? landmarkLat;
    const initialLng = options.initialLng ?? landmarkLng;

    map = L.map('locationMap').setView([initialLat, initialLng], 15);

    map.whenReady(() => {
        showToast('Map loaded successfully!');
        createLandmarkMarker();
        createExtraLandmarks();
        if (options.initialLat && options.initialLng) {
            updatePropertyMarker(options.initialLat, options.initialLng);
        }
        const loadingEl = document.getElementById('mapLoading');
        if (loadingEl) loadingEl.style.display = 'none';
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors', maxZoom: 19,
    }).addTo(map);

    map.on('click', function (e) {
        updatePropertyMarker(e.latlng.lat, e.latlng.lng);
    });

    // Manual Coordinate Handler
    window.useManualCoordinates = function () {
        const lat = parseFloat(document.getElementById('manualLat').value);
        const lng = parseFloat(document.getElementById('manualLng').value);

        if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            alert('Please enter valid coordinates');
            return;
        }

        updatePropertyMarker(lat, lng);
        map.setView([lat, lng], 16);
        document.getElementById('manualLat').value = '';
        document.getElementById('manualLng').value = '';
    };

    return { map, updatePropertyMarker };
}