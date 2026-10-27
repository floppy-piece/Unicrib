{{-- resources/views/landlord/properties/add.blade.php --}}
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
                    {{ __('Add New Property') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Add a listing with full details, exact map location, and photos.
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
                        New Property Listing
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
                          action="{{ route('landlord.properties.store') }}"
                          enctype="multipart/form-data"
                          id="propertyForm"
                          class="space-y-8">
                        @csrf

                        {{-- Basic Information --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Title *</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title') }}"
                                        required
                                        placeholder="e.g., Modern 2-Bedroom Apartment"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Type *</label>
                                    <select name="property_type" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
                                        @php $types = ['apartment','house','room','studio','bedsitter']; @endphp
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('property_type') === $type ? 'selected' : '' }}>
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
                                        value="{{ old('price') }}"
                                        required min="0" step="0.01"
                                        placeholder="e.g., 15000"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Area *</label>
                                    <select name="area" id="areaSelect" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm">
                                        @php $areas = ['mjini','ngomongo','diaspora','kutus']; @endphp
                                        <option value="">Select Area</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area }}" {{ old('area') === $area ? 'selected' : '' }}>
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
                                        value="{{ old('bedrooms', 1) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Bathrooms *</label>
                                    <input
                                        type="number"
                                        name="bathrooms"
                                        value="{{ old('bathrooms', 1) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Size (sqm) *</label>
                                    <input
                                        type="number"
                                        name="size"
                                        value="{{ old('size', 25) }}"
                                        required min="0"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Capacity (Rooms) *</label>
                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ old('capacity', 1) }}"
                                        required min="1"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Description *</label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    required
                                    placeholder="Describe amenities, proximity to campus, rules, etc."
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs p-3 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition shadow-sm"
                                >{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Exact Location Selection (Leaflet Map) --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Exact Property Location</h3>
                            
                            <div class="relative min-h-[400px] rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                                <div id="mapLoading" class="absolute inset-0 flex items-center justify-center bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-medium text-xs z-10">Loading interactive map...</div>
                                <div id="locationMap" class="h-[400px] w-full"></div>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-xs">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-slate-500 dark:text-slate-400">Latitude:</span>
                                        <span id="latDisplay" class="ml-1 font-semibold text-slate-900 dark:text-slate-100">Click on map</span>
                                        <input type="hidden" name="latitude" id="mapLat" value="{{ old('latitude') }}" required>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 dark:text-slate-400">Longitude:</span>
                                        <span id="lngDisplay" class="ml-1 font-semibold text-slate-900 dark:text-slate-100">Click on map</span>
                                        <input type="hidden" name="longitude" id="mapLng" value="{{ old('longitude') }}" required>
                                    </div>
                                </div>
                                <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span id="locationStatus">No location selected on map yet.</span>
                                </div>
                            </div>

                            {{-- Route & Distance Display --}}
                            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-900 dark:text-emerald-300 space-y-1 shadow-sm">
                                <div>
                                    <strong>Route from University:</strong>
                                    <span id="pathDisplay">No route calculated yet.</span>
                                </div>
                                <div>
                                    <strong>Distance:</strong>
                                    <span id="distanceDisplay">–</span>
                                </div>
                            </div>

                            {{-- Manual Coordinate Input (Fallback) --}}
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2 pt-2">
                                <p class="font-medium text-slate-700 dark:text-slate-300">Can't find your location on the map? Enter coordinates manually:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <input type="number" step="any" id="manualLat" 
                                           class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-xs shadow-sm"
                                           placeholder="Latitude (e.g., -0.5080)">
                                    <input type="number" step="any" id="manualLng" 
                                           class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-xs shadow-sm"
                                           placeholder="Longitude (e.g., 37.2752)">
                                    <div>
                                        <button type="button" onclick="useManualCoordinates()" 
                                                class="w-full px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold transition border border-slate-200 dark:border-slate-700 shadow-sm">
                                            Apply Coordinates
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Property Images --}}
                        <div class="space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Property Images</h3>
                            
                            <div class="p-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 text-center bg-slate-50/50 dark:bg-slate-800/30">
                                <input type="file"
                                       name="images[]"
                                       multiple
                                       accept="image/*"
                                       id="imageUpload"
                                       class="w-full cursor-pointer text-xs text-slate-500 dark:text-slate-400">
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Upload multiple images (max 5 images, 2MB each).</p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                            <a href="{{ route('landlord.properties.myProperties') }}"
                               class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition shadow-sm">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-md shadow-indigo-600/30">
                                Add Property
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

    @vite(['resources/js/app.js'])
</x-app-layout>
