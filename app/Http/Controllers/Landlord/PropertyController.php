<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ? Property::where('is_verified', true)->where('is_available', true)->latest()->take(6)->get() 
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
       
         $properties = Property::where('is_verified', true)
         ->where('is_available', true)
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
        // Check if property belongs to the landlord
        if ($property->landlord_id !== Auth::id()) {
            abort(403);
        }

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
            'is_available' => 'boolean',
            'street' => 'nullable|string',
            'building_name' => 'nullable|string',
            'landmark' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'booked_count'=> 'required|integer|min:0|lte:capacity',
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePaths = $property->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties/' . Auth::id(), 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        // Update location confirmation status
        $validated['location_confirmed'] = !empty($validated['map_lat']) && !empty($validated['map_lng']);

        $property->update($validated);

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
}