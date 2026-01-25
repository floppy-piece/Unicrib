<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackWebhookController extends Controller
{
    
    public function handle(Request $request)
    {
        $event = Paystack::verifyWebhookEvent($request->getContent()); // auto-verifies signature

        if ($event['event'] === 'charge.success') {
            $booking = Booking::where('payment_reference', $event['data']['reference'])->first();
            if ($booking) {
                $booking->update(['payment_status' => Booking::PAYMENT_PAID]);
            }
        }

        return response('OK', 200);
    }

}
