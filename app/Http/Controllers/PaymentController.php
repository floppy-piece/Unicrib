<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Models\Property;
use App\Models\StorageBooking;

class PaymentController extends Controller
{
    // Student payment form view
    public function showStudentForm()
    {
        return view('payments.student');
    }

    // Landlord payment form view
    public function showLandlordForm()
    {
        return view('payments.landlord');
    }

    // General gateway redirection for /pay
    public function redirectToGateway(Request $request)
    {
        $user = Auth::user();
        
        // Default amount: KES 500 for landlords, KES 50 for students unless specified
        $amountInKes = $request->input('amount', $user->category === 'landlord' ? 500 : 50);
        $amountInCents = $amountInKes * 100; // Paystack expects minor units (cents)
        
        $reference = Paystack::genTranxRef();

        $payment = Payment::create([
            'user_id'          => $user->id,
            'reference'        => $reference,
            'amount'           => $amountInCents,
            'currency'         => 'KES',
            'status'           => 'pending',
            'provider_payload' => $request->all(),
        ]);

        $data = [
            'amount'       => $amountInCents,
            'email'        => $user->email,
            'reference'    => $reference,
            'callback_url' => route('payment.callback'),
            'currency'     => 'KES',
            'metadata'     => [
                'payment_id' => $payment->id,
                'user_id'    => $user->id,
                'category'   => $user->category,
            ],
        ];

        return Paystack::getAuthorizationUrl($data)->redirectNow();
    }

    // Dedicated handler for student 6-hour access fee (KES 50)
    public function handleStudentPayment(Request $request)
    {
        $request->merge(['amount' => 50]);
        return $this->redirectToGateway($request);
    }

    // Dedicated handler for landlord semester verification fee (KES 500)
    public function handleLandlordPayment(Request $request)
    {
        $request->merge(['amount' => 500]);
        return $this->redirectToGateway($request);
    }

    // Handle Paystack callback after payment completion
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

            return redirect()->route('dashboard')->withErrors('Payment failed or cancelled.');
        }

        $reference = $paymentDetails['data']['reference'];
        $amountInCents = $paymentDetails['data']['amount']; 
        $amountInKes = $amountInCents / 100;

        $payment = Payment::where('reference', $reference)->firstOrFail();

        if ((int) $payment->amount !== (int) $amountInCents) {
            $payment->update([
                'status'           => 'failed',
                'provider_payload' => $paymentDetails,
            ]);

            return redirect()->route('dashboard')->withErrors('Amount mismatch.');
        }

        $payment->update([
            'status'           => 'success',
            'provider_payload' => $paymentDetails,
        ]);

        $user = $payment->user;
        $metadata = $paymentDetails['data']['metadata'] ?? [];

        // ==========================================
        // 1. STORAGE HOUSING PAYMENT
        // ==========================================
        if (!empty($metadata['storage_booking_id'])) {
            $storageBooking = StorageBooking::find($metadata['storage_booking_id']);

            if ($storageBooking) {
                $newPaidAmount = $storageBooking->paid_amount + $amountInKes;
                
                $storageBooking->update([
                    'status'      => 'active',
                    'paid_amount' => $newPaidAmount,
                    'reference'   => $reference,
                ]);
            }

            return redirect()
                ->route('store-housing')
                ->with('status', 'Storage initial deposit of KSh ' . number_format($amountInKes) . ' received. Booking is active!');
        }

        // ==========================================
        // 2. STUDENT 6-HOUR PROPERTY ACCESS FEE (KES 50)
        // ==========================================
        if ($user && $user->category === 'student' && $amountInKes >= 50 && empty($metadata['storage_booking_id'])) {
            $user->is_verified = true;
            $user->access_expires_at = now()->addHours(6);
            $user->save();

            return redirect()
                ->route('student.dashboard')
                ->with('status', 'Student access granted for 6 hours.');
        }

        // ==========================================
        // 3. LANDLORD SEMESTER VERIFICATION FEE (KES 500)
        // ==========================================
        if ($user && $user->category === 'landlord' && $amountInKes >= 500) {
            $user->is_verified = true;
            $user->access_expires_at = now()->addMonths(4);
            $user->save();

            Property::where('landlord_id', $user->id)->update([
                'is_verified'             => true,
                'verification_expires_at' => now()->addMonths(4),
            ]);

            return redirect()
                ->route('landlord.dashboard')
                ->with('status', 'Landlord account and properties verified for the semester.');
        }

        return redirect()->route('dashboard')->with('status', 'Payment completed successfully.');
    }

    public function showStorageForm(StorageBooking $storageBooking)
    {
        $monthlyPrice = 800;
        $upfrontAmount = $monthlyPrice; 

        return view('payments.storage', [
            'booking' => $storageBooking,
            'upfrontAmount' => $upfrontAmount,
        ]);
    }

    public function processStoragePayment(Request $request, StorageBooking $storageBooking)
    {
        $user = Auth::user();
        $monthlyPrice = 800;
        $upfrontAmountInCents = $monthlyPrice * 100; 
        $reference = Paystack::genTranxRef();

        $payment = Payment::create([
            'user_id'          => $user->id,
            'reference'        => $reference,
            'amount'           => $upfrontAmountInCents,
            'currency'         => 'KES',
            'status'           => 'pending',
            'provider_payload' => ['storage_booking_id' => $storageBooking->id],
        ]);

        $data = [
            'amount'       => $upfrontAmountInCents,
            'email'        => $user->email,
            'reference'    => $reference,
            'callback_url' => route('payment.callback'),
            'currency'     => 'KES',
            'metadata'     => [
                'payment_id'         => $payment->id,
                'user_id'            => $user->id,
                'storage_booking_id' => $storageBooking->id,
            ],
        ];

        return Paystack::getAuthorizationUrl($data)->redirectNow();
    }
}