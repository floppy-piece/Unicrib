<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $user->status = $request->status;
        $user->save();

        return back()->with('success', 'User status updated.');

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
