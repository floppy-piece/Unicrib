<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');

        // Base query
        $query = User::query();

        // Filter by category if selected
        if ($category && in_array($category, ['student', 'landlord', 'admin'])) {
            $query->where('category', $category);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Calculate category counts for the filter tabs
        $categoryCounts = [
            'student'  => User::where('category', 'student')->count(),
            'landlord' => User::where('category', 'landlord')->count(),
            'admin'    => User::where('category', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'category', 'categoryCounts'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'is_verified' => 'required|boolean',
        ]);
    
        $user->is_verified = (bool) $request->is_verified;
        
        $user->access_expires_at = now()->addHours(6);
        $user->save();
    
        return back()->with('success', 'User verification updated.');

    }
    public function destroy(User $user)
    {
        // Optional: prevent admin from deleting themselves
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        // Optional: handle related models (bookings, properties) as needed
        // e.g. soft delete or reassign; here, just delete the user:
        $user->delete(); // if using SoftDeletes, it will soft-delete [web:104][web:110]

        return back()->with('success', 'User deleted successfully.');
    }

}
