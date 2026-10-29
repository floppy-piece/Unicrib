<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandlordController extends Controller
{
    public function createSubaccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required',
            'bank_code' => 'required',
        ]);

        $user = $request->user();

        // Exact payload that worked in your cURL test
        $subaccountData = [
            'business_name'         => $user->name . ' (Unicrib Landlord)',
            'settlement_bank'       => trim($request->bank_code),
            'account_number'        => trim($request->account_number),
            'percentage_charge'     => 1.0,
            'primary_contact_email' => $user->email,
            'description'           => 'Unicrib Landlord - ' . $user->id,
        ];

        try {
            $secretKey = config('paystack.secretKey') ?: config('services.paystack.secret');

            $response = Http::withToken($secretKey)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])
                ->post('https://api.paystack.co/subaccount', $subaccountData);

            $result = $response->json();

            if (!$response->successful() || !($result['status'] ?? false)) {
                $errMsg = $result['message'] ?? 'Unknown Paystack error occurred.';
                throw new \Exception($errMsg);
            }

            $subaccountCode = $result['data']['subaccount_code'] ?? null;

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