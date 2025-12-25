{{-- resources/views/landlord/properties/create.blade.php --}}
<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
    
    <style>
        #locationMap {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid #3b82f6;
            margin-top: 10px;
            background-color: #f3f4f6;
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
            color: #6b7280;
            font-weight: 500;
        }
        
        .map-instructions {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .coordinate-display {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            font-family: monospace;
        }

        .path-display {
            background-color: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        .dark #locationMap {
            border-color: #374151;
            background-color: #1f2937;
        }
        
        .dark .map-instructions {
            background-color: #1e3a8a;
            border-color: #3b82f6;
            color: #dbeafe;
        }
        
        .dark .coordinate-display {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dark .path-display {
            background-color: #064e3b;
            border-color: #16a34a;
            color: #dcfce7;
        }
        
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            font-family: monospace;
            font-size: 12px;
            display: none; /* Hide in production */
        }
    </style>

    {{-- Toast --}}
    <div id="toast" style="
        position:fixed;top:1rem;right:1rem;
        background:#111827;color:#f9fafb;
        padding:0.75rem 1rem;border-radius:0.5rem;
        opacity:0;transition:opacity .2s;z-index:9999;
    "></div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Property') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Add New Property</h1>
                    
                    <div class="debug-info">
                        <div id="mapStatus">Checking map...</div>
                        <div id="mapDimensions"></div>
                    </div>
                    
                    <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data" id="propertyForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Property Title *</label>
                                    <input type="text" name="title" required 
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="e.g., Modern 2-Bedroom Apartment">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Property Type *</label>
                                    <select name="property_type" required 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Type</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="house">House</option>
                                        <option value="room">Single Room</option>
                                        <option value="studio">Studio</option>
                                        <option value="bedsitter">Bedsitter</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price (KES) *</label>
                                    <input type="number" name="price" required min="0" step="0.01"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="e.g., 15000">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Area *</label>
                                    <select name="area" id="areaSelect" required 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Property Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bedrooms *</label>
                                    <input type="number" name="bedrooms" required min="0"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bathrooms *</label>
                                    <input type="number" name="bathrooms" required min="0"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size (sqm) *</label>
                                    <input type="number" name="size" required min="0" step="0.01"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amenities</label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="wifi" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">WiFi</span>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="water" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Water 24/7</span>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="parking" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Parking</span>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="security" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Security</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                                <textarea name="description" required rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Describe your property..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Building Name (Optional)</label>
                                    <input type="text" name="building_name"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg"
                                           placeholder="e.g., Greenview Apartments">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Street/Road *</label>
                                    <input type="text" name="street" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg"
                                           placeholder="e.g., Mjini Road">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Landmark (Optional)</label>
                                    <input type="text" name="landmark"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg"
                                           placeholder="e.g., Near Equity Bank">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Address *</label>
                                    <input type="text" name="address" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg"
                                           placeholder="e.g., Mjini Road, Next to Market">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exact Location Selection -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Exact Property Location</h3>
                            
                            <div class="map-instructions">
                                <p class="text-sm">
                                    <strong class="text-blue-700 dark:text-blue-300">Important:</strong> Please pinpoint the EXACT location of your property on the map below.
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    <strong>Instructions:</strong> 
                                    1. Select your area above first<br>
                                    2. Click on the map to drop a pin at your property's exact location
                                </p>
                            </div>
                            
                            <div class="map-container">
                                <div class="map-loading" id="mapLoading">Loading map...</div>
                                <div id="locationMap"></div>
                            </div>
                            
                            <div class="coordinate-display">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Latitude:</span>
                                        <span id="latDisplay" class="ml-2 font-medium text-gray-900 dark:text-gray-100">Click on map</span>
                                        <input type="hidden" name="map_lat" id="mapLat" required>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Longitude:</span>
                                        <span id="lngDisplay" class="ml-2 font-medium text-gray-900 dark:text-gray-100">Click on map</span>
                                        <input type="hidden" name="map_lng" id="mapLng" required>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
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
                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                <p>Can't find your location on the map? Enter coordinates manually:</p>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <input type="number" step="any" id="manualLat" 
                                               class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded text-sm"
                                               placeholder="Latitude (e.g., -0.5080)">
                                    </div>
                                    <div>
                                        <input type="number" step="any" id="manualLng" 
                                               class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded text-sm"
                                               placeholder="Longitude (e.g., 37.2752)">
                                    </div>
                                    <div class="col-span-2">
                                        <button type="button" onclick="useManualCoordinates()" 
                                                class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm hover:bg-gray-300 dark:hover:bg-gray-600">
                                            Use These Coordinates
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Images Upload -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Property Images</h3>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                <input type="file" name="images[]" multiple accept="image/*" 
                                       class="w-full cursor-pointer" id="imageUpload">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Upload multiple images of your property (max 5 images, 2MB each)</p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('landlord.properties.index') }}" 
                               class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-150">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
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

    <script>
        // Fixed landmark (Kirinyaga University area)
        const landmarkLat = -0.566519;
        const landmarkLng = 37.321184;

        // Area coordinates
        const areaCoordinates = {
            mjini: { lat: -0.5080, lng: 37.2752 },
            ngomongo: { lat: -0.5100, lng: 37.2800 },
            diaspora: { lat: -0.5150, lng: 37.2850 },
            kutus: { lat: -0.5200, lng: 37.2700 }
        };

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

            // After setting property marker, fetch and draw route
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

                // Geoapify routing API (driving-car profile)
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
                const coords = feature.geometry.coordinates[0]; // [lng, lat] array
                const distanceMeters = feature.properties.distance; // in meters
                const distanceKm = (distanceMeters / 1000).toFixed(2);

                // Remove old route line if any
                if (routeLine) {
                    map.removeLayer(routeLine);
                }

                // Convert [lng, lat] to [lat, lng] for Leaflet polyline
                const latLngs = coords.map(c => [c[1], c[0]]);

                routeLine = L.polyline(latLngs, {
                    color: '#10b981',
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);

                // Fit map to route bounds
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
                                <img src="${ev.target.result}" class="w-full h-32 object-cover rounded-lg border">
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
