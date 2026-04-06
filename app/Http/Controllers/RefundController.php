<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaystackRefundService;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Property;
use App\Notifications\BookingApproved;
use App\Notifications\BookingRejected;

class RefundController extends Controller
{
    public function refund(Payment $payment, Request $request, PaystackRefundService $refundService)
    {
        $this->authorize('refund', $payment);

        if ($payment->status !== 'success') {
            return back()->withErrors('Only successful payments can be refunded.');
        }

        $request->validate([
            'amount' => ['nullable', 'numeric', 'min:1'],
        ]);

        $amountInKobo = $request->filled('amount')
            ? (int) $request->amount * 100
            : null;

        try {
            $refundResponse = $refundService->refundByReference(
                $payment->reference,
                $amountInKobo,
                $payment->currency
            );
        } catch (\Throwable $e) {
            return back()->withErrors('Refund failed: '.$e->getMessage());
        }

        if (!isset($refundResponse['status']) || $refundResponse['status'] !== true) {
            return back()->withErrors('Refund request not accepted by Paystack.');
        }

        $payment->update([
            'status' => 'refunded',
            'provider_payload' => array_merge(
                $payment->provider_payload ?? [],
                ['last_refund' => $refundResponse]
            ),
        ]);
       /* abort_unless($booking->landlord_id === auth()->id(), 403);

        // only reduce count if you had previously approved this booking
        if ($booking->status === 'approved') {
            $property = $booking->property;

        // $property->decrement('booked_count');

            if (!$property->isFull()) {
                $property->update(['is_available' => true]);
            }
        }

        $booking->update(['status' => 'rejected']);

        $booking->student->notify(new BookingRejected($booking));*/

        return back()->with('status', 'Booking Rejected, Refund initiated successfully.');
    }
}

