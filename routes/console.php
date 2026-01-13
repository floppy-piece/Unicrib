<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    Booking::where('status', 'pending')
        ->where('created_at', '<=', now()->subHours(2))
        ->update(['status' => 'rejected']);

        Log::info("Auto-reject scheduler updated {$count} bookings.");
})->hourly();
