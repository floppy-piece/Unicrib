<?php

/*namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;   // ✅ correct import
use App\Services\MpesaService;
use App\Models\MpesaTransaction;


class PaymentController extends Controller
{
    // STUDENT

    public function showStudentForm()
    {
        return view('payments.student');
    }

    public function handleStudentPayment(Request $request)
    {

        // TODO: integrate real payment here (M-Pesa/STK push or card)
        $user = Auth::user();

        // Only logged-in students allowed
        if (! $user || $user->category !== 'student') {
            abort(403);
        }
    
        // Get and validate the phone number
        $request->validate([
            'phone' => ['required', 'string'],
        ]);
    
        // You can configure this in config/payments.php or .env
        $amount      = (int) env('MPESA_STUDENT_AMOUNT', 50);
        $reference   = env('MPESA_STUDENT_ACCOUNT_REFERENCE', 'STUDENT_VERIFICATION');
        $description = env('MPESA_STUDENT_TRANSACTION_DESC', 'Student verification payment');
    
        try {
            // Send STK push
            $response = $mpesa->stkPush(
                $request->input('phone'),
                $amount,
                $reference,
                $description
            );
    
            // Save transaction with purpose = STUDENT_VERIFICATION
            MpesaTransaction::create([
                'user_id'             => $user->id,
                'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
                'amount'              => $amount,
                'phone'               => $request->input('phone'),
                'status'              => 'PENDING',
                'purpose'             => 'STUDENT_VERIFICATION',
            ]);
    
            return back()->with(
                'success',
                'Payment prompt sent to your phone. Complete it on M‑Pesa to verify your account.'
            );
        } catch (\Throwable $e) {
            \Log::error('Student M-Pesa STK error', ['error' => $e->getMessage()]);
    
            return back()->with(
                'error',
                'We are having a technical difficulty.Could not start student payment. Please try again later.'
            );
        }
    }

    // LANDLORD

    public function showLandlordForm()
    {
        return view('payments.landlord');
    }

    public function handleLandlordPayment(Request $request)
    {

        // TODO: integrate real payment here
       
    $user = Auth::user();

    // Only logged-in landlords
    if (! $user || $user->category !== 'landlord') {
        abort(403);
    }

    // Validate phone input
    $request->validate([
        'phone' => ['required', 'string'],
    ]);

    // You can move this to config or .env
    $amount      = (int) env('MPESA_LANDLORD_AMOUNT', 500);
    $reference   = env('MPESA_LANDLORD_ACCOUNT_REFERENCE', 'LANDLORD_VERIFICATION');
    $description = env('MPESA_LANDLORD_TRANSACTION_DESC', 'Landlord verification payment');
    
    try {
        // 1) Trigger M-Pesa STK push
        $response = $mpesa->stkPush(
            $request->input('phone'),
            $amount,
            $reference,
            $description
        );

        // 2) Save transaction as PENDING with purpose = LANDLORD_VERIFICATION
        MpesaTransaction::create([
            'user_id'             => $user->id,
            'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            'merchant_request_id' => $response['MerchantRequestID'] ?? null,
            'amount'              => $amount,
            'phone'               => $request->input('phone'),
            'status'              => 'PENDING',
            'purpose'             => 'LANDLORD_VERIFICATION',
        ]);

        // 3) Tell landlord to complete payment on phone
        return back()->with(
            'success',
            'Payment prompt sent to your phone. Complete it on M-Pesa to verify your property.'
        );
    } catch (\Throwable $e) {
        Log::error('Landlord M-Pesa STK error', ['error' => $e->getMessage()]);

        return back()->with(
            'error',
            'We are having a technical difficulty.Could not start property payment. Please try again later.'
        );
    }
}    

    /**
     * Daraja STK callback URL
     
    public function mpesaCallback(Request $request)
    {
        $payload = $request->all();
    
        if (! isset($payload['Body']['stkCallback'])) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid callback'], 400);
        }
    
        $stk        = $payload['Body']['stkCallback'];
        $resultCode = $stk['ResultCode'];
        $resultDesc = $stk['ResultDesc'];
        $checkoutId = $stk['CheckoutRequestID'];
    
        $transaction = MpesaTransaction::where('checkout_request_id', $checkoutId)->first();
    
        if (! $transaction) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received'], 200);
        }
    
        if ($resultCode !== 0) {
            $transaction->update([
                'status'       => 'FAILED',
                'raw_callback' => $payload,
            ]);
    
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received'], 200);
        }
    
        // Success branch
        $items     = $stk['CallbackMetadata']['Item'] ?? [];
        $mpesaCode = null;
    
        foreach ($items as $item) {
            if (($item['Name'] ?? '') === 'MpesaReceiptNumber') {
                $mpesaCode = $item['Value'] ?? null;
            }
        }
    
        $transaction->update([
            'status'       => 'SUCCESS',
            'mpesa_receipt'=> $mpesaCode,
            'raw_callback' => $payload,
        ]);
    
        $user = $transaction->user;
    
        if (! $user) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received'], 200);
        }
    
        // 1) Landlord verification: account + properties
        if (
            $transaction->purpose === 'LANDLORD_VERIFICATION' &&
            $user->category === 'landlord' &&
            ! $user->is_verified
        ) {
            $user->update([
                'is_verified'       => true,
                'access_expires_at' => now()->addMonths(4),
            ]);
    
            Property::where('landlord_id', $user->id)->update([
                'is_verified'             => true,
                'verification_expires_at' => now()->addMonths(4),
            ]);
        }
    
        // 2) Student verification: account only
        if (
            $transaction->purpose === 'STUDENT_VERIFICATION' &&
            $user->category === 'student' &&
            ! $user->is_verified
        ) {
            $user->update([
                'is_verified'       => true,
                'access_expires_at' => now()->addHours(6),
            ]);
        }
    
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received'], 200);
    }
    
    public function afterVerificationRedirect()
    {
        return redirect()
            ->route('landlord.dashboard')
            ->with('success', 'Account verified. Students can now view your property');
    }

        
       
}*/
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Models\Property;

