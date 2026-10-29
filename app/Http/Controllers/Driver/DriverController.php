<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display Driver Dashboard with Assigned & Available Trips.
     */
    public function index(Request $request)
    {
        $driver = Auth::user();

        // Ensure user is authorized as a driver/transport provider
        if (!$driver || !in_array($driver->category, ['driver', 'transport', 'admin'])) {
            abort(403, 'Unauthorized access to Driver Console.');
        }

        $filter = $request->query('status', 'assigned');

        // Stats summary for driver
        $stats = [
            'total_assigned' => Transport::where('driver_id', $driver->id)->count(),
            'pending_pickup' => Transport::where('driver_id', $driver->id)->whereIn('status', ['confirmed', 'assigned', 'in_transit'])->count(),
            'completed'      => Transport::where('driver_id', $driver->id)->where('status', 'completed')->count(),
            'unassigned'     => Transport::whereNull('driver_id')->where('status', 'pending')->count(),
        ];

        // Query active trips
        $tripsQuery = Transport::with(['booking', 'property', 'student'])
            ->when($filter === 'assigned', fn($q) => $q->where('driver_id', $driver->id)->whereIn('status', ['confirmed', 'assigned', 'in_transit']))
            ->when($filter === 'available', fn($q) => $q->whereNull('driver_id')->where('status', 'pending'))
            ->when($filter === 'completed', fn($q) => $q->where('driver_id', $driver->id)->where('status', 'completed'))
            ->when($filter === 'all', fn($q) => $q->where(fn($sub) => $sub->where('driver_id', $driver->id)->orWhereNull('driver_id')))
            ->orderBy('assigned_at', 'asc');

        $trips = $tripsQuery->paginate(10);

        return view('driver.dashboard', compact('trips', 'stats', 'filter', 'driver'));
    }

    /**
     * Claim an available unassigned trip.
     */
    public function claimTrip(Transport $transport)
    {
        $driver = Auth::user();

        if ($transport->driver_id !== null && $transport->driver_id !== $driver->id) {
            return back()->with('error', 'This trip has already been claimed by another driver.');
        }

        $transport->update([
            'driver_id' => $driver->id,
            'status'    => 'confirmed',
        ]);

        return back()->with('success', "Trip #{$transport->id} claimed successfully!");
    }

    /**
     * Update transport progress status (confirmed -> in_transit -> completed / cancelled).
     */
    public function updateStatus(Request $request, Transport $transport)
    {
        $driver = Auth::user();

        if ($transport->driver_id !== $driver->id && $driver->category !== 'admin') {
            abort(403, 'You are not assigned to this transport trip.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:confirmed,in_transit,completed,cancelled'],
        ]);

        $transport->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Status for Trip #{$transport->id} updated to " . ucfirst($validated['status']) . ".");
    }
}