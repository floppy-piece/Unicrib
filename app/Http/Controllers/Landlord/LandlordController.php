<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class LandlordController extends Controller
{
    public function createSubaccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|numeric',
            'bank_code' => 'required',
            //'percentage_share' => 99, // Landlord share
        ]);

        $subaccountData = [
            'account_name' => $request->user()->name . ' (Unicrib Landlord)',
            'account_email' => $request->user()->email,
            'account_number' => $request->account_number,
            'bank_code' => $request->bank_code,
            'split_config' => [
                'split_type' => 'percentage',
                'split_value' => 100 - 99, // Platform %
            ],
            'description' => 'Unicrib Landlord - ' . $request->user()->id,
        ];

        try {
            $subaccount = Paystack::createSubAccount($subaccountData);
            
            $request->user()->update([
                'paystack_subaccount_code' => $subaccount['data']['subaccount_code']
            ]);

            return back()->with('success', 'Subaccount created! Ready for split payments.');
        } catch (\Exception $e) {
            //return back()->withErrors(['error' => 'Failed to create subaccount: ' . $e->getMessage()]);
            return back()->with('paystack_subaccount_code', [
                'type'    => 'error',
                'message' => 'Failed to create subaccount: ' . $e->getMessage()
            ]);
        }
    }
   
}
