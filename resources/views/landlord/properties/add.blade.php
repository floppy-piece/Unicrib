{{-- resources/views/landlord/properties/create.blade.php --}}
<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-4 right-4 bg-slate-900 text-slate-50 px-4 py-2.5 rounded-xl border border-slate-800 opacity-0 transition-opacity duration-200 z-50 text-xs shadow-lg"></div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    {{ __('Add New Property') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Add a listing with full details and exact map location.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 space-y-6">
                    
                    <div>
                        <h1 class="text-lg font-bold text-slate-900">Add New Property</h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Accurate details and location help students quickly decide if your unit fits their needs[cite: 3].
                        </p>
                    </div>
                    
                    <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data" id="propertyForm" class="space-y-8">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Basic Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Property Title *</label>
                                    <input type="text" name="title" required 
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"
                                           placeholder="e.g., Modern 2-Bedroom Apartment">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Property Type *</label>
                                    <select name="property_type" required class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                                        <option value="">Select Type</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="house">House</option>
                                        <option value="room">Single Room</option>
                                        <option value="studio">Studio</option>
                                        <option value="bedsitter">Bedsitter</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Price (KES) *</label>
                                    <input type="number" name="price" required min="0" step="0.01"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"
                                           placeholder="e.g., 15000">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Area *</label>
                                    <select name="area" id="areaSelect" required class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
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
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Property Details</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bedrooms *</label>
                                    <input type="number" name="bedrooms" required min="0" value="{{ old('bedrooms', 0) }}"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bathrooms *</label>
                                    <input type="number" name="bathrooms" required min="0" value="{{ old('bathrooms', 0) }}"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Size (sqm) *</label>
                                    <input type="number" name="size" required min="0" step="0.01" value="{{ old('size', 0) }}"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Currently Rented Rooms *</label>
                                    <input type="number" name="booked_count" required min="0" value="{{ old('booked_count', 0) }}"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Rooms Available *</label>
                                    <input type="number" name="capacity" value="{{ old('capacity', 1) }}" min="1"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs" required>
                                </div>
                            
                                <div class="sm:col-span-2 lg:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Amenities</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                                        <label class="flex items-center gap-2 text-xs text-slate-700">
                                            <input type="checkbox" name="amenities[]" value="wifi" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span>WiFi</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-xs text-slate-700">
                                            <input type="checkbox" name="amenities[]" value="water" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span>Water 24/7</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-xs text-slate-700">
                                            <input type="checkbox" name="amenities[]" value="parking" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span>Parking</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-xs text-slate-700">
                                            <input type="checkbox" name="amenities[]" value="security" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span>Security</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Description *</label>
                                <textarea name="description" required rows="4"
                                          class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"
                                          placeholder="Describe your property, key rules, and any highlights students should know."></textarea>
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Address Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Building Name (Optional)</label>
                                    <input type="text" name="building_name"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs"
                                           placeholder="e.g., Greenview Apartments">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Street/Road *</label>
                                    <input type="text" name="street" required
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs"
                                           placeholder="e.g., Mjini Road">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Landmark (Optional)</label>
                                    <input type="text" name="landmark"
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs"
                                           placeholder="e.g., Near Equity Bank">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Full Address *</label>
                                    <input type="text" name="address" required
                                           class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 px-3.5 py-2 text-xs"
                                           placeholder="e.g., Mjini Road, Next to Market">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exact Location Selection -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Exact Property Location</h3>
                            
                            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-100 text-xs text-blue-900">
                                <p><strong>Important:</strong> Please pinpoint the exact location of your property on the map below[cite: 3].</p>
                                <p class="mt-1"><strong>Instructions:</strong> 1) Select your area above, 2) Click on the map to drop a pin at your property's exact location[cite: 3].</p>
                            </div>
                            
                            <div class="relative min-h-[400px]">
                                <div id="mapLoading" class="absolute inset-0 flex items-center justify-center bg-slate-50 text-slate-500 font-medium text-xs z-10 rounded-xl">Loading map...</div>
                                <div id="locationMap" class="h-[400px] w-full rounded-xl border border-slate-200"></div>
                            </div>
                            
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-slate-500">Latitude:</span>
                                        <span id="latDisplay" class="ml-1 font-semibold text-slate-900">Click on map</span>
                                        <input type="hidden" name="map_lat" id="mapLat" required>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Longitude:</span>
                                        <span id="lngDisplay" class="ml-1 font-semibold text-slate-900">Click on map</span>
                                        <input type="hidden" name="map_lng" id="mapLng" required>
                                    </div>
                                </div>
                                <div class="mt-2 text-[11px] text-slate-500">
                                    <span id="locationStatus">No location selected</span>
                                </div>
                            </div>

                            <!-- Route & Distance Display -->
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-900 space-y-1">
                                <div>
                                    <strong>Route from University:</strong>
                                    <span id="pathDisplay">No route calculated yet.</span>
                                </div>
                                <div>
                                    <strong>Distance:</strong>
                                    <span id="distanceDisplay">–</span>
                                </div>
                            </div>
                            
                            <!-- Manual Coordinate Input (Fallback) -->
                            <div class="text-xs text-slate-500 space-y-2">
                                <p>Can't find your location on the map? Enter coordinates manually[cite: 3]:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="number" step="any" id="manualLat" 
                                           class="rounded-xl border border-slate-200 bg-white text-slate-900 px-3 py-2 text-xs"
                                           placeholder="Latitude (e.g., -0.5080)">
                                    <input type="number" step="any" id="manualLng" 
                                           class="rounded-xl border border-slate-200 bg-white text-slate-900 px-3 py-2 text-xs"
                                           placeholder="Longitude (e.g., 37.2752)">
                                    <div class="sm:col-span-2">
                                        <button type="button" onclick="useManualCoordinates()" 
                                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition border border-slate-200">
                                            Use These Coordinates
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Images Upload -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Property Images</h3>
                            <div class="p-6 rounded-xl border border-dashed border-slate-300 text-center bg-slate-50/50">
                                <input type="file" name="images[]" multiple accept="image/*" 
                                       class="w-full cursor-pointer text-xs text-slate-500" id="imageUpload">
                                <p class="mt-2 text-xs text-slate-500">Upload multiple images of your property (max 5 images, 2MB each)[cite: 3].</p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                            <a href="{{ route('landlord.properties.myProperties') }}" 
                               class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
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

        // Dynamically inject landmarks from database via Laravel collection JSON transform
        const landmarks = @json($landmarks);

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

                const marker = L.marker([l.latitude, l.longitude], {
                    icon: L.divIcon({
                        className: 'custom-marker-extra-landmark',
                        html: `
                            <div style="background:${bg}; color:black; max-width:30px; max-height:30px; padding:4px 8px; border-radius:9999px; border:2px solid white; display:flex; flex-direction:column; align-items:center; justify-content:center; font-weight:bold; gap:4px; text-align:center; font-size:10px; box-shadow:0 2px 5px rgba(0,0,0,0.3); white-space:nowrap;">
                                <span>${emoji}</span>
                                <span style="font-size:8px;line-height:1;margin-top:2px;">${l.name}</span>
                            </div>
                        `,
                        iconSize: [40, 24],
                        iconAnchor: [20, 24]
                    })
                }).addTo(map);

                marker.bindTooltip(`${l.name} (${l.type})`, {
                    permanent: false,
                    direction: 'top'
                });
            });
        }

        const GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));

        let map = null;
        let landmarkMarker = null;
        let propertyMarker = null;
        let routeLine = null;

        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.textContent = message;
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            setupEventListeners();
            createExtraLandmarks();

            const loadingEl = document.getElementById('mapLoading');
            if (loadingEl) { loadingEl.style.display = 'none'; }
        });

        function createLandmarkMarker() {
            if (!map) return;
            if (landmarkMarker) { map.removeLayer(landmarkMarker); }

            landmarkMarker = L.marker([landmarkLat, landmarkLng], {
                icon: L.divIcon({
                    className: 'custom-marker-landmark',
                    html: `
                        <div style="background:#1d4ed8; color:black; max-width:30px; max-height:30px; padding:4px 8px; border-radius:9999px; border:2px solid white; display:flex; flex-direction:column; align-items:center; justify-content:center; font-weight:bold; font-size:10px; box-shadow:0 2px 5px rgba(0,0,0,0.3); text-align:center;">
                            🎓
                            <span style="font-size:8px;line-height:1;margin-top:2px;">UNIVERSITY</span>
                        </div>
                    `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                })
            }).addTo(map);
        }

        function updatePropertyMarker(lat, lng) {
            if (!map) return;
            if (propertyMarker) { map.removeLayer(propertyMarker); }

            propertyMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker-property',
                    html: `
                        <div style="background:#ef4444; color:white; width:35px; height:35px; border-radius:50%; border:3px solid white; display:flex; align-items:center; justify-content:center; font-weight:bold; box-shadow:0 2px 5px rgba(0,0,0,0.3);">
                            📍
                        </div>
                    `,
                    iconSize: [35, 35],
                    iconAnchor: [17.5, 35]
                })
            }).addTo(map);

            document.getElementById('latDisplay').textContent = lat.toFixed(6);
            document.getElementById('lngDisplay').textContent = lng.toFixed(6);
            document.getElementById('mapLat').value = lat;
            document.getElementById('mapLng').value = lng;
            
            const statusEl = document.getElementById('locationStatus');
            statusEl.textContent = 'Location set! ✓';
            statusEl.style.color = '#10b981';

            const currentZoom = map.getZoom();
            map.setView([lat, lng], currentZoom > 16 ? currentZoom : 16);

            if (GEOAPIFY_API_KEY) {
                fetchRouteFromLandmark(lat, lng);
            }
        }

        async function fetchRouteFromLandmark(destLat, destLng) {
            try {
                const pathDisplay = document.getElementById('pathDisplay');
                const distanceDisplay = document.getElementById('distanceDisplay');
                pathDisplay.textContent = 'Calculating route...';
                distanceDisplay.textContent = '–';

                const url = `https://api.geoapify.com/v1/routing?waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}&mode=drive&details=route_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`;

                const res = await fetch(url);
                if (!res.ok) throw new Error('Routing request failed');
                const data = await res.json();

                if (!data.features || !data.features.length) throw new Error('No route found');

                const feature = data.features[0];
                const coords = feature.geometry.coordinates[0];
                const distanceKm = (feature.properties.distance / 1000).toFixed(2);

                if (routeLine) { map.removeLayer(routeLine); }

                routeLine = L.polyline(coords.map(c => [c[1], c[0]]), {
                    color: '#10b981', weight: 4, opacity: 0.8
                }).addTo(map);

                map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });

                pathDisplay.textContent = 'Driving route from university to selected property.';
                distanceDisplay.textContent = `${distanceKm} km`;
            } catch (err) {
                console.error('Error fetching route:', err);
                document.getElementById('pathDisplay').textContent = 'Could not calculate route.';
            }
        }

        function setupEventListeners() {
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

                    Array.from(e.target.files).slice(0, 5).forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${ev.target.result}" class="w-full h-24 object-cover rounded-xl border border-slate-200">
                                <button type="button" onclick="this.parentElement.remove()" 
                                        class="absolute top-1 right-1 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-rose-600">×</button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        }

        window.useManualCoordinates = function() {
            const lat = parseFloat(document.getElementById('manualLat').value);
            const lng = parseFloat(document.getElementById('manualLng').value);

            if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                alert('Please enter valid coordinates');
                return;
            }

            if (map) {
                updatePropertyMarker(lat, lng);
                document.getElementById('manualLat').value = '';
                document.getElementById('manualLng').value = '';
            }
        };

        function initMap() {
            try {
                if (typeof L === 'undefined') return;
                map = L.map('locationMap').setView([landmarkLat, landmarkLng], 15);

                map.whenReady(() => {
                    showToast('Map loaded successfully!');
                    createLandmarkMarker();
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors', maxZoom: 19,
                }).addTo(map);

                map.on('click', function(e) {
                    updatePropertyMarker(e.latlng.lat, e.latlng.lng);
                });
            } catch (error) {
                console.error('Error loading map:', error);
            }
        }
    </script>
</x-app-layout>