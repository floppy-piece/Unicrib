<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingApproved;
use App\Notifications\BookingRejected;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Notifications\NewBookingDepositReceived;

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
        if ($booking->payment_status !== 'paid') {
            abort(403, 'Only paid bookings can be viewed.');
        }

        return view('landlord.bookings.show', compact('booking'));
    }
    // Student creates booking
    public function store(Request $request,Property $property)
    {
        //$this->authorize('book', $property);
        $user = auth()->user();
        if (! $user || $user->category !== 'student') {
            abort(403);
        }

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
                'amount' => ceil(0.333333 * $property->price),
                'payment_status' => 'unpaid',
                'payment_reference'=> '',
                'rejection_reason'=> 'Sorry not available.',
            ]);
    
            return back()->with('booking_status', [
                'type' =>'success',
                'message'=>'Booking created successfully, wait for verification email!'
            ]);
            
        } catch (\Throwable $e) {
            return back()->with('booking_status', [
                'type'    => 'error',
                'message' => 'Failed to create booking. Please try again later or contact unicrib.' .$e->getMessage(),
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


/*public function cancel(Booking $booking)
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
}*/
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



    public function payAndBook(Request $request, Property $property)
    {
        //$this->authorize('book', $property);
        $user = auth()->user();
        if (! $user || $user->category !== 'student') {
            abort(403);
        }
        $validated = $request->validate([
            //'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            //'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'email' => ['required', 'email'],
            'terms_agreement' => ['required', 'accepted'],
        ]);

        // FIXED 33.33% deposit
        $fullPrice = $property->price;
        $depositAmount = intval(5/100 * $fullPrice);

        // Check availability
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

        // SPLIT PAYMENT SETUP
        $landlord = $property->landlord;
        if (!$landlord->paystack_subaccount_code) {
            return back()->with('booking_status', [
                'type'    => 'error',
                'message' => 'This landlord is not configured for payments.',
            ]);
        }

        $reference = Paystack::genTranxRef();
        $paymentData = [
            'email' => $validated['email'],
            'amount' => $depositAmount * 100, // kobo
            'reference' => $reference,
            'callback_url' => route('paystack.verify'),
            'subaccount' => $landlord->paystack_subaccount_code, // Landlord 99%
            'bearer' => 'subaccount', // Landlord pays fees
            'metadata' => [
                'intent' => 'deposit_booking',
                'property_id' => $property->id,
                'student_id' => Auth::id(),                'landlord_id' => $landlord->id,
                'full_price' => $fullPrice,
                'deposit_amount' => $depositAmount,
                //'check_in_date' => $validated['check_in_date'],
                //'check_out_date' => $validated['check_out_date'],
            ],
        ];

        // Store pending payment
        session(['pending_split_payment' => [
            'reference' => $reference,
            'property_id' => $property->id,
            'validated_data' => $validated,
            'deposit_amount' => $depositAmount,
            'full_price' => $fullPrice,
            'landlord_subaccount' => $landlord->paystack_subaccount_code,
        ]]);

        return Paystack::getAuthorizationUrl($paymentData)->redirectNow();
    }

    public function paystackVerify(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();
        
        if (!$paymentDetails['status']) {
            $pending = session('pending_split_payment', []);
            return redirect()->route('properties.show', $pending['property_id'] ?? 1)
                ->with('error', 'Payment verification failed');
        }

        $transactionData = $paymentDetails['data'];
        if ($transactionData['status'] !== 'success') {
            return redirect()->back()->with('error', 'Payment not successful');
        }

        $pending = session('pending_split_payment');
        if (!$pending || $pending['reference'] !== $transactionData['reference']) {
            return redirect()->back()->with('error', 'Invalid payment reference');
        }

        // CREATE BOOKING (Payment verified + SPLIT complete)
        $property = Property::findOrFail($pending['property_id']);
        $booking = DB::transaction(function () use ($pending, $transactionData, $property) {
            $booking = Booking::create([
                'property_id' => $property->id,
                'student_id' => Auth::id(),
                'landlord_id' => $property->landlord_id,
                //'check_in_date' => $pending['validated_data']['check_in_date'],
                //'check_out_date' => $pending['validated_data']['check_out_date'],
                //'full_price' => $pending['full_price'],
                'amount' => $pending['deposit_amount'],
                'payment_status' => Booking::PAYMENT_PAID,
                'status' => Booking::STATUS_PENDING,
                'payment_reference' => $transactionData['reference'],
                'paystack_subaccount_code' => $pending['landlord_subaccount'],
            ]);

            // Notify landlord
            //$property->landlord->notify(new NewBookingDepositReceived($booking));
            
            return $booking;
        });

        session()->forget('pending_split_payment');
        return redirect()->route('student.notifications', $booking)
            ->with('booking_status', [
                'type' =>'success',
                'message'=>'Booking created successfully, wait for verification email!'
            ]);
    }

    private function isPropertyAvailable(Property $property, string $checkIn, string $checkOut)
    {
        return !Booking::where('property_id', $property->id)
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<=', $checkOut)
                  ->where('check_out_date', '>=', $checkIn);
            })
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->exists();
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['property', 'student', 'landlord']);
        return view('student.notifications', compact('booking'));
    }


    public function reject(Request $request, Booking $booking)
    {
        //$this->authorize('reject', $booking);
        abort_unless($booking->landlord_id === auth()->id(), 403);
        
        /*$request->validate([
            'reason' => ['required', 'string', 'max:500']
        ]);*/

        DB::transaction(function () use ($booking, $request) {
            // 1. INSTANT REFUND if payment exists
            if ($booking->payment_status === Booking::PAYMENT_PAID && $booking->payment_reference) {
                $this->initiatePaystackRefund($booking, $request->reason);
            }

            // 2. Delete booking record
            $booking->update(['status'          => 'rejected',
            'payment_status'  => 'refunded',]);

            // 3. Notify student
            $booking->student->notify(new BookingRejected($booking));
            
        });

        return redirect()->route('messages')
            ->with('success', 'Booking rejected and student refunded automatically.');
    }

    /**
     * INSTANT Paystack Refund
     */
    private function initiatePaystackRefund(Booking $booking,) //string $reason)
    {
        $secretKey = getenv('PAYSTACK_SECRET_KEY');
    
        // 🔥 CRITICAL CHECKS
        if (empty($secretKey)) {
            \Log::error('PAYSTACK_SECRET_KEY is EMPTY!');
            return false;
        }
        
        if (strpos($secretKey, 'sk_test_') !== 0 && strpos($secretKey, 'sk_live_') !== 0) {
            \Log::error('INVALID PAYSTACK KEY: ' . substr($secretKey, 0, 20));
            return false;
        }
        $refundData = [
            'transaction' => $booking->payment_reference,
            'amount' => intval($booking->amount * 100), // Full deposit back
            'reason' => "i don't want you here",//$reason,
            'email' => $booking->student->email,
        ];

        //try {
            
        
            $refund = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://api.paystack.co/refund', $refundData);

            // 🔥 DEBUG EVERYTHING
        \Log::info('Refund Request Debug', [
            'secret_key_preview' => substr($secretKey, 0, 10) . '...',
            'status_code' => $refund->status(),
            'response' => $refund->json(),
            'payment_ref' => $booking->payment_reference,
        ]);

        if ($refund->successful()) {
            $response = $refund->json();
            $refundCode = $response['refundData']['refund_code'] ?? $response['refund_code'] ?? null;
            
            /*$booking->update([
                'refund_reference' => $refundCode,
                'refund_status' => 'initiated',
                'refund_amount' => $booking->deposit_amount,
                'refund_reason' => "you have a big head"//$reason,
            ]);*/
            $booking->where('payment_reference', $booking->payment_refernce)->update([
                'refund_reference' => $refundCode,
                'refund_status' => 'initiated',
                'refund_amount' => $booking->deposit_amount,
                'refund_reason' => "you have a big head",//$reason,
            ]);
    
            \Log::info('DATABASE UPDATED', [
                'booking_id' => $booking->id,
                'refund_ref' => $response['refundData']['refund_code'] ?? 'no_code',
            ]);
            return true;
        }

        return false;
    

       /* } catch (\Exception $e) {
            // Log error but don't fail rejection
            \Log::error('Paystack Refund Failed: ' . $e->getMessage());
            $booking->update([
                'refund_status' => 'failed',
                'refund_reason' => $reason . ' (Refund processing failed)',
            ]);
            return false;
        }*/
    }

}

