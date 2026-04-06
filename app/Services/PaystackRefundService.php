<?php
// app/Services/PaystackRefundService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackRefundService
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('paystack.paymentUrl', 'https://api.paystack.co'), '/');
        $this->secretKey = config('paystack.secretKey');
    }

    public function refundByReference(string $reference, ?int $amount = null, ?string $currency = null): array
    {
        $payload = [
            'transaction' => $reference,
        ];

        if ($amount !== null) {
            $payload['amount'] = $amount;
        }

        if ($currency !== null) {
            $payload['currency'] = $currency;
        }

        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl.'/refund', $payload);
           // [web:69][web:70]

        if (!$response->successful()) {
            throw new \RuntimeException('Paystack refund failed: '.$response->body());
        }

        return $response->json();
    }
}
