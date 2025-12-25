{{-- resources/views/student/property-show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $property->title }}
        </h2>
    </x-slot>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    <style>
        #propertyMap {
            height: 320px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid #3b82f6;
            margin-top: 10px;
            background-color: #f3f4f6;
        }

        /* Keep normal arrow cursor on map and controls */
        #propertyMap,
        #propertyMap .leaflet-container,
        #propertyMap .leaflet-interactive,
        #propertyMap .leaflet-marker-icon,
        #propertyMap .leaflet-bar a,
        #propertyMap .leaflet-control {
            cursor: default !important;
        }

        .path-display {
            background-color: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .dark .path-display {
            background-color: #064e3b;
            border-color: #16a34a;
            color: #dcfce7;
        }
    </style>

    {{-- Toast --}}
    <div id="toast" style="
        position:fixed;top:1rem;right:1rem;
        background:#111827;color:#f9fafb;
        padding:0.75rem 1rem;border-radius:0.5rem;
        opacity:0;transition:opacity .2s;z-index:9999;
    "></div>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-8">

                {{-- Top section --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $property->title }}
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ ucfirst($property->property_type) }} • Area: {{ ucfirst($property->area) }}
                            @if($property->street)
                                • {{ $property->street }}
                            @endif
                            @if($property->building_name)
                                • {{ $property->building_name }}
                            @endif
                        </p>
                        @if($property->landmark)
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Landmark: {{ $property->landmark }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                            KES {{ number_format($property->price) }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            per month
                        </p>
                        <form method="POST" action="#">
                            @csrf
                            <x-secondary-button class="mt-3 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Book this property
                            </x-secondary-button>
                        </form>
                    </div>
                </div>

                {{-- Images --}}
                @if(is_array($property->images) && count($property->images))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($property->images as $image)
                                @php
                                    // Normalise path: remove leading "public/" if you stored with it
                                    $path = preg_replace('/^public\//', '', $image);
                                @endphp
                                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                    <img src="{{ asset('storage/'.$path) }}"
                                         alt="Property image"
                                         class="w-full h-40 object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Description & details --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Description</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                {{ $property->description }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Details</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm text-gray-700 dark:text-gray-300">
                                <div>
                                    <span class="font-medium">Bedrooms:</span>
                                    <span>{{ $property->bedrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Bathrooms:</span>
                                    <span>{{ $property->bathrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Size:</span>
                                    <span>{{ $property->size }} sqm</span>
                                </div>
                                <div class="col-span-2 sm:col-span-3">
                                    <span class="font-medium">Address:</span>
                                    <span>{{ $property->address }}</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($property->amenities) && count($property->amenities))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Amenities</h3>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    @foreach($property->amenities as $amenity)
                                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-200 rounded-full border border-blue-200 dark:border-blue-700">
                                            {{ ucfirst($amenity) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Map + route --}}
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Location & Route</h3>
                            <div id="propertyMap"></div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                🎓 University marker is fixed. 📍 shows this property location.
                            </p>
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <p>
                                <span class="font-semibold">Property Lat:</span>
                                {{ $property->map_lat ?? $property->latitude ?? 'N/A' }}
                            </p>
                            <p>
                                <span class="font-semibold">Property Lng:</span>
                                {{ $property->map_lng ?? $property->longitude ?? 'N/A' }}
                            </p>
                        </div>

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
                    </div>
                </div>

                {{-- Back --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <a href="{{ route('student.dashboard') }}"
                       class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 hover:underline">
                        ← Back to properties
                    </a>
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
            setTimeout(() => {
                toast.style.opacity = '0';
            }, duration);
        }

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

        function updatePropertyMarker(lat, lng) {
            if (!map) return;
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
        }

        async function fetchRouteFromLandmark(destLat, destLng) {
            try {
                const pathDisplay = document.getElementById('pathDisplay');
                const distanceDisplay = document.getElementById('distanceDisplay');
                if (pathDisplay) pathDisplay.textContent = 'Calculating route...';
                if (distanceDisplay) distanceDisplay.textContent = '–';

                if (!GEOAPIFY_API_KEY) throw new Error('Geoapify key missing');

                const url = `https://api.geoapify.com/v1/routing?` +
                    `waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}` +
                    `&mode=drive&details=route_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`;

                const res = await fetch(url);
                if (!res.ok) throw new Error('Routing request failed with status ' + res.status);
                const data = await res.json();

                if (!data.features || !data.features.length) throw new Error('No route found');

                const feature = data.features[0];
                const coords = feature.geometry.coordinates[0]; // [lng, lat]
                const distanceMeters = feature.properties.distance;
                const distanceKm = (distanceMeters / 1000).toFixed(2);

                if (routeLine) map.removeLayer(routeLine);

                const latLngs = coords.map(c => [c[1], c[0]]);
                routeLine = L.polyline(latLngs, {
                    color: '#10b981',
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);

                map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });

                if (pathDisplay) pathDisplay.textContent =
                    'Driving route from university to this property.';
                if (distanceDisplay) distanceDisplay.textContent = `${distanceKm} km`;
            } catch (err) {
                console.error('Error fetching route:', err);
                const pathDisplay = document.getElementById('pathDisplay');
                const distanceDisplay = document.getElementById('distanceDisplay');
                if (pathDisplay) pathDisplay.textContent = 'Could not calculate route.';
                if (distanceDisplay) distanceDisplay.textContent = '–';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const lat = @json($property->map_lat ?? $property->latitude);
            const lng = @json($property->map_lng ?? $property->longitude);

            if (lat && lng) {
                map = L.map('propertyMap').setView([landmarkLat, landmarkLng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                map.whenReady(() => {
                    showToast('Map loaded successfully!');
                    createLandmarkMarker();
                    updatePropertyMarker(lat, lng);
                    fetchRouteFromLandmark(lat, lng);
                });

            } else {
                document.getElementById('propertyMap').innerHTML =
                    '<div class="flex items-center justify-center h-full text-xs text-gray-500">Location not provided</div>';
            }
        });
    </script>
</x-app-layout>
