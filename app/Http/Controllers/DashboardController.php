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
        $stats = [
            /*'total_products' => $user->products()->count(),
            'total_revenue' => $user->ordersAsSeller()->where('payment_status', 'paid')->sum('total_amount'),*/
        ];

        /*$products = $user->products()->latest()->take(5)->get();
        $recentOrders = $user->ordersAsSeller()->with('buyer')->latest()->take(5)->get();*/

    return view('landlord.dashboard'/*, compact('stats', 'products', 'recentOrders')*/);
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