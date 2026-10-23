<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ $property->building_name }}
        </h2>
    </x-slot>

    {{-- Geoapify API Key Meta Tag --}}
    <meta name="geoapify-key" content="{{ config('services.geoapify.key') }}">

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    {{-- Toast --}}
    <div id="toast" 
         @if(session('booking_status')) data-session='@json(session('booking_status'))' @endif 
         class="fixed top-4 right-4 bg-slate-900 dark:bg-slate-800 text-white px-4 py-3 rounded-xl border border-slate-700 dark:border-slate-700 opacity-0 transition-opacity duration-200 z-50 text-xs shadow-lg">
    </div>

    <div class="py-6 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                
                {{-- Top section --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                            {{ $property->building_name }}
                        </h1>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">
                            {{ ucfirst($property->property_type) }} • Area: {{ ucfirst($property->area) }}
                            @if($property->street)
                                • {{ $property->street }}
                            @endif
                            @if($property->title)
                                • {{ $property->title }}
                            @endif
                        </p>
                        @if($property->landmark)
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                Landmark: {{ $property->landmark }}
                            </p>
                        @endif

                        <div class="mt-2">
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Landlord phone:
                                <a href="tel:{{ optional($property->landlord)->phone }}" class="text-slate-900 dark:text-slate-100 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 underline">
                                    {{ optional($property->landlord)->phone ?? 'No phone set' }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="text-left md:text-right flex flex-col items-start md:items-end">
                        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            KES {{ number_format($property->price) }}
                        </p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50">
                                            This property is fully booked. You can still view its details and map.
                                        </span>
                                    </div>
                                @elseif($hasActiveBooking)
                                    <div class="mt-3 space-y-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            You have an active booking for this property. To book another property contact the landlord of this Property and request for cancellation.
                                        </span>

                                        @if($user && $user->category === 'student' && isset($activeTransport))
                                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1">
                                                <div>
                                                    Transport status:
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $activeTransport->status === 'completed' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50' : ($activeTransport->status === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900/50') }}">
                                                        {{ ucfirst($activeTransport->status) }}
                                                    </span>
                                                </div>

                                                @if($activeTransport->assigned_at && $activeTransport->status !== 'completed')
                                                    <div>
                                                        Assigned time: <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $activeTransport->assigned_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <div>
                                                        Time until transport: <span id="transport-countdown" data-deadline="{{ $activeTransport->assigned_at->toIso8601String() }}" class="text-indigo-600 dark:text-indigo-400 font-bold">calculating...</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($user && $user->category === 'student' && isset($booking))
                                            <button type="button" id="openTransportModalButton" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-500 transition">
                                                Request transport
                                            </button>

                                            {{-- Transport Modal --}}
                                            <div id="transportRequestModal" class="fixed inset-0 z-50 hidden">
                                                <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm uc-modal-backdrop"></div>
                                                <div class="relative max-w-lg mx-auto my-20 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
                                                    <div class="px-6 py-4 bg-indigo-600 dark:bg-indigo-700 flex items-center justify-between text-white">
                                                        <h5 class="text-sm font-bold">Request transport to {{ $property->building_name }}</h5>
                                                        <button type="button" id="closeTransportModalButton" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                    </div>

                                                    <form action="{{ route('transport.request.store', $booking->id) }}" method="POST" class="p-6 space-y-4">
                                                        @csrf
                                                        <div>
                                                            <label for="student_name" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                                                            <input type="text" id="student_name" name="student_name" class="..." value="{{ old('student_name', $user->name) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="student_phone" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Phone number</label>
                                                            <input type="text" id="student_phone" name="student_phone" class="..." value="{{ old('student_phone', $user->phone) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="requested_date" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Preferred transport date</label>
                                                            <input type="date" id="requested_date" name="requested_date" class="..." value="{{ old('requested_date') }}" required>
                                                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                                                                Transport slots run between 8:00am and 10:00pm. The system will assign you the next available 2‑hour slot.
                                                            </p>
                                                        </div>

                                                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                                                            <button type="button" class="..." id="closeTransportModalButton2">Close</button>
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
                                        <p class="text-xs text-rose-600 dark:text-rose-400 font-medium">
                                            Make sure to contact the owner of this property before booking.
                                        </p>
                                        
                                        <button onclick="openPaymentModal({{ $property->id }})" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-500 transition">
                                            <span>Pay & Book Now</span>
                                        </button>

                                        {{-- Payment Modal --}}
                                        <div id="payment-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full overflow-hidden">
                                                <div class="px-6 py-4 bg-indigo-600 dark:bg-indigo-700 text-white flex items-center justify-between">
                                                    <div>
                                                        <h2 class="text-sm font-bold">Book & Pay Securely</h2>
                                                        <p class="text-[11px] text-indigo-100">Complete payment to book {{ $property->building_name ?? 'Property' }}</p>
                                                    </div>
                                                    <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                </div>

                                                <form method="POST" action="{{ route('properties.pay-and-book', $property->id) }}" class="p-6 space-y-4">
                                                    @csrf            
                                                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">

                                                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                                                        <div>
                                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Assurance fee Required (5%)</p>
                                                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">
                                                                KES {{ number_format(ceil(5/100 * ($property->price ?? 0))) }}
                                                            </p>
                                                        </div>
                                                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-400 bg-white dark:bg-slate-800 px-2.5 py-1 rounded-full border border-slate-200 dark:border-slate-700">
                                                            of KES {{ number_format($property->price ?? 0) }}
                                                        </span>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                                    </div>

                                                    <div class="flex items-start gap-2">
                                                        <input type="checkbox" name="terms_agreement" id="terms" required class="mt-0.5 h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-800">
                                                        <label for="terms" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed cursor-pointer">
                                                            I agree to pay and book this property. Payment is non-refundable unless landlord rejects.
                                                        </label>
                                                    </div>

                                                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                                                        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 dark:hover:bg-indigo-500 transition">
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
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-3">Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->images as $image)
                                @php
                                    $path = preg_replace('/^public\//', '', $image);
                                @endphp
                                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800">
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
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Description</h3>
                            <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                                {{ $property->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Details</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/80">
                                <div>
                                    <span class="font-medium text-slate-400 dark:text-slate-400 block text-[11px]">Bedrooms</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->bedrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-400 dark:text-slate-400 block text-[11px]">Bathrooms</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->bathrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-400 dark:text-slate-400 block text-[11px]">Size</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->size }} sqm</span>
                                </div>
                                <div class="col-span-2 sm:col-span-3 pt-2 border-t border-slate-200 dark:border-slate-700">
                                    <span class="font-medium text-slate-400 dark:text-slate-400 block text-[11px]">Address</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->address }}</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($property->amenities) && count($property->amenities))
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($property->amenities as $amenity)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
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
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Location & Route</h3>
                            <div id="propertyMap" 
                                 data-lat="{{ $property->map_lat ?? $property->latitude }}" 
                                 data-lng="{{ $property->map_lng ?? $property->longitude }}" 
                                 class="h-80 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5">
                                🎓 University marker is fixed. 📍 shows this property location.
                            </p>
                        </div>

                        <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5">
                            <p><span class="font-medium text-slate-700 dark:text-slate-300">Property Lat:</span> {{ $property->map_lat ?? $property->latitude ?? 'N/A' }}</p>
                            <p><span class="font-medium text-slate-700 dark:text-slate-300">Property Lng:</span> {{ $property->map_lng ?? $property->longitude ?? 'N/A' }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-xs text-emerald-900 dark:text-emerald-300 space-y-1">
                            <div>
                                <strong class="font-semibold">Route from University:</strong>
                                <span id="pathDisplay" class="block text-emerald-700 dark:text-emerald-400">No route calculated yet.</span>
                            </div>
                            <div>
                                <strong class="font-semibold">Distance:</strong>
                                <span id="distanceDisplay" class="font-bold text-emerald-800 dark:text-emerald-200">–</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back --}}
                <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('student.dashboard') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition flex items-center gap-1">
                        &larr; Back to properties
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>