{{-- resources/views/landlord/properties/create.blade.php --}}
<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
    
    <style>
        :root {
            --uc-bg: #020617;
            --uc-card: #020617;
            --uc-border: #1f2937;
            --uc-muted: #9ca3af;
            --uc-text: #f9fafb;
            --uc-accent: #6366f1;
            --uc-accent-soft: #4f46e5;
        }

        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100%;
            padding-top: 1rem;
            padding-bottom: 2rem;
        }

        .uc-card {
            background: var(--uc-card);
            border-radius: 1rem;
            border: 1px solid var(--uc-border);
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
        }

        .uc-card__inner {
            padding: 1.5rem 1.4rem 1.75rem;
        }

        .uc-card__title {
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--uc-text);
            margin-bottom: 0.25rem;
        }

        .uc-card__subtitle {
            font-size: 0.9rem;
            color: var(--uc-muted);
            margin-bottom: 1.2rem;
        }

        .uc-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--uc-text);
            padding-bottom: 0.4rem;
            border-bottom: 1px solid #111827;
            margin-bottom: 1rem;
        }

        .uc-field-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--uc-muted);
            margin-bottom: 0.35rem;
        }

        .uc-input,
        .uc-select,
        .uc-textarea {
            width: 100%;
            border-radius: 0.65rem;
            border: 1px solid #1f2937;
            background: #020617;
            color: var(--uc-text);
            padding: 0.55rem 0.75rem;
            font-size: 0.9rem;
        }

        .uc-input:focus,
        .uc-select:focus,
        .uc-textarea:focus {
            outline: none;
            border-color: var(--uc-accent);
            box-shadow: 0 0 0 1px rgba(99,102,241,0.5);
        }

        .uc-checkbox-row {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.88rem;
            color: var(--uc-text);
        }

        .uc-checkbox-row input[type="checkbox"] {
            accent-color: var(--uc-accent);
        }

        .uc-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 1rem;
        }
        .uc-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .uc-grid-2,
            .uc-grid-4 {
                grid-template-columns: minmax(0,1fr);
            }
        }

        /* Map */
        #locationMap {
            height: 400px;
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #1f2937;
            margin-top: 0.5rem;
            background-color: #020617;
        }
        #locationMap,
        #locationMap .leaflet-container,
        #locationMap .leaflet-interactive,
        #locationMap .leaflet-marker-icon,
        #locationMap .leaflet-bar a,
        #locationMap .leaflet-control {
            cursor: default !important;
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
            color: var(--uc-muted);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .map-instructions {
            background-color: #0b1120;
            border: 1px solid #1d4ed8;
            border-radius: 0.65rem;
            padding: 0.75rem 0.85rem;
            margin-bottom: 0.9rem;
            font-size: 0.86rem;
            color: #e5e7eb;
        }
        
        .map-instructions strong {
            color: #93c5fd;
        }
        
        .coordinate-display {
            background-color: #020617;
            border: 1px solid #1f2937;
            border-radius: 0.65rem;
            padding: 0.7rem 0.8rem;
            margin-top: 0.6rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.85rem;
        }

        .path-display {
            background-color: #020617;
            border: 1px solid #064e3b;
            border-radius: 0.65rem;
            padding: 0.7rem 0.8rem;
            margin-top: 0.6rem;
            font-size: 0.86rem;
        }
        
        .dark #locationMap {
            border-color: #111827;
            background-color: #020617;
        }
        
        .dark .map-instructions {
            background-color: #0b1120;
            border-color: #1d4ed8;
            color: #dbeafe;
        }
        
        .dark .coordinate-display {
            background-color: #020617;
            border-color: #1f2937;
            color: #d1d5db;
        }
        
        .dark .path-display {
            background-color: #022c22;
            border-color: #16a34a;
            color: #dcfce7;
        }
        
        .debug-info {
            background: #020617;
            border: 1px solid #111827;
            border-radius: 0.5rem;
            padding: 0.6rem 0.7rem;
            margin-bottom: 0.75rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.75rem;
            color: #9ca3af;
            display: none; /* Hide in production */
        }

        /* Upload */
        .uc-upload {
            border-radius: 0.75rem;
            border: 1px dashed #1f2937;
            padding: 1rem;
            text-align: center;
            background: #020617;
        }

        .uc-upload p {
            font-size: 0.85rem;
            color: var(--uc-muted);
        }

        /* Footer buttons */
        .uc-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.1rem;
            border-top: 1px solid #111827;
            margin-top: 0.5rem;
        }

        .uc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.2rem;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .uc-btn--primary {
            background: var(--uc-accent);
            border-color: var(--uc-accent-soft);
            color: #fff;
        }
        .uc-btn--primary:hover {
            background: var(--uc-accent-soft);
        }

        .uc-btn--ghost {
            background: #020617;
            border-color: #1f2937;
            color: var(--uc-text);
        }
        .uc-btn--ghost:hover {
            border-color: var(--uc-accent);
        }

        .uc-btn--small {
            padding: 0.45rem 0.9rem;
            border-radius: 0.65rem;
            font-size: 0.82rem;
        }
    </style>

    {{-- Toast --}}
    <div id="toast" style="
        position:fixed;top:1rem;right:1rem;
        background:#020617;color:#f9fafb;
        padding:0.7rem 1rem;border-radius:0.6rem;
        border:1px solid #1f2937;
        opacity:0;transition:opacity .2s;z-index:9999;
        font-size:0.85rem;
    "></div>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Add New Property') }}
            </h2>
            <p class="text-sm text-gray-400 mt-1">
                Add a listing with full details and exact map location.
            </p>
        </div>
    </x-slot>

    <div class="py-6 uc-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="uc-card">
                <div class="uc-card__inner">
                    <h1 class="uc-card__title">Add New Property</h1>
                    <p class="uc-card__subtitle">
                        Accurate details and location help students quickly decide if your unit fits their needs.
                    </p>
                    
                    <div class="debug-info">
                        <div id="mapStatus">Checking map...</div>
                        <div id="mapDimensions"></div>
                    </div>
                    
                    <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data" id="propertyForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="uc-section-title">Basic Information</h3>
                            <div class="uc-grid-2">
                                <div>
                                    <label class="uc-field-label">Property Title *</label>
                                    <input type="text" name="title" required 
                                           class="uc-input"
                                           placeholder="e.g., Modern 2-Bedroom Apartment">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Property Type *</label>
                                    <select name="property_type" required class="uc-select">
                                        <option value="">Select Type</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="house">House</option>
                                        <option value="room">Single Room</option>
                                        <option value="studio">Studio</option>
                                        <option value="bedsitter">Bedsitter</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Price (KES) *</label>
                                    <input type="number" name="price" required min="0" step="0.01"
                                           class="uc-input"
                                           placeholder="e.g., 15000">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Area *</label>
                                    <select name="area" id="areaSelect" required class="uc-select">
                                        <option value="">Select Area</option>
                                        <option value="mjini">Mjini</option>
                                        <option value="ngomongo">Ngomongo</option>
                                        <option value="diaspora">Diaspora</option>
                                        <option value="kutus">Kutus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="mb-8">
                            <h3 class="uc-section-title">Property Details</h3>
                            <div class="uc-grid-4">
                                <div>
                                    <label class="uc-field-label">Bedrooms *</label>
                                    <input type="number" name="bedrooms" required min="0" value="{{ old('bedrooms', 0) }}"
                                           class="uc-input">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Bathrooms *</label>
                                    <input type="number" name="bathrooms" required min="0" value="{{ old('bathrooms', 0) }}"
                                           class="uc-input">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Size (sqm) *</label>
                                    <input type="number" name="size" required min="0" step="0.01" value="{{ old('size', 0) }}"
                                           class="uc-input">
                                </div>

                                <div>
                                    <label class="uc-field-label">Currently Rented Rooms*</label>
                                    <input type="number" name="booked_count" required min="0" value="{{ old('booked_count', 0) }}"
                                           class="uc-input">
                                </div>
                              

                                <div>
                                    <label class="uc-field-label">Rooms available *</label>
                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ old('capacity', 1) }}"
                                        min="1"
                                        class="uc-input"
                                        required
                                    >
                                </div>
                            

                                
                                <div>
                                    <label class="uc-field-label">Amenities</label>
                                    <div class="space-y-1">
                                        <label class="uc-checkbox-row">
                                            <input type="checkbox" name="amenities[]" value="wifi">
                                            <span>WiFi</span>
                                        </label>
                                        <label class="uc-checkbox-row">
                                            <input type="checkbox" name="amenities[]" value="water">
                                            <span>Water 24/7</span>
                                        </label>
                                        <label class="uc-checkbox-row">
                                            <input type="checkbox" name="amenities[]" value="parking">
                                            <span>Parking</span>
                                        </label>
                                        <label class="uc-checkbox-row">
                                            <input type="checkbox" name="amenities[]" value="security">
                                            <span>Security</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="uc-field-label">Description *</label>
                                <textarea name="description" required rows="4"
                                          class="uc-textarea"
                                          placeholder="Describe your property, key rules, and any highlights students should know."></textarea>
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div class="mb-8">
                            <h3 class="uc-section-title">Address Information</h3>
                            <div class="uc-grid-2">
                                <div>
                                    <label class="uc-field-label">Building Name (Optional)</label>
                                    <input type="text" name="building_name"
                                           class="uc-input"
                                           placeholder="e.g., Greenview Apartments">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Street/Road *</label>
                                    <input type="text" name="street" required
                                           class="uc-input"
                                           placeholder="e.g., Mjini Road">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Landmark (Optional)</label>
                                    <input type="text" name="landmark"
                                           class="uc-input"
                                           placeholder="e.g., Near Equity Bank">
                                </div>
                                
                                <div>
                                    <label class="uc-field-label">Full Address *</label>
                                    <input type="text" name="address" required
                                           class="uc-input"
                                           placeholder="e.g., Mjini Road, Next to Market">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exact Location Selection -->
                        <div class="mb-8">
                            <h3 class="uc-section-title">Exact Property Location</h3>
                            
                            <div class="map-instructions">
                                <p>
                                    <strong>Important:</strong> Please pinpoint the exact location of your property on the map below.
                                </p>
                                <p style="margin-top:0.3rem;">
                                    <strong>Instructions:</strong> 1) Select your area above, 2) Click on the map to drop a pin at your property's exact location.
                                </p>
                            </div>
                            
                            <div class="map-container">
                                <div class="map-loading" id="mapLoading">Loading map try refreshing page if it takes too long...</div>
                                <div id="locationMap"></div>
                            </div>
                            
                            <div class="coordinate-display">
                                <div class="uc-grid-2" style="gap:0.75rem;">
                                    <div>
                                        <span class="text-sm text-gray-400">Latitude:</span>
                                        <span id="latDisplay" class="ml-2 font-medium text-gray-100">Click on map</span>
                                        <input type="hidden" name="map_lat" id="mapLat" required>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-400">Longitude:</span>
                                        <span id="lngDisplay" class="ml-2 font-medium text-gray-100">Click on map</span>
                                        <input type="hidden" name="map_lng" id="mapLng" required>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-400">
                                    <span id="locationStatus">No location selected</span>
                                </div>
                            </div>

                            <!-- Path & Distance Display -->
                            <div class="path-display">
                                <div class="text-sm">
                                    <strong>Route from University:</strong>
                                    <span id="pathDisplay">No route calculated yet.</span>
                                </div>
                                <div class="text-sm mt-1">
                                    <strong>Distance:</strong>
                                    <span id="distanceDisplay">–</span>
                                </div>
                            </div>
                            
                            <!-- Manual Coordinate Input (Fallback) -->
                            <div class="mt-4 text-sm text-gray-400">
                                <p>Can't find your location on the map? Enter coordinates manually:</p>
                                <div class="uc-grid-2" style="margin-top:0.35rem;">
                                    <div>
                                        <input type="number" step="any" id="manualLat" 
                                               class="uc-input uc-btn--small"
                                               placeholder="Latitude (e.g., -0.5080)">
                                    </div>
                                    <div>
                                        <input type="number" step="any" id="manualLng" 
                                               class="uc-input uc-btn--small"
                                               placeholder="Longitude (e.g., 37.2752)">
                                    </div>
                                    <div style="grid-column:1 / -1;">
                                        <button type="button" onclick="useManualCoordinates()" 
                                                class="uc-btn uc-btn--ghost uc-btn--small">
                                            Use These Coordinates
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Images Upload -->
                        <div class="mb-8">
                            <h3 class="uc-section-title">Property Images</h3>
                            <div class="uc-upload">
                                <input type="file" name="images[]" multiple accept="image/*" 
                                       class="w-full cursor-pointer" id="imageUpload">
                                <p class="mt-2">Upload multiple images of your property (max 5 images, 2MB each).</p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="uc-footer-row">
                            <a href="{{ route('landlord.properties.myProperties') }}" 
                               class="uc-btn uc-btn--ghost">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="uc-btn uc-btn--primary">
                                Add Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>

    {{-- Your original JS is kept exactly the same --}}
    <script>
        // Fixed landmark (Kirinyaga University area)
        const landmarkLat = -0.566519;
        const landmarkLng = 37.321184;

        // Area coordinates
        /*const areaCoordinates = {
            mjini: { lat: -0.5080, lng: 37.2752 },
            ngomongo: { lat: -0.5100, lng: 37.2800 },
            diaspora: { lat: -0.5150, lng: 37.2850 },
            kutus: { lat: -0.5200, lng: 37.2700 }
        };*/
        // Multiple landmarks around Kirinyaga University
        const landmarks = [
            {
                name: 'skymart Supermarket',
                type: 'supermarket',
                lat: -0.567700,
                lng: 37.324526
            },
            {
                name: 'cooperative Bank',
                type: 'bank',
                lat: -0.5667250,
                lng: 37.324600
            },
            {
                name: 'Rubis Station',
                type: 'petrol',
                lat: -0.5682000,
                lng: 37.3209166
            },
            /*{
                name: 'Shops Center',
                type: 'shop',
                lat: ,
                lng: 
            },*/
            {
                name: 'Holy Rosary Catholic Church',
                type: 'church',
                lat: -0.567122,
                lng: 37.325788
            },
            {
                name: 'Central Mosque',
                type: 'mosque',
                lat: -0.5630,
                lng: 37.3235
            },
            {
                name: 'Hostel Building A',
                type: 'building',
                lat: -0.5660,
                lng: 37.3260
            }
        ];
        function getLandmarkIcon(type) {
            // simple mapping: choose emoji and color per type
            let emoji = '📍';
            let bg = '#4b5563'; // default gray

            switch (type) {
                case 'supermarket':
                    emoji = '🛒';
                    bg = '#22c55e';
                    break;
                case 'bank':
                    emoji = '🏦';
                    bg = '#0ea5e9';
                    break;
                case 'petrol':
                    emoji = '⛽';
                    bg = '#f97316';
                    break;
                case 'shop':
                    emoji = '🏬';
                    bg = '#6366f1';
                    break;
                case 'church':
                    emoji = '⛪';
                    bg = '#a855f7';
                    break;
                case 'mosque':
                    emoji = '🕌';
                    bg = '#10b981';
                    break;
                case 'building':
                    emoji = '🏢';
                    bg = '#facc15';
                    break;
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
                            <div style="
                                background:${bg};
                                color:black;
                                max-width:30px;
                                max-height:30px;
                                padding:4px 8px;
                                border-radius:9999px;
                                border:2px solid white;
                                display:flex;
                                flex-direction:column;
                                align-items:center;
                                justify-content:center;
                                font-weight:bold;
                                gap:4px;
                                text-align:center;
                                font-size:10px;
                                box-shadow:0 2px 5px rgba(0,0,0,0.3);
                                white-space:nowrap;
                            ">
                                <span>${emoji}</span>
                                <span style="font-size:8px;line-height:1;margin-top:2px;">
                                    ${l.name}
                                </span>
                            </div>
                        `,
                        iconSize: [40, 24],
                        iconAnchor: [20, 24]
                    })
                }).addTo(map);

                // optional tooltip on hover
                marker.bindTooltip(`${l.name} (${l.type})`, {
                    permanent: false,
                    direction: 'top'
                });
            });
        }


        // Geoapify API key from Laravel config
        const GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));

        let map = null;
        let landmarkMarker = null;   // fixed university marker
        let propertyMarker = null;   // movable property marker
        let routeLine = null;        // polyline for route

        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.textContent = message;
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.opacity = '0';
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            setupEventListeners();
            createExtraLandmarks();

            const loadingEl = document.getElementById('mapLoading');
            if (loadingEl) {
                loadingEl.style.display = 'none';
            }
        });

        function createLandmarkMarker() {
            if (!map) return;
            if (landmarkMarker) {
                map.removeLayer(landmarkMarker);
            }

            landmarkMarker = L.marker([landmarkLat, landmarkLng], {
                icon: L.divIcon({
                    className: 'custom-marker-landmark',
                    html: `
                        <div style="
                            background:#1d4ed8;
                            color:black;
                            max-width:30px;
                            max-height:30px;
                            padding:4px 8px;
                            border-radius:9999px;
                            border:2px solid white;
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

        function updatePropertyMarker(lat, lng) {
            if (!map) return;

            if (propertyMarker) {
                map.removeLayer(propertyMarker);
            }

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

            const currentZoom = map.getZoom();
            map.setView([lat, lng], currentZoom > 16 ? currentZoom : 16);

            if (GEOAPIFY_API_KEY) {
                fetchRouteFromLandmark(lat, lng);
            } else {
                console.warn('Geoapify key missing; route not requested.');
            }
        }

        async function fetchRouteFromLandmark(destLat, destLng) {
            try {
                const pathDisplay = document.getElementById('pathDisplay');
                const distanceDisplay = document.getElementById('distanceDisplay');
                if (pathDisplay) pathDisplay.textContent = 'Calculating route...';
                if (distanceDisplay) distanceDisplay.textContent = '–';

                const url = `https://api.geoapify.com/v1/routing?` +
                    `waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}` +
                    `&mode=drive&details=route_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`;

                const res = await fetch(url);
                if (!res.ok) {
                    throw new Error('Routing request failed with status ' + res.status);
                }
                const data = await res.json();

                if (!data.features || !data.features.length) {
                    throw new Error('No route found');
                }

                const feature = data.features[0];
                const coords = feature.geometry.coordinates[0];
                const distanceMeters = feature.properties.distance;
                const distanceKm = (distanceMeters / 1000).toFixed(2);

                if (routeLine) {
                    map.removeLayer(routeLine);
                }

                const latLngs = coords.map(c => [c[1], c[0]]);

                routeLine = L.polyline(latLngs, {
                    color: '#10b981',
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);

                map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });

                if (pathDisplay) {
                    pathDisplay.textContent = 'Driving route from university to selected property.';
                }
                if (distanceDisplay) {
                    distanceDisplay.textContent = `${distanceKm} km`;
                }
            } catch (err) {
                console.error('Error fetching route:', err);
                const pathDisplay = document.getElementById('pathDisplay');
                const distanceDisplay = document.getElementById('distanceDisplay');
                if (pathDisplay) pathDisplay.textContent = 'Could not calculate route.';
                if (distanceDisplay) distanceDisplay.textContent = '–';
            }
        }

        function setupEventListeners() {
            const areaSelect = document.getElementById('areaSelect');
            if (areaSelect) {
                areaSelect.addEventListener('change', function(e) {
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
                form.addEventListener('submit', function(e) {
                    const mapLat = document.getElementById('mapLat')?.value;
                    const mapLng = document.getElementById('mapLng')?.value;

                    if (!mapLat || !mapLng) {
                        e.preventDefault();
                        alert('Please click on the map to set your property location!');
                        return false;
                    }
                    return true;
                });
            }

            const imageUpload = document.getElementById('imageUpload');
            if (imageUpload) {
                imageUpload.addEventListener('change', function(e) {
                    const preview = document.getElementById('imagePreview');
                    if (!preview) return;

                    preview.innerHTML = '';

                    const files = Array.from(e.target.files).slice(0, 5);
                    files.forEach(file => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${ev.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-800">
                                <button type="button" onclick="this.parentElement.remove()" 
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        }

        function addLocateControl() {
            if (!map) return;

            const locateControl = L.control({ position: 'topleft' });
            locateControl.onAdd = function() {
                const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                div.innerHTML = `
                    <a href="#" title="Use my location" 
                       style="display:block; padding:8px; background:white; border-radius:4px; border:2px solid rgba(0,0,0,0.2); cursor:pointer;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#3b82f6">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19 12a7 7 0 0 1-14 0 7 7 0 0 1 14 0z"/>
                        </svg>
                    </a>
                `;

                div.onclick = function(e) {
                    e.preventDefault();
                    locateUser();
                };

                return div;
            };
            locateControl.addTo(map);
        }

        function locateUser() {
            const statusEl = document.getElementById('locationStatus');

            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser");
                return;
            }

            if (statusEl) {
                statusEl.textContent = 'Getting your location...';
                statusEl.style.color = '#f59e0b';
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updatePropertyMarker(lat, lng);
                    alert("Your current location has been set. Adjust if needed.");
                },
                function(error) {
                    let message = "Could not get your location: ";
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message += "Permission denied. Please enable location access.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += "Location unavailable.";
                            break;
                        case error.TIMEOUT:
                            message += "Request timed out.";
                            break;
                        default:
                            message += "Unknown error.";
                    }
                    alert(message);
                    if (statusEl) {
                        statusEl.textContent = 'Location access failed';
                        statusEl.style.color = '#ef4444';
                    }
                }
            );
        }

        window.useManualCoordinates = function() {
            const latInput = document.getElementById('manualLat');
            const lngInput = document.getElementById('manualLng');
            if (!latInput || !lngInput) return;

            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (isNaN(lat) || isNaN(lng)) {
                alert('Please enter valid numbers for both latitude and longitude');
                return;
            }

            if (lat < -90 || lat > 90) {
                alert('Latitude must be between -90 and 90');
                return;
            }

            if (lng < -180 || lng > 180) {
                alert('Longitude must be between -180 and 180');
                return;
            }

            if (map) {
                updatePropertyMarker(lat, lng);
                latInput.value = '';
                lngInput.value = '';
            } else {
                alert('Map not ready yet. Please wait for map to load.');
            }
        };

        window.goToLandmark = function() {
            if (!map) return;
            map.setView([landmarkLat, landmarkLng], 15);
        };

        function initMap() {
            try {
                if (typeof L === 'undefined') {
                    alert('Map library failed to load. Please refresh the page.');
                    return;
                }

                const mapElement = document.getElementById('locationMap');
                if (!mapElement) {
                    return;
                }

                map = L.map('locationMap').setView([landmarkLat, landmarkLng], 15);

                map.whenReady(() => {
                    showToast('Map loaded successfully!');
                    createLandmarkMarker();
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                map.on('click', function(e) {
                    updatePropertyMarker(e.latlng.lat, e.latlng.lng);
                });

                addLocateControl();

            } catch (error) {
                alert('Error loading map: ' + error.message);
            }
        }
    </script>
</x-app-layout>
