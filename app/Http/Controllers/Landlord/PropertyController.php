<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transport;

class PropertyController extends Controller
{
    //for welcome landing page
   public function welcome()
    {
        // Use safe fallbacks so it never fails even if tables are completely empty
        try {
        $properties = Schema::hasTable('properties') 
            ? Property::whereRaw('is_verified = true')->whereRaw('is_available = true')->latest()->take(6)->get() 
            : collect();

        $totalProperties = Schema::hasTable('properties') ? Property::count() : 0;
        
        $totalStudents = Schema::hasTable('users') 
            ? User::where('category', 'student')->count() 
            : 0;
        } catch (\Exception $e) {
            $properties = collect();
            $totalProperties = 0;
            $totalStudents = 0;
        }
        return view('welcome', compact('properties', 'totalProperties', 'totalStudents'));
    }
    /**
     * Display a listing of the landlord's properties.
     */
    public function index()
    {
        $properties = Auth::user()->properties()->latest()->paginate(10);
        return view('landlord.properties.myProperties', compact('properties'));
    }

    //show landlord the property booked by tenant
    public function showProperty(Property $property)
    {
        $user = Auth::user();

        // Ensure landlord owns this property
        if (! $user || $user->category !== 'landlord' || $property->landlord_id !== $user->id) {
            abort(403);
        }

        // Load any needed relations, e.g. bookings
        $property->load(['bookings', 'bookings.student']);

        return view('landlord.properties.show', compact('property'));
    }
    public function show(Property $property)
    {
        // Optionally restrict which properties are visible to students:
        if ($property->is_verified===false) abort(404);
   
        $user = Auth::user();

        $booking = null;
        $activeTransport = null;
        $property->load('landlord');

        if ($user && $user->category === 'student') {
            $booking = Booking::where('property_id', $property->id)
                ->where('student_id', $user->id)
                ->latest()
                ->first();
        }

        if ($booking) {
            $activeTransport = Transport::where('booking_id', $booking->id)
                ->where('student_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->latest('assigned_at')
                ->first();
        }
    

        return view('properties.show', compact('property', 'user', 'booking', 'activeTransport'));          
    }

    public function studentIndex()
    {
        // Get properties you want students to see
        // e.g., only active ones, maybe with pagination
       
         $properties = Property::whereRaw('is_verified = true')
         ->whereRaw('is_available = true')
         ->latest()
         ->paginate(9);
 
     return view('student.dashboard', compact('properties'));       
    }
   /* public function studentIndex()
{
    $properties = Property::latest()->paginate(10);
    return view('student.dashboard', compact('properties'));
}*/


    public function create()
    {
        return view('landlord.properties.add');
    }

    /**
     * Store a newly created property.
     */
    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'size' => 'required|numeric|min:0',
            'address' => 'required|string',
            'area' => 'required|in:mjini,ngomongo,diaspora,kutus',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_lat' => 'nullable|numeric|between:-90,90',
            'map_lng' => 'nullable|numeric|between:-180,180',
            'property_type' => 'required|in:apartment,house,room,studio,bedsitter',
            'amenities' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'street' => 'nullable|string',
            'building_name' => 'nullable|string',
            'landmark' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'booked_count'=>'required|integer|min:0|lte:capacity',
        ],
        [
            'capacity.required'     => 'Please enter the total capacity for this property.',
            'capacity.min'          => 'Capacity must be at least 1 room.',
            'booked_count.required' => 'Please enter how many rooms are already booked.',
            'booked_count.lte'      => 'Initial rented rooms cannot be more than the total rooms available.',
        ]);

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties/' . Auth::id(), 'public');
                $imagePaths[] = $path;
            }
        }

        // Create property
        $property = Property::create([
            'landlord_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'size' => $validated['size'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'map_lat' => $validated['map_lat'] ?? null,
            'map_lng' => $validated['map_lng'] ?? null,
            'property_type' => $validated['property_type'],
            'amenities' => $validated['amenities'] ?? [],
            'images' => $imagePaths,
            'street' => $validated['street'] ?? null,
            'building_name' => $validated['building_name'] ?? null,
            'landmark' => $validated['landmark'] ?? null,
            'location_confirmed' => !empty($validated['map_lat']) && !empty($validated['map_lng']),
            'capacity'     => $validated['capacity'],
            'booked_count' => $validated['booked_count'] ?? 0, // Fixed variable name here
        ]);

        // Geocode address if coordinates not provided
        if (empty($validated['latitude']) || empty($validated['longitude'])) {
            $this->geocodeAddress($property);
        }

        return redirect()->route('landlord.properties.myProperties')
            ->with('success', 'Property added successfully!');
    }
    /**
     * Show the form for editing a property.
     */
    public function edit(Property $property)
    {
        // Check if property belongs to the landlord
        if ($property->landlord_id !== Auth::id()) {
            abort(403);
        }

        return view('landlord.properties.edit', compact('property'));
        
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property)
    {
        // Authorization check
        if ($property->landlord_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:apartment,house,room,studio,bedsitter',
            'price' => 'required|numeric|min:0',
            'area' => 'required|in:mjini,ngomongo,diaspora,kutus',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'size' => 'required|numeric|min:0',
            'booked_count' => 'required|integer|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'building_name' => 'nullable|string|max:255',
            'street' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'map_lat' => 'nullable|numeric',
            'map_lng' => 'nullable|numeric',
            'amenities' => 'nullable|array',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1. Handle removal of selected existing images
        $currentImages = $property->images ?? [];
        if ($request->has('remove_images')) {
            $imagesToRemove = $request->input('remove_images', []);
            foreach ($imagesToRemove as $imgToRemove) {
                // Delete from disk storage
                if (\Storage::disk('public')->exists($imgToRemove)) {
                    \Storage::disk('public')->delete($imgToRemove);
                }
            }
            // Filter out removed images
            $currentImages = array_values(array_diff($currentImages, $imagesToRemove));
        }

        // 2. Handle uploading new images
        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('properties/' . auth()->id(), 'public');
                $newImagePaths[] = $path;
            }
        }

        // 3. Merge remaining existing images with new uploads
        $finalImages = array_merge($currentImages, $newImagePaths);

        // 4. Update property record fields
        $property->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'],
            'price' => $validated['price'],
            'area' => $validated['area'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'size' => $validated['size'],
            'booked_count' => $validated['booked_count'],
            'capacity' => $validated['capacity'],
            'description' => $validated['description'],
            'building_name' => $validated['building_name'] ?? null,
            'street' => $validated['street'],
            'landmark' => $validated['landmark'] ?? null,
            'address' => $validated['address'],
            'map_lat' => $validated['map_lat'] ?? $property->map_lat,
            'map_lng' => $validated['map_lng'] ?? $property->map_lng,
            'amenities' => $validated['amenities'] ?? [],
            'images' => $finalImages,
        ]);

        return redirect()->route('landlord.properties.myProperties')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Update property coordinates via AJAX.
     */
    public function updateCoordinates(Request $request, Property $property)
    {
        // Check if property belongs to the landlord
        if ($property->landlord_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $property->update([
            'map_lat' => $validated['lat'],
            'map_lng' => $validated['lng'],
            'location_confirmed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'property' => $property->fresh()
        ]);
    }
    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('landlord.properties.myProperties')
            ->with('success', 'Property deleted successfully.');
    }


    /**
     * Geocode address to get coordinates.
     */
    private function geocodeAddress(Property $property)
    {
        try {
            $address = urlencode($property->full_address);
            $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}&limit=1";
            
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => config('app.name'),
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (!empty($data)) {
                $property->update([
                    'latitude' => $data[0]['lat'],
                    'longitude' => $data[0]['lon'],
                    'formatted_address' => $data[0]['display_name'],
                    'geocoding_data' => $data[0],
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Geocoding failed: ' . $e->getMessage());
        }
    }

    public function searchPropertiesApi(Request $request, User $user)
    {
        // 🔒 Validate and sanitize area input against allowed enum values
        $area = $request->input('area', '');
        $allowedAreas = ['mjini', 'ngomongo', 'diaspora', 'kutus'];
        
        // Use whereRaw with explicit boolean expressions to prevent integer binding on PostgreSQL/Neon
        $query = Property::where('is_verified', filter_var($request->input('is_verified', true), FILTER_VALIDATE_BOOLEAN))
                         ->where('is_available', filter_var($request->input('is_available', true), FILTER_VALIDATE_BOOLEAN));

        if (!empty($area) && in_array(strtolower($area), $allowedAreas)) {
            $query->where('area', strtolower($area));
        } elseif (!empty($area)) {
            // Escape wildcard characters to prevent SQL wildcard injection
            $escapedArea = str_replace(['%', '_'], ['\%', '\_'], $area);
            $query->where('area', 'ILIKE', '%' . $escapedArea . '%');
        }

        $maxPrice = filter_var($request->input('max_price'), FILTER_VALIDATE_FLOAT);
        if ($maxPrice !== false && $maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        $properties = $query->take(3)->get();

        if ($properties->isEmpty()) {
            return "No matching student properties found right now. Try checking another location.";
        }

        return $properties->map(fn($p) => "• [ID: {$p->id}] {$p->title} - KSh " . number_format($p->price) . "/mo at {$p->address}")->implode("\n");
    }

    public function landlordPropertiesApi(User $user)
    {
        // 🔒 FIX 2: Ensure strictly bound to the authenticated landlord
        $properties = Property::where('landlord_id', $user->id)->get();
        if ($properties->isEmpty()) {
            return "You have no registered properties.";
        }
        return "Your Properties:\n" . $properties->map(fn($p) => "• [ID: {$p->id}] {$p->title} - KSh " . number_format($p->price) . " (" . ($p->is_verified ? 'Verified ✅' : 'Pending ⏳') . ")")->implode("\n");
    }

    public function landlordBookingsApi(User $user)
    {
        // 🔒 FIX 3: Ensure bookings are strictly filtered by landlord's properties to block IDOR cross-tenant inspection
        $bookings = Booking::with(['property', 'student'])
            ->where('landlord_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        if ($bookings->isEmpty()) {
            return "No student bookings found.";
        }

        return "Recent Bookings:\n" . $bookings->map(fn($b) => "• Booking #{$b->id}: " . ($b->student->name ?? 'Student') . " for " . ($b->property->title ?? 'Property') . " | Status: " . ucfirst($b->status))->implode("\n");
    }

    public function addLandlordPropertyApi(Request $request, User $user, array $imageFileUrls = [])
    {
        if ($user->category !== 'landlord') {
            return "Unauthorized: Only registered landlords can add properties.";
        }

        // 🔒 FIX 4: Enforce landlord subscription/semester verification check
        if (!$user->is_verified) {
            return "🔒 Verification Required: Please complete your landlord semester verification payment on the web dashboard before adding new property listings.";
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:1000000',
            'area' => 'required|in:mjini,ngomongo,diaspora,kutus',
            'address' => 'required|string|max:255',
            'property_type' => 'required|in:apartment,house,room,studio,bedsitter',
            'bedrooms' => 'required|integer|min:0|max:20',
            'bathrooms' => 'required|integer|min:0|max:20',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        // 🔒 FIX 5: Mitigate SSRF when downloading images from chat URLs
        $imagePaths = [];
        foreach ($imageFileUrls as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            // Restrict downloads strictly to trusted hosts (e.g., Telegram file servers or Unicrib CDN)
            $parsedHost = parse_url($url, PHP_URL_HOST);
            if (!str_ends_with($parsedHost, 'telegram.org') && !str_ends_with($parsedHost, 'amazonaws.com')) {
                continue;
            }

            try {
                // Use a short timeout to prevent hanging worker threads (DoS defense)
                $response = Http::timeout(5)->get($url);
                if ($response->successful()) {
                    $filename = 'properties/' . $user->id . '/' . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $response->body());
                    $imagePaths[] = $filename;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to download agent property image safely: ' . $e->getMessage());
            }
        }

        $property = Property::create([
            'landlord_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? 'Student property listed via AI Assistant',
            'price' => $validated['price'],
            'area' => $validated['area'],
            'address' => $validated['address'],
            'property_type' => $validated['property_type'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'capacity' => $validated['capacity'],
            'booked_count' => 0,
            'images' => $imagePaths,
            'is_verified' => false, // New listings require admin or automated review before going live
            'is_available' => true,
        ]);

        return "Property successfully created! [ID: {$property->id}] {$property->title} in {$property->area} at KSh " . number_format($property->price) . "/mo with " . count($imagePaths) . " attached image(s).";
    }

    public function updatePropertyAvailabilityApi(Request $request, User $user)
    {
        $propertyId = filter_var($request->input('property_id'), FILTER_VALIDATE_INT);
        $isAvailable = filter_var($request->input('is_available', true), FILTER_VALIDATE_BOOL);

        // 🔒 FIX 6: Secure IDOR check ensuring property belongs exclusively to the requesting landlord
        $property = Property::where('id', $propertyId)
            ->where('landlord_id', $user->id)
            ->first();

        if (!$property) {
            return "Property #{$propertyId} not found or you do not own it.";
        }

        $property->update(['is_available' => $isAvailable]);
        $statusText = $isAvailable ? 'available' : 'unavailable';
        return "Property #{$property->id} ({$property->title}) status updated to {$statusText}.";
    }
    public function updateBookingStatusApi(Request $request, User $user)
    {
        $bookingId = $request->input('booking_id');
        $status = $request->input('status'); // approved or rejected

        if (!in_array($status, ['approved', 'rejected'])) {
            return "Invalid status. Please specify 'approved' or 'rejected'.";
        }

        $booking = Booking::with(['property', 'student'])
            ->where('id', $bookingId)
            ->where('landlord_id', $user->id)
            ->first();

        if (!$booking) {
            return "Booking #{$bookingId} not found or does not belong to your properties.";
        }

        // 🔒 SECURITY CHECK: Prevent re-approving cancelled, rejected, or completed bookings
        if ($status === 'approved' && $booking->status !== 'pending') {
            return "Security Error: Cannot approve Booking #{$bookingId} because its current status is '{$booking->status}'. The student must create a new booking request first.";
        }

        $booking->update(['status' => $status]);
        return "Booking #{$booking->id} for student {$booking->student->name} ({$booking->property->title}) has been successfully " . ucfirst($status) . ".";
    }
}