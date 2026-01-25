<?php

namespace App\Listeners;

use App\Events\BookingRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingRejected $event): void
    {
        $booking = $event->booking;

        // Student gets refund confirmation
        $booking->student->notify(new BookingStatusChanged($booking));
    
        // Landlord confirmation
        $booking->landlord->notify(new BookingStatusChanged($booking));
    
        // Optional: admins
        User::where('role', 'admin')->get()
            ->each(fn($admin) => $admin->notify(new BookingStatusChanged($booking)));
    }

}
