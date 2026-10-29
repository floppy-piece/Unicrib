<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Log;

class LandlordController extends Controller
{
    public function createSubaccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|numeric',
            'bank_code' => 'required|string',
        ]);

        $user = $request->user();

        // Paystack expects 'settlement_bank' instead of 'bank_code' and 'percentage_charge' for the split
        $subaccountData = [
            'business_name' => $user->name . ' (Unicrib Landlord)',
            'settlement_bank' => $request->bank_code,
            'account_number' => $request->account_number,
            'percentage_charge' => 1.00, // Platform split percentage (e.g. 1% commission or adjust as needed)
            'description' => 'Unicrib Landlord Account - ID: ' . $user->id,
            'primary_contact_email' => $user->email,
        ];

        try {
            $response = Paystack::createSubAccount($subaccountData);
            
            if (!$response || !isset($response['status']) || !$response['status']) {
                $errMsg = $response['message'] ?? 'Unknown Paystack error occurred.';
                throw new \Exception($errMsg);
            }

            $subaccountCode = $response['data']['subaccount_code'] ?? null;

            if (!$subaccountCode) {
                throw new \Exception('Subaccount code was not returned by Paystack.');
            }

            $user->update([
                'paystack_subaccount_code' => $subaccountCode
            ]);

            return back()->with('success', 'Subaccount created successfully! Ready for split payments.');
        } catch (\Exception $e) {
            Log::error('Paystack Subaccount Creation Failed: ' . $e->getMessage());
            
            return back()->withErrors(['paystack_error' => 'Failed to create subaccount: ' . $e->getMessage()]);
        }
    }
}