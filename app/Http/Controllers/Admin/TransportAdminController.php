<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transport;


class TransportAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // optional filter

        $query = Transport::with(['student', 'property', 'booking'])
            ->latest('assigned_at');

        if ($status) {
            $query->where('status', $status);
        }

        $transports = $query->paginate(20);

        $statusCounts = [
            'pending'   => Transport::where('status', 'pending')->count(),
            'confirmed' => Transport::where('status', 'confirmed')->count(),
            'completed' => Transport::where('status', 'completed')->count(),
            'cancelled' => Transport::where('status', 'cancelled')->count(),
        ];

        return view('admin.transports.index', compact('transports', 'status', 'statusCounts'));
    }

    public function update(Request $request, Transport $transport)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $transport->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Transport status updated.');
    }
}
