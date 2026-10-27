<?php

namespace App\Http\Controllers;

use App\Models\StorageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'months'     => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $user = Auth::user();
        $monthlyPrice = 800;
        $months = (int) $request->input('months');

        $start = Carbon::parse($request->input('start_date'))->startOfDay();
        $end   = (clone $start)->addMonths($months)->subDay();

        $totalAmount = $months * $monthlyPrice;
        // Require paying the first month upfront
        $upfrontAmount = $monthlyPrice; 

        $booking = StorageBooking::create([
            'user_id'     => $user->id,
            'start_date'  => $start->toDateString(),
            'end_date'    => $end->toDateString(),
            'months'      => $months,
            'amount'      => $totalAmount,
            'paid_amount' => 0, // Will update upon callback confirmation
            'status'      => 'pending',
        ]);

        // Redirect to a dedicated storage payment route, passing the booking ID
        return redirect()->route('payments.storage.form', ['storageBooking' => $booking->id])
        ->with('info', 'Storage booking created. Pay the initial month fee (KES 800) to confirm your booking.');
    }

    public function apiStorageStatus(Request $request, $user = null)
    {
        $userId = $user ? $user->id : Auth::id();
        $storage = StorageBooking::where('user_id', $userId)->latest()->first();
        if (!$storage) {
            return "You have no active storage bookings.";
        }
        return "Storage #{$storage->id}: Status is " . ucfirst($storage->status) . ", Duration: {$storage->months} months, Paid: KSh {$storage->paid_amount}/{$storage->amount}.";
    }

    public function apiManageStorage(Request $request, $user = null)
    {
        $userId = $user ? $user->id : Auth::id();
        $action = $request->input('action', 'check');
        $unitSize = $request->input('unit_size', 'standard');

        if ($action === 'reserve') {
            $storage = StorageBooking::create([
                'user_id'     => $userId,
                'start_date'  => now()->toDateString(),
                'end_date'    => now()->addMonth()->toDateString(),
                'months'      => 1,
                'amount'      => 800,
                'paid_amount' => 0,
                'status'      => 'pending',
            ]);
            return "Storage unit ({$unitSize}) reserved successfully! Booking ID #{$storage->id}. Total fee: KSh 800 (pending payment).";
        }

        $storage = StorageBooking::where('user_id', $userId)->latest()->first();
        if (!$storage) {
            return "You have no active storage bookings. Would you like to reserve a storage unit?";
        }
        return "Storage Booking #{$storage->id}: Status is " . ucfirst($storage->status) . ", Duration: {$storage->months} months, Unit Size: {$unitSize}, Paid: KSh {$storage->paid_amount}/{$storage->amount}.";
    }
}