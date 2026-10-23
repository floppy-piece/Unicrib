<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-4 right-4 bg-slate-900 dark:bg-slate-800 text-slate-50 dark:text-slate-100 px-4 py-2.5 rounded-xl border border-slate-800 dark:border-slate-700 opacity-0 transition-opacity duration-200 z-50 text-xs shadow-lg"></div>

    <script>
        window.GEOAPIFY_API_KEY = @json(config('services.geoapify.key'));
    </script>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ __('Add New Property') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Add a listing with full details and exact map location.
                </p>
            </div>
        </div>
    </x-slot>

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
                    
                    <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data" id="propertyForm" class="space-y-8">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-800">Basic Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Title *</label>
                                    <input type="text" name="title" required 
                                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"
                                           placeholder="e.g., Modern 2-Bedroom Apartment">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Property Type *</label>
                                    <select name="property_type" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                                        <option value="">Select Type</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="house">House</option>
                                        <option value="room">Single Room</option>
                                        <option value="studio">Studio</option>
                                        <option value="bedsitter">Bedsitter</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Price (KES) *</label>
                                    <input type="number" name="price" required min="0" step="0.01"
                                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"
                                           placeholder="e.g., 15000">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Area *</label>
                                    <select name="area" id="areaSelect" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                                        <option value="">Select Area</option>
                                        <option value="mjini">Mjini</option>
                                        <option value="ngomongo">Ngomongo</option>
                                        <option value="diaspora">Diaspora</option>
                                        <option value="kutus">Kutus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exact Location Selection -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-800">Exact Property Location</h3>
                            
                            <div class="relative min-h-[400px]">
                                <div id="mapLoading" class="absolute inset-0 flex items-center justify-center bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-medium text-xs z-10 rounded-xl">Loading map...</div>
                                <div id="locationMap" class="h-[400px] w-full rounded-xl border border-slate-200 dark:border-slate-700"></div>
                            </div>
                            
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-xs">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-slate-500 dark:text-slate-400">Latitude:</span>
                                        <span id="latDisplay" class="ml-1 font-semibold text-slate-900 dark:text-slate-100">Click on map</span>
                                        <input type="hidden" name="map_lat" id="mapLat" required>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 dark:text-slate-400">Longitude:</span>
                                        <span id="lngDisplay" class="ml-1 font-semibold text-slate-900 dark:text-slate-100">Click on map</span>
                                        <input type="hidden" name="map_lng" id="mapLng" required>
                                    </div>
                                </div>
                                <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span id="locationStatus">No location selected</span>
                                </div>
                            </div>

                            <!-- Route & Distance Display -->
                            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-900 dark:text-emerald-300 space-y-1">
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
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-2">
                                <p>Can't find your location on the map? Enter coordinates manually:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="number" step="any" id="manualLat" 
                                           class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-xs"
                                           placeholder="Latitude (e.g., -0.5080)">
                                    <input type="number" step="any" id="manualLng" 
                                           class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-xs"
                                           placeholder="Longitude (e.g., 37.2752)">
                                    <div class="sm:col-span-2">
                                        <button type="button" onclick="useManualCoordinates()" 
                                                class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold transition border border-slate-200 dark:border-slate-700">
                                            Use These Coordinates
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Images Upload -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-800">Property Images</h3>
                            <div class="p-6 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 text-center bg-slate-50/50 dark:bg-slate-900/50">
                                <input type="file" name="images[]" multiple accept="image/*" 
                                       class="w-full cursor-pointer text-xs text-slate-500 dark:text-slate-400" id="imageUpload">
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Upload multiple images of your property (max 5 images, 2MB each).</p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                            <a href="{{ route('landlord.properties.myProperties') }}" 
                               class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition">
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
            
    @vite(['resources/js/app.js'])
</x-app-layout>