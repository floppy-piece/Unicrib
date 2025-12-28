{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <style>
        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding-top: 16px;
            padding-bottom: 24px;
        }
        .uc-container {
            max-width: 1040px;
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
            padding: 18px 18px 20px;
        }

        .uc-header-top {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        @media (min-width: 768px) {
            .uc-header-top {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .uc-title-main {
            font-size: 20px;
            font-weight: 600;
            color: #f9fafb;
            margin: 0;
        }
        .uc-subline {
            margin-top: 4px;
            font-size: 13px;
            color: #9ca3af;
        }
        .uc-landmark {
            margin-top: 2px;
            font-size: 11px;
            color: #6b7280;
        }

        .uc-price-box {
            text-align: right;
        }
        .uc-price-main {
            font-size: 18px;
            font-weight: 600;
            color: #93c5fd;
        }
        .uc-price-sub {
            font-size: 12px;
            color: #9ca3af;
        }
        .uc-btn-primary {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #4f46e5;
            background-color: #6366f1;
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .uc-btn-primary:hover {
            background-color: #4f46e5;
        }

        /* Photos */
        .uc-block-title {
            font-size: 16px;
            font-weight: 600;
            color: #e5e7eb;
            margin-bottom: 8px;
        }
        .uc-photos-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        @media (min-width: 768px) {
            .uc-photos-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .uc-photo-wrap {
            overflow: hidden;
            border-radius: 10px;
            border: 1px solid #1f2937;
        }
        .uc-photo-wrap img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        /* Description & layout */
        .uc-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: 18px;
            margin-top: 16px;
        }
        @media (max-width: 768px) {
            .uc-main-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
        .uc-text-block {
            font-size: 13px;
            color: #e5e7eb;
        }
        .uc-text-block p {
            margin: 0;
            white-space: pre-line;
        }

        .uc-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 8px;
            font-size: 13px;
            color: #e5e7eb;
            margin-top: 4px;
        }
        @media (min-width: 640px) {
            .uc-detail-grid {
                grid-template-columns: repeat(3, minmax(0,1fr));
            }
        }
        .uc-detail-label {
            font-weight: 500;
        }

        .uc-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 4px;
        }
        .uc-amenity-pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            border: 1px solid #1d4ed8;
            background-color: rgba(37,99,235,0.16);
            color: #bfdbfe;
        }

        /* Map */
        #propertyMap {
            height: 320px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #1f2937;
            margin-top: 8px;
            background-color: #020617;
        }
        #propertyMap,
        #propertyMap .leaflet-container,
        #propertyMap .leaflet-interactive,
        #propertyMap .leaflet-marker-icon,
        #propertyMap .leaflet-bar a,
        #propertyMap .leaflet-control {
            cursor: default !important;
        }
        .uc-map-note {
            margin-top: 6px;
            font-size: 11px;
            color: #9ca3af;
        }

        .path-display {
            background-color: #022c22;
            border: 1px solid #16a34a;
            border-radius: 8px;
            padding: 8px 10px;
            margin-top: 8px;
            font-size: 12px;
            color: #dcfce7;
        }
        .path-display strong {
            font-weight: 600;
        }

        .uc-coords {
            font-size: 11px;
            color: #9ca3af;
        }
        .uc-coords p {
            margin: 2px 0;
        }

        /* Back link */
        .uc-footer {
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px solid #111827;
        }
        .uc-back-link {
            font-size: 12px;
            color: #9ca3af;
            text-decoration: none;
        }
        .uc-back-link:hover {
            text-decoration: underline;
        }

        /* Toast */
        #toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background:#020617;
            color:#f9fafb;
            padding:0.75rem 1rem;
            border-radius:0.5rem;
            border: 1px solid #1f2937;
            opacity:0;
            transition:opacity .2s;
            z-index:9999;
            font-size: 0.85rem;
        }
    </style>

    <x-slot name="header">
        <h2 class="uc-header-title">
            {{ $property->title }}
        </h2>
    </x-slot>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    {{-- Toast --}}
    <div id="toast"></div>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                {{-- Top section --}}
                <div class="uc-header-top">
                    <div>
                        <h1 class="uc-title-main">
                            {{ $property->title }}
                        </h1>
                        <p class="uc-subline">
                            {{ ucfirst($property->property_type) }} • Area: {{ ucfirst($property->area) }}
                            @if($property->street)
                                • {{ $property->street }}
                            @endif
                            @if($property->building_name)
                                • {{ $property->building_name }}
                            @endif
                        </p>
                        @if($property->landmark)
                            <p class="uc-landmark">
                                Landmark: {{ $property->landmark }}
                            </p>
                        @endif
                    </div>
                    <div class="uc-price-box">
                        <p class="uc-price-main">
                            KES {{ number_format($property->price) }}
                        </p>
                        <p class="uc-price-sub">
                            per month
                        </p>
                       
                        {{-- property info and map here, always visible --}}

                        @auth
                            @if(auth()->user()->category === 'student')
                                @php
                                    $user = auth()->user();

                                    // Active booking for THIS property
                                    $activeBooking = \App\Models\Booking::where('student_id', $user->id)
                                        ->where('property_id', $property->id)
                                        ->whereIn('status', ['pending', 'approved'])
                                        ->latest()
                                        ->first();

                                    $hasActiveBooking = !is_null($activeBooking);
                                @endphp

                                @if($property->isFull() && !$hasActiveBooking)
                                    <p style="margin-top:8px;font-size:13px;color:#f97316;">
                                        This property is fully booked. You can still view its details and map.
                                    </p>
                                @elseif($hasActiveBooking)
                                    <p style="margin-top:8px;font-size:13px;color:#9ca3af;">
                                        You already have an active booking for this property.
                                    </p>

                                    {{-- Cancel booking button --}}
                                    <form method="POST"
                                        action="{{ route('student.bookings.cancel', $activeBooking->id) }}"
                                        style="margin-top:8px;">
                                        @csrf
                                        <button type="submit"
                                                class="uc-btn-primary"
                                                style="background-color:#b91c1c;border-color:#7f1d1d;">
                                            Cancel booking
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('student.bookings.store', $property->id) }}">
                                        @csrf
                                        <button type="submit" class="uc-btn-primary">
                                            Book this property
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endauth




                    </div>
                </div>

                {{-- Images --}}
                @if(is_array($property->images) && count($property->images))
                    <div style="margin-top:18px;">
                        <h3 class="uc-block-title">Photos</h3>
                        <div class="uc-photos-grid">
                            @foreach($property->images as $image)
                                @php
                                    $path = preg_replace('/^public\//', '', $image);
                                @endphp
                                <div class="uc-photo-wrap">
                                    <img src="{{ asset('storage/'.$path) }}"
                                         alt="Property image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Description & details --}}
                <div class="uc-main-grid">
                    <div class="space-y-4" style="margin-top:16px;">
                        <div>
                            <h3 class="uc-block-title">Description</h3>
                            <div class="uc-text-block">
                                <p>{{ $property->description }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="uc-block-title">Details</h3>
                            <div class="uc-detail-grid">
                                <div>
                                    <span class="uc-detail-label">Bedrooms:</span>
                                    <span>{{ $property->bedrooms }}</span>
                                </div>
                                <div>
                                    <span class="uc-detail-label">Bathrooms:</span>
                                    <span>{{ $property->bathrooms }}</span>
                                </div>
                                <div>
                                    <span class="uc-detail-label">Size:</span>
                                    <span>{{ $property->size }} sqm</span>
                                </div>
                                <div style="grid-column:1/-1;">
                                    <span class="uc-detail-label">Address:</span>
                                    <span>{{ $property->address }}</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($property->amenities) && count($property->amenities))
                            <div>
                                <h3 class="uc-block-title">Amenities</h3>
                                <div class="uc-amenities">
                                    @foreach($property->amenities as $amenity)
                                        <span class="uc-amenity-pill">
                                            {{ ucfirst($amenity) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Map + route --}}
                    <div style="margin-top:16px;">
                        <div>
                            <h3 class="uc-block-title">Location & Route</h3>
                            <div id="propertyMap"></div>
                            <p class="uc-map-note">
                                🎓 University marker is fixed. 📍 shows this property location.
                            </p>
                        </div>

                        <div class="uc-coords" style="margin-top:8px;">
                            <p>
                                <span class="uc-detail-label">Property Lat:</span>
                                {{ $property->map_lat ?? $property->latitude ?? 'N/A' }}
                            </p>
                            <p>
                                <span class="uc-detail-label">Property Lng:</span>
                                {{ $property->map_lng ?? $property->longitude ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="path-display">
                            <div>
                                <strong>Route from University:</strong>
                                <span id="pathDisplay">No route calculated yet.</span>
                            </div>
                            <div style="margin-top:4px;">
                                <strong>Distance:</strong>
                                <span id="distanceDisplay">–</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back --}}
                <div class="uc-footer">
                    <a href="{{ route('student.dashboard') }}" class="uc-back-link">
                        ← Back to properties
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="toaster"
         style="position:fixed;top:1rem;left:1rem;z-index:9999;
                background:#020617;color:#f9fafb;border:1px solid #1f2937;
                padding:0.7rem 1rem;border-radius:0.6rem;
                opacity:0;transition:opacity .2s ease;">
    </div>

    @if(session('booking_status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toast = document.getElementById('toaster');
                if (!toast) return;
                const data = @json(session('booking_status'));
                toast.textContent = data.message;
                toast.style.opacity = '1';
                if (data.type === 'error') {
                    toast.style.borderColor = '#b91c1c';
                    toast.style.color = '#fecaca';
                } else {
                    toast.style.borderColor = '#16a34a';
                }
                setTimeout(() => {
                    toast.style.opacity = '0';
                }, 3000);
            });
        </script>
    @endif

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
                const coords = feature.geometry.coordinates[0];
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
                    '<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:11px;color:#6b7280;">Location not provided</div>';
            }
        });
    </script>
</x-app-layout>
