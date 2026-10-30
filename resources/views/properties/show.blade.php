{{-- resources/views/properties/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-[#2e2f2c] dark:text-white">
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
        @if(session('booking_status')) 
            data-session='@json(session('booking_status'))' 
        @elseif(session('success')) 
            data-session='@json(['type' => 'success', 'message' => session('success')])' 
        @elseif(session('error')) 
            data-session='@json(['type' => 'error', 'message' => session('error')])' 
        @endif 
        class="fixed top-4 right-4 bg-[#2e2f2c] dark:bg-[#6a6c6a] text-white px-4 py-3 rounded-xl border border-[#6a6c6a] dark:border-[#8da5c0] opacity-0 transition-opacity duration-200 z-50 text-xs shadow-lg">
    </div>

    <div class="py-6 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                
                {{-- Top section --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-[#cad8e5] dark:border-[#6a6c6a]">
                    <div>
                        <h1 class="text-xl font-bold text-[#2e2f2c] dark:text-white">
                            {{ $property->building_name }}
                        </h1>
                        <p class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5] mt-1">
                            {{ ucfirst($property->property_type) }} • Area: {{ ucfirst($property->area) }}
                            @if($property->street)
                                • {{ $property->street }}
                            @endif
                            @if($property->title)
                                • {{ $property->title }}
                            @endif
                        </p>
                        @if($property->landmark)
                            <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2] mt-0.5">
                                Landmark: {{ $property->landmark }}
                            </p>
                        @endif

                        <div class="mt-2">
                            <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                                Landlord phone:
                                <a href="tel:{{ optional($property->landlord)->phone }}" class="text-[#2e2f2c] dark:text-white font-bold hover:underline">
                                    {{ optional($property->landlord)->phone ?? 'No phone set' }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="text-left md:text-right flex flex-col items-start md:items-end">
                        <p class="text-xl font-bold text-[#2e2f2c] dark:text-[#aabdd2]">
                            KES {{ number_format($property->price) }}
                        </p>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
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
                                    <div class="mt-3 space-y-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#cad8e5] border border-[#aabdd2] dark:border-[#6a6c6a]">
                                            You have an active booking for this property. To book another property contact the landlord of this Property and request for cancellation.
                                        </span>

                                        @php
                                            $activeTransport = \App\Models\Transport::where('student_id', $user->id)
                                                ->where('property_id', $property->id)
                                                ->latest()
                                                ->first();

                                            $transportStatus = $activeTransport ? $activeTransport->status : null;
                                            $isComplete = $transportStatus === 'completed';
                                            $hasActiveRequest = $activeTransport && in_array($transportStatus, ['pending', 'confirmed', 'approved']);
                                        @endphp

                                        @if($activeTransport)
                                            <div class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] space-y-1 p-3 rounded-xl bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 border border-[#cad8e5] dark:border-[#6a6c6a]">
                                                <div>
                                                    Transport status:
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $isComplete ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50' : ($transportStatus === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50' : 'bg-[#cad8e5] dark:bg-[#6a6c6a] text-[#2e2f2c] dark:text-white') }}">
                                                        {{ ucfirst($transportStatus) }}
                                                    </span>
                                                </div>

                                                @if($activeTransport->assigned_at && !$isComplete && $transportStatus !== 'cancelled')
                                                    <div>
                                                        Assigned time: <span class="text-[#2e2f2c] dark:text-white font-medium">{{ $activeTransport->assigned_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <div>
                                                        Time until transport: <span id="transport-countdown" data-deadline="{{ $activeTransport->assigned_at->toIso8601String() }}" class="text-[#2e2f2c] dark:text-[#aabdd2] font-bold">calculating...</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Transport Action Button --}}
                                        <div>
                                            <button type="button" 
                                                    id="openTransportModalButton" 
                                                    @if($isComplete || $hasActiveRequest) disabled @endif
                                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition
                                                    {{ $isComplete || $hasActiveRequest
                                                        ? 'bg-[#cad8e5] dark:bg-[#6a6c6a] text-[#6a6c6a] dark:text-[#aabdd2] cursor-not-allowed opacity-60' 
                                                        : 'bg-[#cad8e5] text-[#2e2f2c] hover:bg-[#aabdd2] dark:bg-[#cad8e5] dark:text-[#2e2f2c] dark:hover:bg-[#aabdd2]' }}">
                                                {{ $isComplete ? 'Transport Completed' : ($hasActiveRequest ? 'Transport Requested (Pending)' : 'Request transport') }}
                                            </button>
                                        </div>

                                        {{-- Transport Modal --}}
                                        @if(!$isComplete && !$hasActiveRequest)
                                            <div id="transportRequestModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-[#2e2f2c]/60 dark:bg-[#2e2f2c]/80 backdrop-blur-sm">
                                                <div class="relative max-w-lg w-full bg-white dark:bg-[#6a6c6a]/30 rounded-2xl border border-[#cad8e5] dark:border-[#8da5c0]/40 shadow-2xl overflow-hidden">
                                                    <div class="px-6 py-4 bg-[#2e2f2c] dark:bg-[#6a6c6a] flex items-center justify-between text-white">
                                                        <h5 class="text-sm font-bold">Request transport to {{ $property->building_name }}</h5>
                                                        <button type="button" id="closeTransportModalButton" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                    </div>

                                                    <form action="{{ route('transport.request.store', $activeBooking->id) }}" method="POST" class="p-6 space-y-4">
                                                        @csrf
                                                        <div>
                                                            <label for="student_name" class="block text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] mb-1">Name</label>
                                                            <input type="text" id="student_name" name="student_name" class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3.5 py-2.5 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" value="{{ old('student_name', $user->name) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="student_phone" class="block text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] mb-1">Phone number</label>
                                                            <input type="text" id="student_phone" name="student_phone" class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3.5 py-2.5 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" value="{{ old('student_phone', $user->phone) }}" required>
                                                        </div>

                                                        <div>
                                                            <label for="requested_date" class="block text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] mb-1">Preferred transport date</label>
                                                            <input type="date" id="requested_date" name="requested_date" class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3.5 py-2.5 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]" value="{{ old('requested_date') }}" required>
                                                            <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2] mt-1">
                                                                Transport slots run between 8:00am and 10:00pm. The system will assign you the next available 2‑hour slot.
                                                            </p>
                                                        </div>

                                                        <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a] flex justify-end gap-2">
                                                            <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#cad8e5] dark:hover:bg-[#6a6c6a] transition" id="closeTransportModalButton2">Close</button>
                                                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] transition">
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
                                        
                                        <button type="button" id="pay-and-book-btn" onclick="openPaymentModal()" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#cad8e5] text-[#2e2f2c] shadow-sm hover:bg-[#aabdd2] transition">
                                            <span>Pay &amp; Book Now</span>
                                        </button>

                                        {{-- Payment Modal --}}
                                        <div id="payment-modal" class="fixed inset-0 bg-[#2e2f2c]/60 dark:bg-[#2e2f2c]/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                                            <div class="bg-white dark:bg-[#6a6c6a]/30 rounded-2xl border border-[#cad8e5] dark:border-[#8da5c0]/40 shadow-2xl max-w-lg w-full overflow-hidden">
                                                <div class="px-6 py-4 bg-[#2e2f2c] dark:bg-[#6a6c6a] text-white flex items-center justify-between">
                                                    <div>
                                                        <h2 class="text-sm font-bold">Book &amp; Pay Securely</h2>
                                                        <p class="text-[11px] text-[#cad8e5]">Complete payment to book {{ $property->building_name ?? 'Property' }}</p>
                                                    </div>
                                                    <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                                                </div>

                                                <form method="POST" action="{{ route('properties.pay-and-book', $property->id) }}" class="p-6 space-y-4">
                                                    @csrf            
                                                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">

                                                    <div class="p-4 rounded-xl bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] flex items-center justify-between">
                                                        <div>
                                                            <p class="text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5]">Assurance fee Required (5%)</p>
                                                            <p class="text-lg font-bold text-[#2e2f2c] dark:text-[#aabdd2] mt-0.5">
                                                                KES {{ number_format(ceil(5/100 * ($property->price ?? 0))) }}
                                                            </p>
                                                        </div>
                                                        <span class="text-[11px] font-semibold text-[#6a6c6a] dark:text-[#cad8e5] bg-white dark:bg-[#2e2f2c] px-2.5 py-1 rounded-full border border-[#cad8e5] dark:border-[#6a6c6a]">
                                                            of KES {{ number_format($property->price ?? 0) }}
                                                        </span>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-[#2e2f2c] dark:text-[#cad8e5] mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] px-3 py-2 text-xs text-[#2e2f2c] dark:text-white focus:border-[#8da5c0] focus:ring-[#8da5c0]">
                                                    </div>

                                                    <div class="flex items-start gap-2">
                                                        <input type="checkbox" name="terms_agreement" id="terms" required class="mt-0.5 h-4 w-4 rounded border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] focus:ring-[#8da5c0] dark:bg-[#2e2f2c]">
                                                        <label for="terms" class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed cursor-pointer">
                                                            I agree to pay and book this property. Payment is non-refundable unless landlord rejects.
                                                        </label>
                                                    </div>

                                                    <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a] flex justify-end gap-2">
                                                        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#cad8e5] dark:hover:bg-[#6a6c6a] transition">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] transition">
                                                            Pay &amp; Book Now
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
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-3">Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->images as $image)
                                @php
                                    $path = preg_replace('/^public\//', '', $image);
                                @endphp
                                <div class="overflow-hidden rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee] dark:bg-[#2e2f2c]">
                                    <img src="{{ asset('storage/'.$path) }}" 
                                        alt="Property image" 
                                        class="w-full h-40 object-cover hover:scale-105 transition duration-300"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236a6c6a\'><rect width=\'100%\' height=\'100%\' fill=\'%23dae4ee\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>';">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Description & details --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-2">Description</h3>
                            <div class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed whitespace-pre-line">
                                {{ $property->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-2">Details</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-[#2e2f2c] dark:text-[#cad8e5] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 p-4 rounded-2xl border border-[#cad8e5] dark:border-[#6a6c6a]">
                                <div>
                                    <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2] block text-[11px]">Bedrooms</span>
                                    <span class="font-bold text-[#2e2f2c] dark:text-white">{{ $property->bedrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2] block text-[11px]">Bathrooms</span>
                                    <span class="font-bold text-[#2e2f2c] dark:text-white">{{ $property->bathrooms }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2] block text-[11px]">Size</span>
                                    <span class="font-bold text-[#2e2f2c] dark:text-white">{{ $property->size }} sqm</span>
                                </div>
                                <div class="col-span-2 sm:col-span-3 pt-2 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                                    <span class="font-medium text-[#6a6c6a] dark:text-[#aabdd2] block text-[11px]">Address</span>
                                    <span class="font-bold text-[#2e2f2c] dark:text-white">{{ $property->address }}</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($property->amenities) && count($property->amenities))
                            <div>
                                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-2">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($property->amenities as $amenity)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] border border-[#aabdd2] dark:border-[#6a6c6a]">
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
                            <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white mb-2">Location &amp; Route</h3>
                            <div id="propertyMap" 
                                 data-lat="{{ $property->map_lat ?? $property->latitude }}" 
                                 data-lng="{{ $property->map_lng ?? $property->longitude }}" 
                                 class="h-80 w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee] dark:bg-[#2e2f2c]">
                            </div>
                            <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2] mt-1.5">
                                🎓 University marker is fixed. 📍 shows this property location.
                            </p>
                        </div>

                        <div class="text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] space-y-0.5">
                            <p><span class="font-medium text-[#2e2f2c] dark:text-white">Property Lat:</span> {{ $property->map_lat ?? $property->latitude ?? 'N/A' }}</p>
                            <p><span class="font-medium text-[#2e2f2c] dark:text-white">Property Lng:</span> {{ $property->map_lng ?? $property->longitude ?? 'N/A' }}</p>
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
                <div class="mt-8 pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                    <a href="{{ route('student.dashboard') }}" class="text-xs font-medium text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition flex items-center gap-1">
                        &larr; Back to properties
                    </a>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Toast Notification Handler
            const toast = document.getElementById('toast');
            if (toast && toast.dataset.session) {
                try {
                    const data = JSON.parse(toast.dataset.session);
                    const message = typeof data === 'string' ? data : (data.message || '');
                    const isError = data.type === 'error';
                    const isDark = document.documentElement.classList.contains('dark');

                    if (message) {
                        toast.textContent = message;
                        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-xl border opacity-100 transition-opacity duration-300 z-50 text-xs shadow-lg pointer-events-auto ${
                            isError
                                ? (isDark ? 'bg-rose-950 text-rose-200 border-rose-800' : 'bg-rose-900 text-white border-rose-700')
                                : (isDark ? 'bg-emerald-950 text-emerald-200 border-emerald-800' : 'bg-emerald-900 text-white border-emerald-700')
                        }`;

                        setTimeout(() => {
                            toast.style.opacity = '0';
                        }, 4000);
                    }
                } catch (e) {
                    console.error('Error parsing session toast:', e);
                }
            }

            // 2. Transport Modal Handler
            const openTransportBtn = document.getElementById('openTransportModalButton');
            const transportModal = document.getElementById('transportRequestModal');
            const closeTransportBtn = document.getElementById('closeTransportModalButton');
            const closeTransportBtn2 = document.getElementById('closeTransportModalButton2');

            if (transportModal && openTransportBtn) {
                openTransportBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (openTransportBtn.hasAttribute('disabled')) return;
                    transportModal.classList.remove('hidden');
                    transportModal.classList.add('flex');
                    transportModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });

                const closeTransport = function () {
                    transportModal.classList.remove('flex');
                    transportModal.classList.add('hidden');
                    transportModal.style.display = 'none';
                    document.body.style.overflow = '';
                };

                if (closeTransportBtn) closeTransportBtn.addEventListener('click', closeTransport);
                if (closeTransportBtn2) closeTransportBtn2.addEventListener('click', closeTransport);

                transportModal.addEventListener('click', function (e) {
                    if (e.target === transportModal) {
                        closeTransport();
                    }
                });
            }

            // 3. Payment Modal Backdrop Click Dismissal
            const paymentModal = document.getElementById('payment-modal');
            if (paymentModal) {
                paymentModal.addEventListener('click', function (e) {
                    if (e.target === paymentModal) {
                        window.closeModal();
                    }
                });
            }

            // 4. Global ESC key listener to close modals
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    window.closeModal();
                    if (transportModal) {
                        transportModal.classList.remove('flex');
                        transportModal.classList.add('hidden');
                        transportModal.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                }
            });

            // 5. Transport Countdown Timer
            const countdownEl = document.getElementById('transport-countdown');
            if (countdownEl) {
                const deadlineStr = countdownEl.getAttribute('data-deadline');
                if (deadlineStr) {
                    const deadline = new Date(deadlineStr).getTime();

                    const updateCountdown = () => {
                        const now = new Date().getTime();
                        const diff = deadline - now;

                        if (diff <= 0) {
                            countdownEl.textContent = 'Transport time reached';
                            clearInterval(timer);
                            return;
                        }

                        const second = 1000;
                        const minute = 60 * second;
                        const hour = 60 * minute;

                        const hours = Math.floor(diff / hour);
                        const minutes = Math.floor((diff % hour) / minute);
                        const seconds = Math.floor((diff % minute) / second);

                        let parts = [];
                        if (hours > 0) parts.push(hours + 'h');
                        if (minutes >= 0) parts.push(minutes + 'm');
                        parts.push(seconds + 's');

                        countdownEl.textContent = parts.join(' ');
                    };

                    updateCountdown();
                    const timer = setInterval(updateCountdown, 1000);
                }
            }
        });

        window.openPaymentModal = function () {
            const paymentModal = document.getElementById('payment-modal');
            if (paymentModal) {
                paymentModal.classList.remove('hidden');
                paymentModal.classList.add('flex');
                paymentModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function () {
            const paymentModal = document.getElementById('payment-modal');
            if (paymentModal) {
                paymentModal.classList.remove('flex');
                paymentModal.classList.add('hidden');
                paymentModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    </script>
    @endpush
</x-app-layout>