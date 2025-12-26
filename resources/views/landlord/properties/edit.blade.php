{{-- resources/views/landlord/properties/edit.blade.php --}}
<x-app-layout>
    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    <style>
        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding-top: 16px;
            padding-bottom: 24px;
        }
        .uc-container {
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 16px;
            padding-right: 16px;
        }
        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            overflow: hidden;
        }
        .uc-card-inner {
            padding: 18px 18px 20px;
        }
        .uc-title-main {
            font-size: 20px;
            font-weight: 600;
            color: #f9fafb;
            margin-bottom: 16px;
        }
        .uc-section-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
            padding-bottom: 6px;
            border-bottom: 1px solid #111827;
            margin-bottom: 14px;
        }
        .uc-error-box {
            margin-bottom: 16px;
            background-color: #220b0f;
            border: 1px solid #7f1d1d;
            color: #fecaca;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
        }
        .uc-error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .uc-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 16px;
        }
        .uc-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 16px;
        }
        @media (max-width: 768px) {
            .uc-grid-2,
            .uc-grid-4 {
                grid-template-columns: minmax(0,1fr);
            }
        }

        .uc-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .uc-input,
        .uc-select,
        .uc-textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #1f2937;
            background-color: #020617;
            color: #f9fafb;
            padding: 8px 10px;
            font-size: 14px;
        }
        .uc-input:focus,
        .uc-select:focus,
        .uc-textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 1px rgba(99,102,241,0.45);
        }
        .uc-checkbox-row {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #e5e7eb;
        }
        .uc-checkbox-row input[type="checkbox"] {
            margin-right: 6px;
            accent-color: #6366f1;
        }

        /* Map */
        #locationMap {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #1f2937;
            margin-top: 8px;
            background-color: #020617;
        }
        .map-container {
            min-height: 400px;
            position: relative;
        }
        .map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
        }
        .map-instructions {
            background-color: #0b1120;
            border: 1px solid #1d4ed8;
            border-radius: 8px;
            padding: 10px 11px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #e5e7eb;
        }
        .map-instructions strong {
            color: #93c5fd;
        }
        .coordinate-display {
            background-color: #020617;
            border: 1px solid #1f2937;
            border-radius: 8px;
            padding: 8px 10px;
            margin-top: 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
        }

        /* Arrow cursor on map */
        #locationMap,
        #locationMap .leaflet-container,
        #locationMap .leaflet-interactive,
        #locationMap .leaflet-marker-icon,
        #locationMap .leaflet-bar a,
        #locationMap .leaflet-control {
            cursor: default !important;
        }

        /* Images */
        .uc-images-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 10px;
        }
        @media (max-width: 768px) {
            .uc-images-grid {
                grid-template-columns: repeat(2, minmax(0,1fr));
            }
        }
        .uc-image-wrap {
            overflow: hidden;
            border-radius: 10px;
            border: 1px solid #1f2937;
        }
        .uc-image-wrap img {
            width: 100%;
            height: 96px;
            object-fit: cover;
            display: block;
        }

        /* Footer buttons */
        .uc-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid #111827;
            margin-top: 8px;
        }
        .uc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 22px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        .uc-btn-ghost {
            background-color: #020617;
            border-color: #1f2937;
            color: #e5e7eb;
        }
        .uc-btn-ghost:hover {
            border-color: #6366f1;
        }
        .uc-btn-primary {
            background-color: #6366f1;
            border-color: #4f46e5;
            color: #ffffff;
        }
        .uc-btn-primary:hover {
            background-color: #4f46e5;
        }

        /* Toast */
        #toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: #020617;
            color: #f9fafb;
            padding: 0.7rem 1rem;
            border-radius: 0.6rem;
            border: 1px solid #1f2937;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 9999;
            font-size: 0.85rem;
        }
    </style>

    {{-- Toast --}}
    <div id="toast"></div>

    {{-- Expose Geoapify key --}}
    <script>
        const GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));
    </script>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
                {{ __('Edit Property') }}
            </h2>
            <p class="uc-header-subtitle">
                Update details, location, and images for this listing.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                <div class="uc-card-inner">
                    <h1 class="uc-title-main">
                        Edit: {{ $property->title }}
                    </h1>

                    @if ($errors->any())
                        <div class="uc-error-box">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('landlord.properties.update', $property->id) }}"
                          enctype="multipart/form-data"
                          id="propertyForm">
                        @csrf
                        @method('PUT')

                        {{-- Basic Information --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Basic Information</h3>
                            <div class="uc-grid-2">
                                <div>
                                    <label class="uc-label">Property Title *</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', $property->title) }}"
                                        required
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Property Type *</label>
                                    <select name="property_type" required class="uc-select">
                                        @php $types = ['apartment','house','room','studio','bedsitter']; @endphp
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}"
                                                {{ old('property_type', $property->property_type) === $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="uc-label">Price (KES) *</label>
                                    <input
                                        type="number"
                                        name="price"
                                        value="{{ old('price', $property->price) }}"
                                        required min="0" step="0.01"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Area *</label>
                                    <select name="area" id="areaSelect" required class="uc-select">
                                        @php $areas = ['mjini','ngomongo','diaspora','kutus']; @endphp
                                        <option value="">Select Area</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area }}"
                                                {{ old('area', $property->area) === $area ? 'selected' : '' }}>
                                                {{ ucfirst($area) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Property Details --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Property Details</h3>
                            <div class="uc-grid-4">
                                <div>
                                    <label class="uc-label">Bedrooms *</label>
                                    <input
                                        type="number"
                                        name="bedrooms"
                                        value="{{ old('bedrooms', $property->bedrooms) }}"
                                        required min="0"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Bathrooms *</label>
                                    <input
                                        type="number"
                                        name="bathrooms"
                                        value="{{ old('bathrooms', $property->bathrooms) }}"
                                        required min="0"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Size (sqm) *</label>
                                    <input
                                        type="number"
                                        name="size"
                                        value="{{ old('size', $property->size) }}"
                                        required min="0" step="0.01"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Amenities</label>
                                    @php
                                        $selectedAmenities = old('amenities', $property->amenities ?? []);
                                        $allAmenities = [
                                            'wifi' => 'WiFi',
                                            'water' => 'Water 24/7',
                                            'parking' => 'Parking',
                                            'security' => 'Security',
                                        ];
                                    @endphp
                                    <div style="display:flex;flex-direction:column;gap:4px;">
                                        @foreach($allAmenities as $key => $label)
                                            <label class="uc-checkbox-row">
                                                <input
                                                    type="checkbox"
                                                    name="amenities[]"
                                                    value="{{ $key }}"
                                                    {{ in_array($key, $selectedAmenities, true) ? 'checked' : '' }}
                                                >
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:16px;">
                                <label class="uc-label">Description *</label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    required
                                    class="uc-textarea"
                                >{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Address Information</h3>
                            <div class="uc-grid-2">
                                <div>
                                    <label class="uc-label">Building Name (Optional)</label>
                                    <input
                                        type="text"
                                        name="building_name"
                                        value="{{ old('building_name', $property->building_name) }}"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Street/Road *</label>
                                    <input
                                        type="text"
                                        name="street"
                                        value="{{ old('street', $property->street) }}"
                                        required
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Landmark (Optional)</label>
                                    <input
                                        type="text"
                                        name="landmark"
                                        value="{{ old('landmark', $property->landmark) }}"
                                        class="uc-input"
                                    >
                                </div>

                                <div>
                                    <label class="uc-label">Full Address *</label>
                                    <input
                                        type="text"
                                        name="address"
                                        value="{{ old('address', $property->address) }}"
                                        required
                                        class="uc-input"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Availability --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Availability</h3>
                            <label class="uc-checkbox-row">
                                <input
                                    type="checkbox"
                                    name="is_available"
                                    value="1"
                                    {{ old('is_available', $property->is_available) ? 'checked' : '' }}
                                >
                                <span>Mark as available for students</span>
                            </label>
                        </div>

                        {{-- Exact Location (Map) --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Exact Property Location</h3>

                            <div class="map-instructions">
                                <p>
                                    <strong>Tip:</strong> Click on the map to adjust the exact location of this property.
                                </p>
                                <p style="margin-top:4px;">
                                    🎓 Fixed university marker shows the campus. 📍 is your property. The route is from Geoapify.
                                </p>
                            </div>

                            <div class="map-container">
                                <div class="map-loading" id="mapLoading">Loading map...</div>
                                <div id="locationMap"></div>
                            </div>

                            <div class="coordinate-display">
                                <div class="uc-grid-2" style="gap:10px;">
                                    <div>
                                        <span style="font-size:12px;color:#9ca3af;">Latitude:</span>
                                        <span id="latDisplay" style="margin-left:6px;font-size:13px;color:#f9fafb;">
                                            {{ $property->map_lat ?? $property->latitude ?? 'Click on map' }}
                                        </span>
                                        <input
                                            type="hidden"
                                            name="map_lat"
                                            id="mapLat"
                                            value="{{ old('map_lat', $property->map_lat ?? $property->latitude) }}"
                                        >
                                    </div>
                                    <div>
                                        <span style="font-size:12px;color:#9ca3af;">Longitude:</span>
                                        <span id="lngDisplay" style="margin-left:6px;font-size:13px;color:#f9fafb;">
                                            {{ $property->map_lng ?? $property->longitude ?? 'Click on map' }}
                                        </span>
                                        <input
                                            type="hidden"
                                            name="map_lng"
                                            id="mapLng"
                                            value="{{ old('map_lng', $property->map_lng ?? $property->longitude) }}"
                                        >
                                    </div>
                                </div>
                                <div style="margin-top:6px;font-size:11px;color:#9ca3af;">
                                    <span id="locationStatus">
                                        {{ ($property->map_lat || $property->latitude) ? 'Location already set' : 'No location selected' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Images --}}
                        <div class="mb-8">
                            <h3 class="uc-section-title">Property Images</h3>

                            @if(is_array($property->images) && count($property->images))
                                <p style="font-size:13px;color:#9ca3af;margin-bottom:8px;">
                                    Existing images:
                                </p>
                                <div class="uc-images-grid" style="margin-bottom:12px;">
                                    @foreach($property->images as $image)
                                        @php $path = preg_replace('/^public\//', '', $image); @endphp
                                        <div class="uc-image-wrap">
                                            <img src="{{ asset('storage/'.$path) }}" alt="Existing image">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <p style="font-size:13px;color:#9ca3af;margin-bottom:6px;">
                                Upload additional images (existing ones will be kept):
                            </p>
                            <input
                                type="file"
                                name="images[]"
                                multiple
                                accept="image/*"
                                class="uc-input"
                                style="padding:6px 8px;"
                            >
                            <p style="font-size:11px;color:#6b7280;margin-top:4px;">
                                Max 2MB per image. JPEG, PNG, JPG, GIF.
                            </p>
                        </div>

                        {{-- Submit --}}
                        <div class="uc-footer-row">
                            <a href="{{ route('landlord.properties.myProperties') }}" class="uc-btn uc-btn-ghost">
                                Cancel
                            </a>
                            <button type="submit" class="uc-btn uc-btn-primary">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script>
        const landmarkLat = -0.566519;
        const landmarkLng = 37.321184;

        const areaCoordinates = {
            mjini: { lat: -0.5080, lng: 37.2752 },
            ngomongo: { lat: -0.5100, lng: 37.2800 },
            diaspora: { lat: -0.5150, lng: 37.2850 },
            kutus: { lat: -0.5200, lng: 37.2700 }
        };

        let map = null;
        let landmarkMarker = null;
        let propertyMarker = null;
        let routeLayer = null; // GeoJSON route from Geoapify

        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.textContent = message;
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, duration);
        }

        document.addEventListener('DOMContentLoaded', function () {
            initMap();
            setupEventListeners();
            const loadingEl = document.getElementById('mapLoading');
            if (loadingEl) loadingEl.style.display = 'none';
        });

        function createLandmarkMarker() {
            if (!map) return;
            if (landmarkMarker) map.removeLayer(landmarkMarker);

            landmarkMarker = L.marker([landmarkLat, landmarkLng], {
                icon: L.divIcon({
                    className: 'custom-marker-landmark',
                    html: `
                        <div style="
                            background:#1d4ed8;
                            color:white;
                            min-width:40px;
                            min-height:40px;
                            padding:4px 8px;
                            border-radius:9999px;
                            border:3px solid white;
                            display:flex;
                            flex-direction:column;
                            align-items:center;
                            justify-content:center;
                            font-weight:bold;
                            font-size:10px;
                            box-shadow:0 2px 5px rgba(0,0,0,0.3);
                            text-align:center;
                        ">
                            🎓
                            <span style="font-size:8px;line-height:1;margin-top:2px;">
                                UNIVERSITY
                            </span>
                        </div>
                    `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                })
            }).addTo(map);
        }

        async function fetchAndDrawRoute(propertyLat, propertyLng) {
            if (!GEOAPIFY_API_KEY) {
                console.warn("Geoapify API key is not set.");
                return;
            }

            const fromWaypoint = [landmarkLat, landmarkLng];
            const toWaypoint = [Number(propertyLat), Number(propertyLng)];

            if (Number.isNaN(toWaypoint[0]) || Number.isNaN(toWaypoint[1])) return;

            const url =
                `https://api.geoapify.com/v1/routing?waypoints=${fromWaypoint.join(',')}|${toWaypoint.join(',')}` +
                `&mode=drive&details=instruction_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`; // [web:288][web:393]

            try {
                const res = await fetch(url);
                if (!res.ok) {
                    console.error("Geoapify routing error:", res.statusText);
                    return;
                }
                const data = await res.json(); // FeatureCollection GeoJSON [web:288][web:297]

                const routeFeature = data.features && data.features[0];
                if (!routeFeature) {
                    console.warn("No route feature in Geoapify response");
                    return;
                }

                if (routeLayer) {
                    map.removeLayer(routeLayer);
                }

                routeLayer = L.geoJSON(routeFeature, {
                    style: {
                        color: "#3b82f6",
                        weight: 4,
                        opacity: 0.9
                    }
                }).addTo(map); // [web:294][web:371]

            } catch (err) {
                console.error("Failed to fetch route:", err);
            }
        }

        function updatePropertyMarker(lat, lng) {
            if (!map) return;

            lat = Number(lat);
            lng = Number(lng);

            if (Number.isNaN(lat) || Number.isNaN(lng)) {
                console.warn('Invalid coordinates for marker', lat, lng);
                return;
            }

            if (propertyMarker) map.removeLayer(propertyMarker);

            propertyMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker-property',
                    html: `
                        <div style="
                            background:#ef4444;
                            color:white;
                            width:35px;
                            height:35px;
                            border-radius:50%;
                            border:3px solid white;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-weight:bold;
                            box-shadow:0 2px 5px rgba(0,0,0,0.3);
                        ">
                            📍
                        </div>
                    `,
                    iconSize: [35, 35],
                    iconAnchor: [17.5, 35]
                })
            }).addTo(map);

            const latDisplay = document.getElementById('latDisplay');
            const lngDisplay = document.getElementById('lngDisplay');
            const mapLatInput = document.getElementById('mapLat');
            const mapLngInput = document.getElementById('mapLng');
            const statusEl = document.getElementById('locationStatus');

            if (latDisplay) latDisplay.textContent = lat.toFixed(6);
            if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);
            if (mapLatInput) mapLatInput.value = lat;
            if (mapLngInput) mapLngInput.value = lng;
            if (statusEl) {
                statusEl.textContent = 'Location set! ✓';
                statusEl.style.color = '#10b981';
            }

            // Draw exact route from Geoapify
            fetchAndDrawRoute(lat, lng);
        }

        function setupEventListeners() {
            const areaSelect = document.getElementById('areaSelect');
            if (areaSelect) {
                areaSelect.addEventListener('change', function (e) {
                    const area = e.target.value;
                    if (areaCoordinates[area] && map) {
                        const { lat, lng } = areaCoordinates[area];
                        map.setView([lat, lng], 16);
                        updatePropertyMarker(lat, lng);
                    }
                });
            }

            const form = document.getElementById('propertyForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const mapLat = document.getElementById('mapLat')?.value;
                    const mapLng = document.getElementById('mapLng')?.value;
                    if (!mapLat || !mapLng) {
                        e.preventDefault();
                        alert('Please click on the map to set your property location!');
                    }
                });
            }
        }

        function initMap() {
            try {
                if (typeof L === 'undefined') {
                    alert('Map library failed to load. Please refresh the page.');
                    return;
                }

                const mapElement = document.getElementById('locationMap');
                if (!mapElement) return;

                const initialLatRaw = @json($property->map_lat ?? $property->latitude ?? null);
                const initialLngRaw = @json($property->map_lng ?? $property->longitude ?? null);

                const initialLat = initialLatRaw !== null ? Number(initialLatRaw) : null;
                const initialLng = initialLngRaw !== null ? Number(initialLngRaw) : null;

                const centerLat = initialLat ?? landmarkLat;
                const centerLng = initialLng ?? landmarkLng;

                map = L.map('locationMap').setView([centerLat, centerLng], 15);

                map.whenReady(() => {
                    showToast('Map loaded successfully!');
                    createLandmarkMarker();
                    if (initialLat !== null && initialLng !== null) {
                        updatePropertyMarker(initialLat, initialLng);
                    }
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                map.on('click', function (e) {
                    updatePropertyMarker(e.latlng.lat, e.latlng.lng);
                });
            } catch (error) {
                alert('Error loading map: ' + error.message);
            }
        }
    </script>
</x-app-layout>
