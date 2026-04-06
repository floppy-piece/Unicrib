<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('category','!=','admin')->latest()->paginate(20);
        $user = User::latest()->first(); // single model, not collection
        $category = $request->query('category'); // optional filter

        $categoryCounts = [
            'student'   => User::where('category', 'student')->count(),
            'landlord' => User::where('category', 'landlord')->count(),
            'admin' => User::where('category', 'admin')->count(),
        ];

        if ($category) {
            $users->where('category', $category);
        }

        foreach ($users as $user) {
            $user->status = $user->is_verified ? 'verified' : 'Not verified';
        }

        

        return view('admin.users.index', compact('users', 'user', 'categoryCounts','category'));
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
