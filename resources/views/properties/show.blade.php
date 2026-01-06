{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <style>
        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding-top: 16px;
            padding-bottom: 24px;
        }
/*
    Modal transport form css
*/

.uc-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
}

.uc-modal.show {
    display: block;
}

/* Backdrop matches dark UniCrib background */
.uc-modal-backdrop {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
    opacity: 0.92;
}

/* Dialog: similar to uc-card but centered and slightly lighter border */
.uc-modal-dialog {
    position: relative;
    max-width: 520px;
    margin: 5rem auto;
    background-color: #020617;
    border-radius: 12px;
    border: 1px solid #1f2937;
    box-shadow: 0 28px 80px rgba(15,23,42,0.9);
    overflow: hidden;
}

/* Header: gradient accent like your primary buttons */
.uc-modal-header {
    padding: 10px 16px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #f9fafb;
}

.uc-modal-title {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
}

/* Close button */
.uc-modal-close {
    border: none;
    background: transparent;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    color: #e5e7eb;
}
.uc-modal-close:hover {
    color: #ffffff;
}

/* Body uses same dark gradient vibe as uc-page */
.uc-modal-body {
    padding: 16px;
    background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
}

/* Inner form card, similar to uc-card */
.uc-modal-form-card {
    background-color: #020617;
    border-radius: 10px;
    border: 1px solid #1f2937;
    box-shadow: 0 18px 50px rgba(15,23,42,0.75);
    padding: 14px 16px;
    text-align:left;
}

/* Labels and helper text */
.uc-modal-form-card label {
    display: block;
    font-size: 12px;
    color: #e5e7eb;
    margin-bottom: 4px;
}

.uc-modal-form-card .form-text {
    font-size: 11px;
    color: #9ca3af;
}

/* Inputs styled for dark theme */
.uc-modal-form-card .form-control {
    background-color: #020617;
    border: 1px solid #1f2937;
    color: #e5e7eb;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 13px;
}
.uc-modal-form-card .form-control::placeholder {
    color: #6b7280;
}
.uc-modal-form-card .form-control:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 1px rgba(99,102,241,0.7);
    background-color: #020617;
}

/* Space between fields */
.uc-modal-form-card .mb-3 {
    margin-bottom: 10px !important;
}

