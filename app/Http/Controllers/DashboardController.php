<?php

/*namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
}*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->category) {
            case 'student':
                return redirect()->route('student.dashboard');
            case 'landlord':
                return redirect()->route('landlord.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/');
        }
        if (!in_array($user->category, ['student', 'landlord','admin'])) {
            abort(403, 'Invalid user category');
        }
    }

    public function studentDashboard()
    {
        $user = auth()->user();

        // Example: allow viewing all verified, available properties,
        // regardless of student's own verification, if that's what you want.
        $properties = Property::query()
            ->where('is_verified', true)
            ->latest()
            ->paginate(9);
    
        return view('student.dashboard', compact('properties', 'user'));

       
    }

    public function landlordDashboard()
    {
        $user = Auth::user();
        $landlord = Auth::user();

        // All properties belonging to this landlord
        $properties = Property::where('landlord_id', $landlord->id)->get();

        // Sum of booked_count for this landlord
        $totalBookedCount = Property::where('landlord_id', $landlord->id)
            ->sum('booked_count');

        return view('landlord.dashboard', compact('properties', 'totalBookedCount'));

    }

    /*public function adminDashboard()
    {
        $user = Auth::user();
        $stats = [
            'total_purchases' => $user->ordersAsBuyer()->count(),
        ];

        $recentOrders = $user->ordersAsBuyer()->with('seller')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }*/
}