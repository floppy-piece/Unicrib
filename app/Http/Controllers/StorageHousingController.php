<?php

namespace App\Http\Controllers;

use App\Models\StorageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorageHousingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookings = StorageBooking::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('store-housing', [
            'user' => $user,
            'bookings' => $bookings,
            'monthlyPrice' => 800,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'months'     => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $user = Auth::user();
        $monthlyPrice = 800;
        $months = (int) $request->input('months');

        $start = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $end   = (clone $start)->addMonths($months)->subDay();

        $amount = $months * $monthlyPrice;

        $booking = StorageBooking::create([
            'user_id'    => $user->id,
            'start_date' => $start->toDateString(),
            'end_date'   => $end->toDateString(),
            'months'     => $months,
            'amount'     => $amount,
            'status'     => 'pending',
        ]);

        return redirect()->route('payments.student.pay', [
            'storage_booking_id' => $booking->id,
        ])->with('success', 'Storage booking created. Complete payment to confirm.');
    }
}
