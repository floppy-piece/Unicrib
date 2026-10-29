{{-- resources/views/landlord/properties/edit.blade.php --}}
<x-app-layout>
    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-4 right-4 bg-[#2e2f2c] dark:bg-[#6a6c6a] text-slate-50 dark:text-slate-100 px-4 py-2.5 rounded-xl border border-[#6a6c6a] dark:border-[#8da5c0] opacity-0 transition-opacity duration-200 z-[9999] text-xs font-medium shadow-lg"></div>

    {{-- Expose Geoapify key for global/module access --}}
    <script>
        window.GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));
    </script>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                    {{ __('Edit Property') }}
                </h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
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

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="p-6 sm:p-8 space-y-6">
                    
                    <h1 class="text-lg font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
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
                            <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Property Title *</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', $property->title) }}"
                                        required
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Property Type *</label>
                                    <select name="property_type" required class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm">
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
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Price (KES) *</label>
                                    <input
                                        type="number"
                                        name="price"
                                        value="{{ old('price', $property->price) }}"
                                        required min="0" step="0.01"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Area *</label>
                                    <select name="area" id="areaSelect" required class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm">
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
                            <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">Property Details</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Bedrooms *</label>
                                    <input
                                        type="number"
                                        name="bedrooms"
                                        value="{{ old('bedrooms', $property->bedrooms) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Bathrooms *</label>
                                    <input
                                        type="number"
                                        name="bathrooms"
                                        value="{{ old('bathrooms', $property->bathrooms) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Size (sqm) *</label>
                                    <input
                                        type="number"
                                        name="size"
                                        value="{{ old('size', $property->size) }}"
                                        required min="0" step="0.01"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Initial Rented Rooms *</label>
                                    <input
                                        type="number"
                                        name="booked_count"
                                        value="{{ old('booked_count', $property->booked_count) }}"
                                        required min="0" 
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Rooms available *</label>
                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ old('capacity', $property->capacity) }}"
                                        min="1"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                        required
                                    >
                                </div>

                                <div class="sm:col-span-2 lg:col-span-3">
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Amenities</label>
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
                                            <label class="flex items-center gap-2 bg-[#dae4ee]/50 dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] p-2.5 rounded-xl cursor-pointer hover:bg-[#cad8e5]/40 dark:hover:bg-[#6a6c6a]/40 transition">
                                                <input
                                                    type="checkbox"
                                                    name="amenities[]"
                                                    value="{{ $key }}"
                                                    class="rounded border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] focus:ring-[#8da5c0] bg-white dark:bg-[#2e2f2c]"
                                                    {{ in_array($key, $selectedAmenities, true) ? 'checked' : '' }}
                                                >
                                                <span class="text-[#2e2f2c] dark:text-[#cad8e5] font-medium">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Description *</label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    required
                                    class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                >{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">Address Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Building Name (Optional)</label>
                                    <input
                                        type="text"
                                        name="building_name"
                                        value="{{ old('building_name', $property->building_name) }}"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Street/Road *</label>
                                    <input
                                        type="text"
                                        name="street"
                                        value="{{ old('street', $property->street) }}"
                                        required
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Landmark (Optional)</label>
                                    <input
                                        type="text"
                                        name="landmark"
                                        value="{{ old('landmark', $property->landmark) }}"
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-[#6a6c6a] dark:text-[#cad8e5] mb-1">Full Address *</label>
                                    <input
                                        type="text"
                                        name="address"
                                        value="{{ old('address', $property->address) }}"
                                        required
                                        class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs py-2 px-3 focus:outline-none focus:border-[#8da5c0] focus:ring-1 focus:ring-[#8da5c0] transition shadow-sm"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        {{-- Exact Location (Map) --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">Exact Property Location</h3>

                            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 text-xs text-blue-900 dark:text-blue-300 space-y-1">
                                <p>
                                    <strong class="font-bold">Tip:</strong> Click on the map to adjust the exact location of this property.
                                </p>
                                <p class="text-[11px] text-blue-700 dark:text-blue-400">
                                    🎓 Fixed university marker shows the campus. 📍 is your property. The route is from Geoapify.
                                </p>
                            </div>

                            <div class="relative min-h-[400px] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl overflow-hidden bg-[#dae4ee] dark:bg-[#2e2f2c]">
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-[#6a6c6a] dark:text-[#aabdd2] z-10" id="mapLoading">Loading map...</div>
                                <div id="locationMap" 
                                     data-lat="{{ $property->map_lat ?? $property->latitude }}" 
                                     data-lng="{{ $property->map_lng ?? $property->longitude }}" 
                                     style="height: 400px; width: 100%;" class="cursor-default"></div>
                            </div>

                            <div class="bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/60 border border-[#cad8e5] dark:border-[#6a6c6a] rounded-xl p-4 text-xs space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-[#6a6c6a] dark:text-[#aabdd2] font-semibold uppercase text-[10px]">Latitude:</span>
                                        <span id="latDisplay" class="ml-2 font-mono font-medium text-[#2e2f2c] dark:text-white">
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
                                        <span class="text-[#6a6c6a] dark:text-[#aabdd2] font-semibold uppercase text-[10px]">Longitude:</span>
                                        <span id="lngDisplay" class="ml-2 font-mono font-medium text-[#2e2f2c] dark:text-white">
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
                                <div class="text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] pt-1 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                                    <span id="locationStatus" class="font-semibold">
                                        {{ ($property->map_lat || $property->latitude) ? 'Location already set' : 'No location selected' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Images --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-2">Property Images</h3>

                            @if(is_array($property->images) && count($property->images))
                                <p class="text-xs font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Existing images:</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($property->images as $image)
                                        @php $path = preg_replace('/^public\//', '', $image); @endphp
                                        <div class="overflow-hidden rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] aspect-video bg-[#dae4ee] dark:bg-[#2e2f2c]">
                                            <img src="{{ asset('storage/'.$path) }}" alt="Existing image" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="space-y-1.5 pt-2">
                                <label class="block text-xs font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Upload additional images (existing ones will be kept):</label>
                                <input
                                    type="file"
                                    name="images[]"
                                    multiple
                                    accept="image/*"
                                    id="imageUpload"
                                    class="w-full rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-white text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#cad8e5] file:text-[#2e2f2c] hover:file:bg-[#aabdd2] transition"
                                >
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>

                                <p class="text-[10px] text-[#6a6c6a] dark:text-[#aabdd2]">Max 2MB per image. JPEG, PNG, JPG, GIF.</p>
                            </div>
                        </div>

                        {{-- Submit / Footer --}}
                        <div class="flex items-center justify-between pt-6 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                            <a href="{{ route('landlord.properties.myProperties') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-[#cad8e5] dark:border-[#6a6c6a] bg-white dark:bg-[#2e2f2c] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] text-[#2e2f2c] dark:text-white text-xs font-semibold transition shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold transition shadow-sm">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet JS & Map Script --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script type="module">
        import { initPropertyMap } from "{{ Vite::asset('resources/js/property-map.js') }}";
        document.addEventListener('DOMContentLoaded', () => {
            const mapEl = document.getElementById('locationMap');
            if (mapEl) {
                const lat = mapEl.dataset.lat ? parseFloat(mapEl.dataset.lat) : null;
                const lng = mapEl.dataset.lng ? parseFloat(mapEl.dataset.lng) : null;
                initPropertyMap({ initialLat: lat, initialLng: lng });
            }
        });
    </script>
</x-app-layout>