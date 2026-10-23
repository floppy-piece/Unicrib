<?php


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

