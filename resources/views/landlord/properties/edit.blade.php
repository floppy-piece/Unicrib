{{-- resources/views/landlord/properties/edit.blade.php --}}
<x-app-layout>
    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-4 right-4 bg-slate-900 dark:bg-slate-800 text-slate-50 dark:text-slate-100 px-4 py-2.5 rounded-xl border border-slate-800 dark:border-slate-700 opacity-0 transition-opacity duration-200 z-[9999] text-xs font-medium shadow-lg"></div>

    {{-- Expose Geoapify key for global/module access --}}
    <script>
        window.GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));
    </script>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ __('Edit Property') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Update details, location, and images for this listing.
                </p>
            </div>
        </div>
    </x-slot>

    @push('scripts')
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    @endpush

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 sm:p-8 space-y-6">
                    
                    <h1 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-4">
                        Edit: {{ $property->title }}
                    </h1>

                    @if ($errors->any())
                        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-900 text-xs text-rose-700 dark:text-rose-300 space-y-1">
                            <span class="font-bold block">Please fix the following errors:</span>
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('landlord.properties.update', $property->id) }}"
                          enctype="multipart/form-data"
                          id="propertyForm"
                          class="space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- Basic Information --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Title *</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', $property->title) }}"
                                        required
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Type *</label>
                                    <select name="property_type" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
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
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Price (KES) *</label>
                                    <input
                                        type="number"
                                        name="price"
                                        value="{{ old('price', $property->price) }}"
                                        required min="0" step="0.01"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Area *</label>
                                    <select name="area" id="areaSelect" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
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
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Property Details</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Bedrooms *</label>
                                    <input
                                        type="number"
                                        name="bedrooms"
                                        value="{{ old('bedrooms', $property->bedrooms) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Bathrooms *</label>
                                    <input
                                        type="number"
                                        name="bathrooms"
                                        value="{{ old('bathrooms', $property->bathrooms) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Size (sqm) *</label>
                                    <input
                                        type="number"
                                        name="size"
                                        value="{{ old('size', $property->size) }}"
                                        required min="0" step="0.01"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Initial Rented Rooms *</label>
                                    <input
                                        type="number"
                                        name="booked_count"
                                        value="{{ old('booked_count', $property->booked_count) }}"
                                        required min="0" 
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Rooms available *</label>
                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ old('capacity', $property->capacity) }}"
                                        min="1"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                        required
                                    >
                                </div>

                                <div class="sm:col-span-2 lg:col-span-3">
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Amenities</label>
                                    @php
                                        $selectedAmenities = old('amenities', $property->amenities ?? []);
                                        $allAmenities = [
                                            'wifi' => 'WiFi',
                                            'water' => 'Water 24/7',
                                            'parking' => 'Parking',
                                            'security' => 'Security',
                                        ];
                                    @endphp
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                                        @foreach($allAmenities as $key => $label)
                                            <label class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-2.5 rounded-xl cursor-pointer hover:bg-slate-100/50 dark:hover:bg-slate-700/50 transition">
                                                <input
                                                    type="checkbox"
                                                    name="amenities[]"
                                                    value="{{ $key }}"
                                                    class="rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-slate-100 dark:bg-slate-900"
                                                    {{ in_array($key, $selectedAmenities, true) ? 'checked' : '' }}
                                                >
                                                <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Description *</label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    required
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                >{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Address Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Building Name (Optional)</label>
                                    <input
                                        type="text"
                                        name="building_name"
                                        value="{{ old('building_name', $property->building_name) }}"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Street/Road *</label>
                                    <input
                                        type="text"
                                        name="street"
                                        value="{{ old('street', $property->street) }}"
                                        required
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Landmark (Optional)</label>
                                    <input
                                        type="text"
                                        name="landmark"
                                        value="{{ old('landmark', $property->landmark) }}"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Full Address *</label>
                                    <input
                                        type="text"
                                        name="address"
                                        value="{{ old('address', $property->address) }}"
                                        required
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Availability --}}
                        <div class="space-y-3">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Availability</h3>
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                                <input
                                    type="checkbox"
                                    name="is_available"
                                    value="1"
                                    class="rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-slate-100 dark:bg-slate-900"
                                    {{ old('is_available', $property->is_available) ? 'checked' : '' }}
                                >
                                <span>Mark as available for students</span>
                            </label>
                        </div>

                        {{-- Exact Location (Map) --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Exact Property Location</h3>

                            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 text-xs text-blue-900 dark:text-blue-300 space-y-1">
                                <p>
                                    <strong class="font-bold">Tip:</strong> Click on the map to adjust the exact location of this property.
                                </p>
                                <p class="text-[11px] text-blue-700 dark:text-blue-400">
                                    🎓 Fixed university marker shows the campus. 📍 is your property. The route is from Geoapify.
                                </p>
                            </div>

                            <div class="relative min-h-[400px] border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800">
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-slate-400 z-10" id="mapLoading">Loading map...</div>
                                <div id="locationMap" 
                                     data-lat="{{ $property->map_lat ?? $property->latitude }}" 
                                     data-lng="{{ $property->map_lng ?? $property->longitude }}" 
                                     style="height: 400px; width: 100%;" class="cursor-default"></div>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl p-4 text-xs space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-slate-400 font-semibold uppercase text-[10px]">Latitude:</span>
                                        <span id="latDisplay" class="ml-2 font-mono font-medium text-slate-900 dark:text-slate-100">
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
                                        <span class="text-slate-400 font-semibold uppercase text-[10px]">Longitude:</span>
                                        <span id="lngDisplay" class="ml-2 font-mono font-medium text-slate-900 dark:text-slate-100">
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
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-200/60 dark:border-slate-700">
                                    <span id="locationStatus" class="font-semibold">
                                        {{ ($property->map_lat || $property->latitude) ? 'Location already set' : 'No location selected' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Images --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Property Images</h3>

                            @if(is_array($property->images) && count($property->images))
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Existing images:</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($property->images as $image)
                                        @php $path = preg_replace('/^public\//', '', $image); @endphp
                                        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 aspect-video bg-slate-50 dark:bg-slate-800">
                                            <img src="{{ asset('storage/'.$path) }}" alt="Existing image" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="space-y-1.5 pt-2">
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Upload additional images (existing ones will be kept):</label>
                                <input
                                    type="file"
                                    name="images[]"
                                    multiple
                                    accept="image/*"
<<<<<<< HEAD
<<<<<<< HEAD
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900 transition"
                                >
=======
=======
>>>>>>> master
                                    id="imageUpload"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900 transition"
                                >
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
<<<<<<< HEAD
>>>>>>> master
=======
>>>>>>> master
                                <p class="text-[10px] text-slate-400">Max 2MB per image. JPEG, PNG, JPG, GIF.</p>
                            </div>
                        </div>

                        {{-- Submit / Footer --}}
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                            <a href="{{ route('landlord.properties.myProperties') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-sm">
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

<<<<<<< HEAD
<<<<<<< HEAD
    <script>
        const landmarkLat = -0.566519;
        const landmarkLng = 37.321184;

        const areaCoordinates = {
            mjini: { lat: -0.5080, lng: 37.2752 },
            ngomongo: { lat: -0.5100, lng: 37.2800 },
            diaspora: { lat: -0.5150, lng: 37.2850 },
            kutus: { lat: -0.5200, lng: 37.2700 }
        };

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
                const marker = L.marker([l.lat, l.lng], {
                    icon: L.divIcon({
                        className: 'custom-marker-extra-landmark',
                        html: `
                            <div style="background:${bg}; color:black; max-width:30px; max-height:30px; padding:4px 8px; border-radius:9999px; display:flex; flex-direction:column; align-items:center; justify-content:center; font-weight:bold; gap:4px; text-align:center; font-size:10px; box-shadow:0 2px 5px rgba(0,0,0,0.3); white-space:nowrap;">
                                <span>${emoji}</span>
                                <span style="font-size:8px; line-height:1; margin-top:2px;">${l.name}</span>
                            </div>
                        `,
                        iconSize: [40, 24],
                        iconAnchor: [20, 24]
                    })
                }).addTo(map);

                marker.bindTooltip(`${l.name} (${l.type})`, { permanent: false, direction: 'top' });
            });
        }

        let map = null;
        let landmarkMarker = null;
        let propertyMarker = null;
        let routeLayer = null;

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
            createExtraLandmarks();
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
                        <div style="background:#1d4ed8; color:white; min-width:40px; min-height:40px; padding:4px 8px; border-radius:9999px; border:3px solid white; display:flex; flex-direction:column; align-items:center; justify-content:center; font-weight:bold; font-size:10px; box-shadow:0 2px 5px rgba(0,0,0,0.3); text-align:center;">
                            🎓
                            <span style="font-size:8px; line-height:1; margin-top:2px;">UNIVERSITY</span>
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

            const url = `https://api.geoapify.com/v1/routing?waypoints=${fromWaypoint.join(',')}|${toWaypoint.join(',')}&mode=drive&details=instruction_details&apiKey=${encodeURIComponent(GEOAPIFY_API_KEY)}`;

            try {
                const res = await fetch(url);
                if (!res.ok) return;
                const data = await res.json();
                const routeFeature = data.features && data.features[0];
                if (!routeFeature) return;

                if (routeLayer) map.removeLayer(routeLayer);

                routeLayer = L.geoJSON(routeFeature, {
                    style: { color: "#3b82f6", weight: 4, opacity: 0.9 }
                }).addTo(map);
            } catch (err) {
                console.error("Failed to fetch route:", err);
            }
        }

        function updatePropertyMarker(lat, lng) {
            if (!map) return;
            lat = Number(lat);
            lng = Number(lng);

            if (Number.isNaN(lat) || Number.isNaN(lng)) return;

            if (propertyMarker) map.removeLayer(propertyMarker);

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
                if (typeof L === 'undefined') return;

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
=======
    {{-- Bundled application JavaScript via Vite --}}
    @vite(['resources/js/app.js'])
>>>>>>> master
=======
    {{-- Bundled application JavaScript via Vite --}}
    @vite(['resources/js/app.js'])
>>>>>>> master
</x-app-layout>