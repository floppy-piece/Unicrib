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

        // Pass both settlement_bank and bank_code to ensure Paystack API validation passes
        $subaccountData = [
            'business_name'      => $user->name . ' (Unicrib Landlord)',
            'settlement_bank'    => trim($request->bank_code),
            'bank_code'          => trim($request->bank_code),
            'account_number'     => trim($request->account_number),
            'percentage_charge'  => 1.0, // Platform commission fee (e.g. 1%)
            'primary_contact_email' => $user->email,
            'description'        => 'Unicrib Landlord - ' . $user->id,
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
            Log::error('Paystack Subaccount Creation Error: ' . $e->getMessage());
            
            return back()->withErrors(['paystack_error' => 'Failed to create subaccount: ' . $e->getMessage()]);
        }
    }
}