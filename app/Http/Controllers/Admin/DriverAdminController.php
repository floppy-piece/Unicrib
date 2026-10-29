<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Transport;
use Illuminate\Http\Request;

class DriverAdminController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('admin.drivers.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:drivers'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_plate' => ['required', 'string', 'max:20', 'unique:drivers'],
        ]);

        Driver::create($validated);

        return back()->with('success', 'Driver registered successfully.');
    }

    public function assignDriver(Request $request, Transport $transport)
    {
        $validated = $request->validate([
            'driver_id' => ['required', 'exists:drivers,id'],
        ]);

        $transport->update([
            'driver_id' => $validated['driver_id'],
            'status' => 'driver_assigned',
        ]);

        // Optional: Update driver status to busy
        Driver::where('id', $validated['driver_id'])->update(['status' => 'busy']);

        return back()->with('success', 'Driver dispatched to transport request successfully.');
    }
}