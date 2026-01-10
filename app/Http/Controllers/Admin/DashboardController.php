<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\StorageBooking;


class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalLandlords = User::where('category', 'landlord')->count();
        $totalStudents = User::where('category', 'student')->count();

        $totalProperties = Property::count();
        $verifiedProperties = Property::where('is_verified', true)->count();
        $unverifiedProperties = Property::where('is_verified', false)->count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();

        $recentUsers = User::latest()->take(5)->get();
        $recentProperties = Property::latest()->take(5)->get();
        $recentBookings = Booking::with(['student', 'property'])->latest()->take(5)->get();

        $recentTransports = Transport::with(['student', 'property'])->latest('assigned_at')->take(5)->get();

        $totalStorageBookings = StorageBooking::count();
        $storageStatusCounts = StorageBooking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $recentStorageBookings = StorageBooking::with('user')->latest()->limit(5)->get();
        

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalLandlords',
            'totalStudents',
            'totalProperties',
            'verifiedProperties',
            'unverifiedProperties',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings',
            'recentUsers',
            'recentProperties',
            'recentBookings',
            'recentTransports',
            'totalStorageBookings',
            'storageStatusCounts',
            'recentStorageBookings',
        ));
    }
}
