<?php

/*namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StorageAdminController extends Controller
{
    //
}*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageBooking;
use Illuminate\Http\Request;

class StorageAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $statusCounts = StorageBooking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $query = StorageBooking::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $storageBookings = $query->paginate(20);
        $totalStorageBookings = StorageBooking::count();
        $storageStatusCounts = StorageBooking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $recentStorageBookings = StorageBooking::with('user')->latest()->limit(5)->get();

        return view('admin.storage.index', [
            'storageBookings' => $storageBookings,
            'status' => $status,
            'statusCounts' => $statusCounts,
            'totalStorageBookings'=> $totalStorageBookings,
            'storageStatusCounts'=> $storageStatusCounts,
            'recentStorageBookings'=> $recentStorageBookings,
        ]);
    }

    public function update(Request $request, StorageBooking $storageBooking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,paid,completed,cancelled'],
        ]);

        $storageBooking->update([
            'status' => $request->input('status'),
        ]);

        return back()->with('success', 'Storage booking status updated.');
    }
}
