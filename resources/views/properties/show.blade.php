{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">
            {{ $property->building_name }}
        </h2>
    </x-slot>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    {{-- Toast --}}
    <div id="toast" class="fixed top-4 right-4 bg-slate-900 text-white px-4 py-3 rounded-xl border border-slate-700 opacity-0 transition-opacity duration-200 z-50 text-xs shadow-lg"></div>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                
                {{-- Top section --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">
                            {{ $property->building_name }}
                        </h1>
                        <p class="text-xs font-medium text-slate-500 mt-1">
                            {{ ucfirst($property->property_type) }} • Area: {{ ucfirst($property->area) }}
                            @if($property->street)
                                • {{ $property->street }}
                            @endif
                            @if($property->title)
                                • {{ $property->title }}
                            @endif
                        </p>
                        @if($property->landmark)
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Landmark: {{ $property->landmark }}
                            </p>
                        @endif

                        <div class="mt-2">
                            <p class="text-xs text-slate-600">
                                Landlord phone:
                                <a href="tel:{{ optional($property->landlord)->phone }}" class="text-slate-900 font-bold hover:text-indigo-600 underline">
                                    {{ optional($property->landlord)->phone ?? 'No phone set' }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="text-left md:text-right flex flex-col items-start md:items-end">
                        <p class="text-xl font-bold text-indigo-600">
                            KES {{ number_format($property->price) }}
                        </p>
                        <p class="text-xs text-slate-400">
                            per month
                        </p>
                       
                        @auth
                            @if(auth()->user()->category === 'student')
                                @php
                                    $user = auth()->user();

                                    $activeBooking = \App\Models\Booking::where('student_id', $user->id)
                                        ->where('property_id', $property->id)
                                        ->whereIn('status', ['pending', 'approved'])
                                        ->latest()
                                        ->first();

                                    $hasActiveBooking = !is_null($activeBooking);
                                    
                                    $unavailable = (int)($property->bookings()->where('status', 'approved')->count() + (int)($property->booked_count));
                                
                                    $currentBooked = $unavailable;
                                    $capacity = (int) ($property->capacity ?? 0);
                                    $isFull = $capacity > 0 && $currentBooked >= $capacity;
                                @endphp

                                @if($isFull && !$hasActiveBooking)
                                    <div class="mt-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            This property is fully booked. You can still view its details and map.
                                        </span>
                                    </div>
                                @elseif($hasActiveBooking)
                                    <div class="mt-3 space-y-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            You have an active booking for this property. To book another property contact the landlord of this Property and request for cancellation.
                                        </span>

                                        @if($user && $user->category === 'student' && isset($activeTransport))
                                            <div class="text-xs text-slate-500 space-y-1">
                                                <div>
                                                    Transport status:
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $activeTransport->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($activeTransport->status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200') }}">
                                                        {{ ucfirst($activeTransport->status) }}
                                                    </span>
                                                </div>

                                                @if($activeTransport->assigned_at && $activeTransport->status !== 'completed')
                                                    <div>
                                                        Assigned time: <span class="text-slate-700 font-medium">{{ $activeTransport->assigned_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <div>
                                                        Time until transport: <span id="transport-countdown" data-deadline="{{ $activeTransport->assigned_at->toIso8601String() }}" class="text-indigo-600 font-bold">calculating...</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($user && $user->category === 'student' && isset($booking))
                                            <button type="button" id="openTransportModalButton" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 transition">
                                                Request transport
                                            </button>

                                            {{-- Transport Modal --}}
                                            <div id="transportRequestModal" class="fixed inset-0 z-50 hidden">
                                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm uc-modal-backdrop"></div>
                                                <div class="relative max-w-lg mx-auto my-20 bg-white rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
                                                    <div class="px-6 py-4 bg-indigo-600 flex items-center justify-between text-white">
                                                        <h5 class="text-sm font-bold">Request transport to {{ $property->building_name }}</h5>
                                                        <button type="button" id="closeTransportModalButton" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                    </div>

                                                    <form action="{{ route('transport.request.store', $booking->id) }}" method="POST" class="p-6 space-y-4">
                                                        @csrf
                                                        <div>
                                                            <label for="student_name" class="block text-xs font-medium text-slate-700 mb-1">Name</label>
                                                            <input type="text" id="student_name" name="student_name" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('student_name', $user->name) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="student_phone" class="block text-xs font-medium text-slate-700 mb-1">Phone number</label>
                                                            <input type="text" id="student_phone" name="student_phone" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('student_phone', $user->phone) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="requested_date" class="block text-xs font-medium text-slate-700 mb-1">Preferred transport date</label>
                                                            <input type="date" id="requested_date" name="requested_date" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('requested_date') }}" required>
                                                            <p class="text-[11px] text-slate-400 mt-1">
                                                                Transport slots run between 8:00am and 10:00pm. The system will assign you the next available 2‑hour slot.
                                                            </p>
                                                        </div>

                                                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                                                            <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition" id="closeTransportModalButton2">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                                                Send request
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-3 space-y-3">
                                        <p class="text-xs text-rose-600 font-medium">
                                            Make sure to contact the owner of this property before booking.
                                        </p>
                                        
                                        <button onclick="openPaymentModal({{ $property->id }})" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition">
                                            <span>Pay & Book Now</span>
                                        </button>

                                        {{-- Payment Modal --}}
                                        <div id="payment-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden">
                                                <div class="px-6 py-4 bg-indigo-600 text-white flex items-center justify-between">
                                                    <div>
                                                        <h2 class="text-sm font-bold">Book & Pay Securely</h2>
                                                        <p class="text-[11px] text-indigo-100">Complete payment to book {{ $property->building_name ?? 'Property' }}</p>
                                                    </div>
                                                    <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                </div>

                                                <form method="POST" action="{{ route('properties.pay-and-book', $property->id) }}" class="p-6 space-y-4">
                                                    @csrf            
                                                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">

                                                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                                        <div>
                                                            <p class="text-xs font-medium text-slate-500">Assurance fee Required (5%)</p>
                                                            <p class="text-lg font-bold text-indigo-600 mt-0.5">
                                                                KES {{ number_format(ceil(5/100 * ($property->price ?? 0))) }}
                                                            </p>
                                                        </div>
                                                        <span class="text-[11px] font-semibold text-slate-400 bg-white px-2.5 py-1 rounded-full border border-slate-200">
                                                            of KES {{ number_format($property->price ?? 0) }}
                                                        </span>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                                                    </div>

                                                    <div class="flex items-start gap-2">
                                                        <input type="checkbox" name="terms_agreement" id="terms" required class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                        <label for="terms" class="text-xs text-slate-600 leading-relaxed cursor-pointer">
                                                            I agree to pay and book this property. Payment is non-refundable unless landlord rejects.
                                                        </label>
                                                    </div>

                                                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                                                        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                                            Pay & Book Now
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Images --}}
                @if(is_array($property->images) && count($property->images))
                    <div class="mt-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->images as $image)
                                @php
                                    $path = preg_replace('/^public\//', '', $image);
                                @endphp
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <img src="{{ asset('storage/'.$path) }}" alt="Property image" class="w-full h-40 object-cover hover:scale-105 transition duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Description & details --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2">Description</h3>
                            <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $property->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2">Details</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div>
                                    <span class="font-medium text-slate-400 block text-[11px]">Bedrooms</span>
                                    <span class="font-bold text-slate-800">{{ $property->bedrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-400 block text-[11px]">Bathrooms</span>
                                    <span class="font-bold text-slate-800">{{ $property->bathrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-400 block text-[11px]">Size</span>
                                    <span class="font-bold text-slate-800">{{ $property->size }} sqm</span>
                                </div>
                                <div class="col-span-2 sm:col-span-3 pt-2 border-t border-slate-200">
                                    <span class="font-medium text-slate-400 block text-[11px]">Address</span>
                                    <span class="font-bold text-slate-800">{{ $property->address }}</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($property->amenities) && count($property->amenities))
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 mb-2">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($property->amenities as $amenity)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
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
                            <h3 class="text-sm font-bold text-slate-900 mb-2">Location & Route</h3>
                            <div id="propertyMap" class="h-80 w-full rounded-xl border border-slate-200 bg-slate-100"></div>
                            <p class="text-[11px] text-slate-400 mt-1.5">
                                🎓 University marker is fixed. 📍 shows this property location.
                            </p>
                        </div>

                        <div class="text-[11px] text-slate-500 space-y-0.5">
                            <p><span class="font-medium text-slate-700">Property Lat:</span> {{ $property->map_lat ?? $property->latitude ?? 'N/A' }}</p>
                            <p><span class="font-medium text-slate-700">Property Lng:</span> {{ $property->map_lng ?? $property->longitude ?? 'N/A' }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                            <div>
                                <strong class="font-semibold">Route from University:</strong>
                                <span id="pathDisplay" class="block text-emerald-700">No route calculated yet.</span>
                            </div>
                            <div>
                                <strong class="font-semibold">Distance:</strong>
                                <span id="distanceDisplay" class="font-bold text-emerald-800">–</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back --}}
                <div class="mt-8 pt-4 border-t border-slate-100">
                    <a href="{{ route('student.dashboard') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        &larr; Back to properties
                    </a>
                </div>

            </div>
        </div>
    </div>

    @if(session('booking_status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toast = document.getElementById('toast');
                if (!toast) return;
                const data = @json(session('booking_status'));
                toast.textContent = data.message;
                toast.style.opacity = '1';
                if (data.type === 'error') {
                    toast.classList.remove('bg-slate-900', 'border-slate-700');
                    toast.classList.add('bg-rose-900', 'border-rose-700');
                } else {
                    toast.classList.remove('bg-slate-900', 'border-slate-700');
                    toast.classList.add('bg-emerald-900', 'border-emerald-700');
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

        const landmarks = [
            { name: 'skymart Supermarket', type: 'supermarket', lat: -0.567700, lng: 37.324526 },
            { name: 'cooperative Bank', type: 'bank', lat: -0.5667250, lng: 37.324600 },
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
            if (!map) return;
            landmarks.forEach(l => {
                const { emoji, bg } = getLandmarkIcon(l.type);
                L.marker([l.lat, l.lng], {
                    icon: L.divIcon({
                        className: 'custom-marker-extra-landmark',
                        html: `<div style="background:${bg};color:black;max-width:30px;max-height:30px;padding:4px 8px;border-radius:9999px;border:2px solid white;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:bold;gap:4px;text-align:center;font-size:10px;box-shadow:0 2px 5px rgba(0,0,0,0.3);white-space:nowrap;"><span>${emoji}</span><span style="font-size:8px;line-height:1;margin-top:2px;">${l.name}</span></div>`,
                        iconSize: [40, 24],
                        iconAnchor: [20, 24]
                    })
                }).addTo(map).bindTooltip(`${l.name} (${l.type})`, { permanent: false, direction: 'top' });
            });
        }

        function createLandmarkMarker() {
            if (!map) return;
            if (landmarkMarker) map.removeLayer(landmarkMarker);
            landmarkMarker = L.marker([landmarkLat, landmarkLng], {
                icon: L.divIcon({
                    className: 'custom-marker-landmark',
                    html: `<div style="background:#1d4ed8;color:white;min-width:40px;min-height:40px;padding:4px 8px;border-radius:9999px;border:3px solid white;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:bold;font-size:10px;box-shadow:0 2px 5px rgba(0,0,0,0.3);text-align:center;">🎓<span style="font-size:8px;line-height:1;margin-top:2px;">UNIVERSITY</span></div>`,
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
                    html: `<div style="background:#ef4444;color:white;width:35px;height:35px;border-radius:50%;border:3px solid white;display:flex;align-items:center;justify-content:center;font-weight:bold;box-shadow:0 2px 5px rgba(0,0,0,0.3);">📍</div>`,
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

                const url = `https://api.geoapify.com/v1/routing?waypoints=${landmarkLat},${landmarkLng}|${destLat},${destLng}&mode=drive&details=route_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`;

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
                    createExtraLandmarks();
                    updatePropertyMarker(lat, lng);
                    fetchRouteFromLandmark(lat, lng);
                });
            } else {
                document.getElementById('propertyMap').innerHTML = '<div class="flex items-center justify-center h-full text-xs text-slate-400">Location not provided</div>';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn  = document.getElementById('openTransportModalButton');
            const modal    = document.getElementById('transportRequestModal');
            const closeBtn = document.getElementById('closeTransportModalButton');
            const closeBtn2= document.getElementById('closeTransportModalButton2');

            if (modal && openBtn) {
                function openModal() {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }

                openBtn.addEventListener('click', openModal);
                if (closeBtn)  closeBtn.addEventListener('click', closeModal);
                if (closeBtn2) closeBtn2.addEventListener('click', closeModal);
            }

            const countdownEl = document.getElementById('transport-countdown');
            if (!countdownEl) return;

            const deadlineStr = countdownEl.getAttribute('data-deadline');
            if (!deadlineStr) return;

            const deadline = new Date(deadlineStr).getTime();

            function updateCountdown() {
                const now    = new Date().getTime();
                const diff   = deadline - now;

                if (diff <= 0) {
                    countdownEl.textContent = 'Transport time reached';
                    clearInterval(timer);
                    return;
                }

                const second = 1000;
                const minute = 60 * second;
                const hour   = 60 * minute;

                const hours   = Math.floor(diff / hour);
                const minutes = Math.floor((diff % hour) / minute);
                const seconds = Math.floor((diff % minute) / second);

                let parts = [];
                if (hours > 0)   parts.push(hours + 'h');
                if (minutes >= 0) parts.push(minutes + 'm');
                parts.push(seconds + 's');

                countdownEl.textContent = parts.join(' ');
            }

            updateCountdown();
            const timer = setInterval(updateCountdown, 1000);
        });

        function openPaymentModal(propertyId) {
            document.getElementById('payment-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</x-app-layout>