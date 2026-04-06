<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('last_seen', 'desc')
            ->get(['id', 'name', 'email', 'phone', 'profile_photo_path as avatar', 'last_seen', 'status']);
        
        return response()->json($users);
    }
    
    public function search(Request $request)
    {
        $query = $request->query('q');
        
        $users = User::where('id', '!=', auth()->id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'profile_photo_path as avatar', 'last_seen', 'status']);
        
        return response()->json($users);
    }
}