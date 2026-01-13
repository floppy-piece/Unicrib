<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingApproved;
use App\Notifications\BookingRejected;

class BookingController extends Controller
{
    public function showBooking(Booking $booking)   // route: /landlord/bookings/{booking}
    {
        $user = Auth::user();

        // Ensure only the landlord who owns this booking can view it
        if (! $user || $user->category !== 'landlord' || $booking->landlord_id !== $user->id) {
            abort(403);
        }

        // Load related models if not already eager loaded
        $booking->load(['student', 'property']);

        return view('landlord.bookings.show', compact('booking'));
    }
    // Student creates booking
    public function store(Property $property)
    {
        $user = auth()->user();

        $existing = Booking::where('student_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('booking_status', [
                'type'    => 'error',
                'message' => 'You already have an active booking. To book another property, request cancellation from your existing landlord.',
            ]);
        }

        if ($property->isFull()) {
            return back()->with('booking_status', [
                'type'    => 'error',
                'message' => 'This property is full.',
            ]);
        }

    // Create booking
        try {
            Booking::create([
                'student_id'  => auth()->id(),
                'landlord_id' => $property->landlord_id,
                'property_id' => $property->id,
                'status'      => 'pending',
            ]);
    
            return back()->with('booking_status', [
                'type'    => 'success',
                'message' => 'Booking sent to landlord for confirmation.',
            ]);
        } catch (\Throwable $e) {
            return back()->with('booking_status', [
                'type'    => 'error',
                'message' => 'Failed to send booking. Please try again.',
            ]);
        }
    }

    // Landlord approves
  
    public function approve(Booking $booking)
{
    abort_unless($booking->landlord_id === auth()->id(), 403);

    $property = $booking->property;

    // if already full, do not approve
    if ($property->isFull()) {
        return back()->with('error', 'Property is already full.');
    }

    // set approved
    $booking->update(['status' => 'approved']);

    // increment booked_count
    //$property->increment('booked_count');

    // if now full, mark as not available
    //if ($property->isFull()) {
        //$property->update(['is_available' => false]);
    //}

    // notify student (your BookingApproved notification)
    $booking->student->notify(new BookingApproved($booking));

    return back()->with('success', 'Booking approved and student notified.');
}


    // Landlord rejects
    public function reject(Booking $booking)
{
    abort_unless($booking->landlord_id === auth()->id(), 403);

    // only reduce count if you had previously approved this booking
    if ($booking->status === 'approved') {
        $property = $booking->property;

       // $property->decrement('booked_count');

        if (!$property->isFull()) {
            $property->update(['is_available' => true]);
        }
    }

    $booking->update(['status' => 'rejected']);

    $booking->student->notify(new BookingRejected($booking));

    return back()->with('success', 'Booking rejected and student notified.');
}

public function cancel(Booking $booking)
{
    $user = auth()->user();

    // Ensure the booking belongs to this student
    if ($booking->student_id !== $user->id) {
        abort(403);
    }
    if($booking->created_at->lte(now()->subhours(2))){
    // Change status to rejected
        $booking->status = 'rejected';
        $booking->save(); // updates bookings table [web:104][web:100]
    }

    return back()->with('booking_status', [
        'type' => 'success',
        'message' => 'Your booking has been cancelled.',
    ]);
}
public function cancelStudent(Booking $booking)
    {
        $user = Auth::user();

        // Ensure landlord owns this booking
        if (! $user || $user->category !== 'landlord' || $booking->landlord_id !== $user->id) {
            abort(403);
        }

        if ($booking->status === 'approved') {
            $booking->status = 'rejected';
            $booking->save();
        }

        return back()->with('status', 'Tenant booking has been cancelled.');
    }

}

