<?php
// app/Http/Controllers/TransportController.php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransportController extends Controller
{
    public function create(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if (! $user || $user->category !== 'student') {
            abort(403);
        }

        // Ensure booking belongs to this student
        if ($booking->student_id !== $user->id) {
            abort(403);
        }

        $property = $booking->property; // needs Booking->property relationship

        return view('transport.request', [
            'booking'  => $booking,
            'property' => $property,
            'user'     => $user,
        ]);
    }

  
    public function store(Request $request, Booking $booking)
    {
        $user = Auth::user();
    
        if (! $user || $user->category !== 'student') {
            abort(403);
        }
    
        if ($booking->student_id !== $user->id) {
            abort(403);
        }
    
        $property = $booking->property;
    
        // 1) Block if there is an active (pending, confirmed, or approved) request
        $existing = Transport::where('booking_id', $booking->id)
            ->where('student_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->first();
    
        if ($existing) {
            return back()->with('error',
                'You already have an active transport request for this booking. Please wait until it is completed or cancelled.'
            );
        }
    
        // 2) Validate input
        $validated = $request->validate([
            'student_name'  => ['required', 'string', 'max:255'],
            'student_phone' => ['required', 'string', 'max:20'],
            'requested_date'=> ['required', 'date', 'after_or_equal:today'],
        ]);
    
        // 3) Assign slot
        $assignedSlot = $this->assignSlot($property->id, $validated['requested_date']);
    
        // 4) Create transport record
        Transport::create([
            'booking_id'     => $booking->id,
            'property_id'    => $property->id,
            'student_id'     => $user->id,
            'student_name'   => $validated['student_name'],
            'student_phone'  => $validated['student_phone'],
            'requested_date' => $validated['requested_date'],
            'requested_time' => $assignedSlot->format('H:i:s'),
            'assigned_at'    => $assignedSlot,
            'status'         => 'pending',
        ]);
    
        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Transport request sent. Your assigned time is '.$assignedSlot->format('d M Y, H:i'));
    }
        
    

    protected function assignSlot(int $propertyId, string $requestedDate): Carbon
    {
        $requested = Carbon::parse($requestedDate)->startOfDay();

        $lastTransport = Transport::where('property_id', $propertyId)
            ->whereDate('assigned_at', $requested)
            ->orderBy('assigned_at', 'desc')
            ->first();

        if (! $lastTransport) {
            $slot = $requested->copy()->setTime(8, 0);
        } else {
            $slot = $lastTransport->assigned_at->copy()->addHours(2);
        }

        $start = $requested->copy()->setTime(8, 0);
        $end   = $requested->copy()->setTime(22, 0);

        if ($slot->lt($start)) {
            $slot = $start;
        }

        if ($slot->gt($end)) {
            $slot = $requested->copy()->addDay()->setTime(8, 0);
        }

        return $slot;
    }
    /**
     * Agent / API endpoint for WhatsApp bot to fetch ride status
     */
    public function apiTransportStatus(Request $request)
    {
        $identifier = $request->input('identifier') ?? $request->input('booking_id');
        $transport = Transport::with('driver')
            ->when(is_numeric($identifier), fn($q) => $q->where('booking_id', $identifier))
            ->when(!is_numeric($identifier) && $identifier, fn($q) => $q->whereHas('property', fn($p) => $p->where('area', 'LIKE', '%' . $identifier . '%')->orWhere('title', 'LIKE', '%' . $identifier . '%')))
            ->latest()->first();

        if (!$transport) {
            $msg = "No transport request or route found for identifier '{$identifier}'.";
            return $request->wantsJson() ? response()->json(['status' => 'none', 'message' => $msg], 404) : $msg;
        }

        $driverInfo = $transport->driver ? "Driver: {$transport->driver->name} ({$transport->driver->vehicle_model}, Plate: {$transport->driver->vehicle_plate})" : "Driver pending assignment.";
        $statusStr = "Transport Status: " . ucfirst($transport->status) . "\nRequested Date: {$transport->requested_date}\n{$driverInfo}";

        if ($request->wantsJson() && !$request->has('identifier')) {
            return response()->json([
                'status' => $transport->status,
                'assigned_time' => optional($transport->assigned_at)->format('d M Y, H:i'),
                'driver' => $transport->driver ? [
                    'name' => $transport->driver->name,
                    'phone' => $transport->driver->phone,
                    'vehicle' => $transport->driver->vehicle_model,
                    'plate' => $transport->driver->vehicle_plate,
                ] : null
            ]);
        }

        return $statusStr;
    }
    
}
