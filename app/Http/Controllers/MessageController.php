<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Show all pending bookings (messages) for this landlord
    public function index()

    {
        $user = Auth::user();

        $pendingBookings  = collect();
        $approvedBookings = collect();

        if ($user && $user->category === 'landlord') {
            $pendingBookings = Booking::where('landlord_id', $user->id)
                ->where('status', 'pending')
                ->with(['student', 'property'])
                ->latest()
                ->get();

            $approvedBookings = Booking::where('landlord_id', $user->id)
                ->where('status', 'approved')
                ->with(['student', 'property'])
                ->latest()->paginate(20)
                ;
        }

        return view('messages', compact('pendingBookings', 'approvedBookings'));
    }
    public function notify()
    {
        $user = Auth::user();

        // Only allow logged-in students
        if (! $user || $user->category !== 'student') {
            abort(403);
        }
        
        // Get all notifications for this student, newest first
        $notifications = Booking::with('property')->where('student_id', $user->id)->get();
        $bookings = Booking::with('property')->where('student_id', $user->id)->paginate(8);

        return view('student.notifications', [
            'user'          => $user,
            'notifications' => $notifications,
            'bookings'=>$bookings,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|exists:users,id',
            'text' => 'required|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            $message = Message::create([
                'from' => auth()->id(),
                'to' => $request->to,
                'text' => $request->text,
                'status' => 'sent'
            ]);
            
            // Broadcast to recipient
            broadcast(new MessageSent($message))->toOthers();
            
            // Store minimal copy for sync (optional, can be disabled)
            if (config('messages.store_on_server', false)) {
                // Store message in database
                $message->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'server_id' => $message->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message'
            ], 500);
        }
    }
    
    public function sync(Request $request)
    {
        $since = $request->query('since', 0);
        $userId = auth()->id();
        
        // Get messages since last sync
        $messages = Message::where(function ($query) use ($userId) {
                $query->where('from', $userId)
                      ->orWhere('to', $userId);
            })
            ->where('created_at', '>', \Carbon\Carbon::createFromTimestamp($since / 1000))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($userId) {
                return [
                    'serverId' => $message->id,
                    'from' => $message->from,
                    'to' => $message->to,
                    'text' => $message->text,
                    'timestamp' => $message->created_at->timestamp * 1000,
                    'status' => $message->status
                ];
            });
        
        // Get deleted messages
        $deleted = DB::table('deleted_messages')
            ->where('user_id', $userId)
            ->where('deleted_at', '>', \Carbon\Carbon::createFromTimestamp($since / 1000))
            ->pluck('message_id');
        
        // Get updated user statuses
        $users = User::where('last_seen', '>', \Carbon\Carbon::createFromTimestamp($since / 1000))
            ->where('id', '!=', $userId)
            ->get(['id', 'name', 'avatar', 'last_seen', 'status']);
        
        return response()->json([
            'messages' => $messages,
            'deleted' => $deleted,
            'users' => $users,
            'timestamp' => now()->timestamp * 1000
        ]);
    }
    
    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required'
        ]);
        
        $message = Message::find($request->message_id);
        
        if ($message && $message->to === auth()->id()) {
            $message->update(['status' => 'read']);
            broadcast(new MessageRead($message->id, auth()->id()));
        }
        
        return response()->json(['success' => true]);
    }
    
    public function delete(Request $request, $id)
    {
        // Mark as deleted for sync (soft delete)
        DB::table('deleted_messages')->insert([
            'user_id' => auth()->id(),
            'message_id' => $id,
            'deleted_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}

