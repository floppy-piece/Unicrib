<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PaymentController extends Controller
{
    // STUDENT

    public function showStudentForm()
    {
        return view('payments.student');
    }

    public function handleStudentPayment(Request $request)
    {
        $user = Auth::user();

        // TODO: integrate real payment here (M-Pesa/STK push or card)
        // For now, assume paid successfully:


        //after integrattion of mpesa use the commeneted code
        $user->update([
            'is_verified'       => true,
            'access_expires_at' => now()->addHours(6),
        ]);
       /* $user->is_verified = 1;
        $user->access_expires_at = now()->addHours(6);
        $user->save(); */

        return redirect()->route('student.dashboard')
            ->with('success', 'Payment received. You can now view property details for 6 hours.');
    }

    // LANDLORD

    public function showLandlordForm()
    {
        return view('payments.landlord');
    }

    public function handleLandlordPayment(Request $request)
    {
        $user = Auth::user();

        // TODO: integrate real payment here

        $user->update([
            'is_verified' => true,
        ]);

        // Optional: mark all their properties verified for the semester
        // or only future properties. Here, mark existing:
        Property::where('landlord_id', $user->id)->update([
            'is_verified'            => true,
            'verification_expires_at'=> now()->addMonths(4), // example semester length
        ]);

        return redirect()->route('landlord.dashboard')
            ->with('success', 'Account verified. You can now add and verify properties.');
    }
}
