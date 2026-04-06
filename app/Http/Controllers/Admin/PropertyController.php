<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('landlord')->latest()->paginate(20);
        return view('admin.properties.index', compact('properties'));
    }

    public function updateStatus(Request $request, Property $property)
    {
        $request->validate([
            'is_verified' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        if ($request->has('is_verified')) {
            $property->is_verified = (bool) $request->is_verified;
        }

        if ($request->has('is_available')) {
            $property->is_available = (bool) $request->is_available;
        }

        $property->save();

        return back()->with('success', 'Property status updated.');
    }
}