/* Footer with accent buttons */
.uc-modal-footer {
    padding: 10px 16px;
    border-top: 1px solid #111827;
    background-color: #020617;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

/* Primary/secondary buttons in footer following uc-btn-primary style */
.uc-modal-footer .btn-primary {
    border-radius: 999px;
    border: 1px solid #4f46e5;
    background-color: #6366f1;
    color: #ffffff;
    font-size: 13px;
    font-weight: 500;
    padding: 6px 14px;
}
.uc-modal-footer .btn-primary:hover {
    background-color: #4f46e5;
}

.uc-modal-footer .btn-secondary {
    border-radius: 999px;
    border: 1px solid #1f2937;
    background-color: #020617;
    color: #e5e7eb;
    font-size: 13px;
    padding: 6px 14px;
}
.uc-modal-footer .btn-secondary:hover {
    background-color: #111827;
}

/* end of transport form*/

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

                                    @if($user && $user->category === 'student' && $booking)
                                            {{-- Transport status + countdown --}}
                                            @if($activeTransport)
                                                <div class="mt-2" style="font-size:12px;color:#9ca3af;">
                                                    <div>
                                                        Transport status:
                                                        <span class="uc-pill
                                                            {{ $activeTransport->status === 'completed' ? 'uc-pill-green'
                                                                : ($activeTransport->status === 'cancelled' ? 'uc-pill-red' : 'uc-pill-blue') }}">
                                                            {{ ucfirst($activeTransport->status) }}
                                                        </span>
                                                    </div>

                                                    @if($activeTransport->assigned_at && $activeTransport->status !== 'completed')
                                                        <div style="margin-top:4px;">
                                                            Assigned time:
                                                            <span style="color:#e5e7eb;">
                                                                {{ $activeTransport->assigned_at->format('d M Y, H:i') }}
                                                            </span>
                                                        </div>
                                                        <div style="margin-top:4px;">
                                                            Time until transport:
                                                            <span id="transport-countdown"
                                                                data-deadline="{{ $activeTransport->assigned_at->toIso8601String() }}"
                                                                style="color:#93c5fd;">
                                                                calculating...
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @if($user && $user->category === 'student' && $booking)
                                            {{-- Trigger button --}}
                                            <button type="button"
                                                    id="openTransportModalButton"
                                                    class="uc-btn-primary">
                                                Request transport
                                            </button>

                                            {{-- Simple custom modal --}}
                                            <div id="transportRequestModal"
                                                class="uc-modal"
                                                aria-hidden="true">
                                                <div class="uc-modal-backdrop"></div>
                                                <div class="uc-modal-dialog">
                                                    <div class="uc-modal-header">
                                                        <h5 class="uc-modal-title">
                                                            Request transport to {{ $property->name }}
                                                        </h5>
                                                        <button type="button"
                                                                class="uc-modal-close"
                                                                id="closeTransportModalButton">
                                                            ×
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('transport.request.store', $booking->id) }}" method="POST">
                                                        @csrf
                                                        <div class="uc-modal-body">
                                                            <div class="mb-3">
                                                                <label for="student_name">Name</label>
                                                                <input
                                                                    type="text"
                                                                    id="student_name"
                                                                    name="student_name"
                                                                    class="uc-modal-form-card "
                                                                    value="{{ old('student_name', $user->name) }}"
                                                                    required
                                                                >
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="student_phone">Phone number</label>
                                                                <input
                                                                    type="text"
                                                                    id="student_phone"
                                                                    name="student_phone"
                                                                    class="uc-modal-form-card "
                                                                    value="{{ old('student_phone', $user->phone) }}"
                                                                    required
                                                                >
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="requested_date">Preferred transport date</label>
                                                                <input
                                                                    type="date"
                                                                    id="requested_date"
                                                                    name="requested_date"
                                                                    class="uc-modal-form-card "
                                                                    value="{{ old('requested_date') }}"
                                                                    required
                                                                ><br>
                                                                <small class="form-text text-muted">
                                                                    Transport slots run between 8:00am and 10:00pm. The system will assign you the next available 2‑hour slot.
                                                                </small>
                                                            </div>
                                                        </div>

                                                        <div class="uc-modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-secondary"
                                                                    id="closeTransportModalButton2">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Send request
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                    @endif
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
                    createExtraLandmarks();
                    updatePropertyMarker(lat, lng);
                    fetchRouteFromLandmark(lat, lng);
                });

            } else {
                document.getElementById('propertyMap').innerHTML =
                    '<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:11px;color:#6b7280;">Location not provided</div>';
            }
        });
    </script>
    <script>
        //script for transport window
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn  = document.getElementById('openTransportModalButton');
        const modal    = document.getElementById('transportRequestModal');
        const closeBtn = document.getElementById('closeTransportModalButton');
        const closeBtn2= document.getElementById('closeTransportModalButton2');
        const backdrop = modal ? modal.querySelector('.uc-modal-backdrop') : null;

        if (!modal || !openBtn) {
            return;
        }

        function openModal() {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openBtn.addEventListener('click', openModal);

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        if (closeBtn2) {
            closeBtn2.addEventListener('click', closeModal);
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeModal);
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal JS from earlier (open/close) ...
        const openBtn  = document.getElementById('openTransportModalButton');
        const modal    = document.getElementById('transportRequestModal');
        const closeBtn = document.getElementById('closeTransportModalButton');
        const closeBtn2= document.getElementById('closeTransportModalButton2');
        const backdrop = modal ? modal.querySelector('.uc-modal-backdrop') : null;

        if (modal && openBtn) {
            function openModal() {
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openModal);
            if (closeBtn)  closeBtn.addEventListener('click', closeModal);
            if (closeBtn2) closeBtn2.addEventListener('click', closeModal);
            if (backdrop)  backdrop.addEventListener('click', closeModal);
        }

        // Countdown logic
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
</script>


    

</x-app-layout>
