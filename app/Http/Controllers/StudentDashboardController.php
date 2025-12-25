<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Get all available properties grouped by area
        $propertiesByArea = Property::available()
            ->verified()
            ->get()
            ->groupBy('area');
        
        // Get area statistics
        $areaStats = [
            'mjini' => [
                'count' => $propertiesByArea->get('mjini', collect())->count(),
                'avg_price' => $propertiesByArea->get('mjini', collect())->avg('price') ?? 0,
            ],
            'ngomongo' => [
                'count' => $propertiesByArea->get('ngomongo', collect())->count(),
                'avg_price' => $propertiesByArea->get('ngomongo', collect())->avg('price') ?? 0,
            ],
            'diaspora' => [
                'count' => $propertiesByArea->get('diaspora', collect())->count(),
                'avg_price' => $propertiesByArea->get('diaspora', collect())->avg('price') ?? 0,
            ],
            'kutus' => [
                'count' => $propertiesByArea->get('kutus', collect())->count(),
                'avg_price' => $propertiesByArea->get('kutus', collect())->avg('price') ?? 0,
            ],
        ];
        
        return view('dashboard.student', [
            'propertiesByArea' => $propertiesByArea,
            'areaStats' => $areaStats,
        ]);
    }
    
    public function showPropertiesInArea($area)
    {
        $properties = Property::available()
            ->verified()
            ->inArea($area)
            ->with('landlord')
            ->get();
            
        return response()->json([
            'success' => true,
            'properties' => $properties->map(function($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => number_format($property->price),
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'size' => $property->size,
                    'address' => $property->address,
                    'full_address' => $property->full_address,
                    'landlord' => $property->landlord->name,
                    'contact' => $property->landlord->phone ?? $property->landlord->email,
                    'coordinates' => $property->coordinates,
                    'amenities' => $property->amenities,
                    'description' => $property->description,
                    'images' => $property->images,
                    'distance' => $this->calculateDistanceFromUniversity(
                        $property->coordinates['lat'],
                        $property->coordinates['lng']
                    ),
                ];
            }),
        ]);
    }
    
    private function calculateDistanceFromUniversity($lat, $lng)
    {
        // Kirinyaga University coordinates
        $uniLat = -0.505;
        $uniLng = 37.270;
        
        // Haversine formula
        $R = 6371; // Earth's radius in km
        $dLat = deg2rad($lat - $uniLat);
        $dLng = deg2rad($lng - $uniLng);
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($uniLat)) * cos(deg2rad($lat)) * 
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $R * $c;
        
        return $distance;
    }
}