class PaymentController extends Controller
{
    //student
    public function showStudentForm()
    {
        return view('payments.student');
    }
    //
    //landlord
    public function showLandlordForm()
    {
        return view('payments.landlord');
    }
    //
    public function redirectToGateway(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:50'],
        ]);

        $user = Auth::user();

        $amountInKobo = (int) $request->amount * 100 ;
        $reference    = Paystack::genTranxRef(); // unique reference[web:51][web:77]

        $payment = Payment::create([
            'user_id'  => $user->id,
            'reference'=> $reference,
            'amount'   => $amountInKobo,
            'currency' => 'KES',
            'status'   => 'pending',
        ]);

        $data = [
            'amount'    => $amountInKobo,
            'email'     => $user->email,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'currency'  => 'KES',
            'metadata'  => [
                'payment_id' => $payment->id,
                'user_id'    => $user->id,
            ],
        ];

        return Paystack::getAuthorizationUrl($data)->redirectNow();
    }

    public function handleGatewayCallback(Request $request)
    {
        \Log::info('Paystack callback hit', ['query' => $request->all()]);

        $paymentDetails = Paystack::getPaymentData();
        \Log::info('Paystack paymentDetails', $paymentDetails);

        if (
            !isset($paymentDetails['status']) ||
            $paymentDetails['status'] !== true ||
            !isset($paymentDetails['data']['status']) ||
            $paymentDetails['data']['status'] !== 'success'
        ) {
            if (!empty($paymentDetails['data']['reference'] ?? null)) {
                Payment::where('reference', $paymentDetails['data']['reference'])
                    ->update([
                        'status'           => 'failed',
                        'provider_payload' => $paymentDetails,
                    ]);
            }

            return redirect()->route('payments.student')->withErrors('Payment failed or cancelled.');
        }

        $reference = $paymentDetails['data']['reference'];
        $amount    = $paymentDetails['data']['amount']; // kobo[web:74][web:77]

        $payment = Payment::where('reference', $reference)->firstOrFail();

        if ((int) $payment->amount !== (int) $amount) {
            $payment->update([
                'status'           => 'failed',
                'provider_payload' => $paymentDetails,
            ]);

            return redirect()->route('payments.student')->withErrors('Amount mismatch.');
        }

        $payment->update([
            'status'           => 'success',
            'provider_payload' => $paymentDetails,
        ]);

        // *** IMPORTANT: verify the user and set is_verified = true for ***
        $user = $payment->user; // relation from Payment model

        if (
            $user &&
            $user->category === 'student' &&
            /*! $user->is_verified && */$payment->status==='success' && $payment->amount >=50
        ) {            
            $user->is_verified = true;
            $user->access_expires_at = now()->addHours(6);
            $user->save();
        }

        //landlord property verification
        if (
            $user &&
            $user->category === 'landlord' &&
            /*! $user->is_verified && */$payment->status==='success' && $payment->amount >= 500
        ) {
            $user->is_verified = true;
            $user->access_expires_at = now()->addMonths(4);
            $user->save();
    
            Property::where('landlord_id', $user->id)->update([
                'is_verified'             => true,
                'verification_expires_at' => now()->addMonths(4),
            ]);
        }
    

        // here you can also mark any booking/order as paid if needed

        return redirect()->route('dashboard')->with('status', 'Payment successful. Account verified.');
    }
}

