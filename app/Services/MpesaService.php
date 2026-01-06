<?php
// app/Services/MpesaService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected string $env;
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $shortCode;
    protected string $passKey;
    protected string $callbackUrl;

    public function __construct()
    {
        $this->env           = config('services.mpesa.env', 'sandbox');
        $this->consumerKey   = config('services.mpesa.consumer_key');
        $this->consumerSecret= config('services.mpesa.consumer_secret');
        $this->shortCode     = config('services.mpesa.shortcode');
        $this->passKey       = config('services.mpesa.passkey');
        $this->callbackUrl   = config('services.mpesa.callback_url');
    }

    protected function baseUrl(): string
    {
        return $this->env === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    protected function accessToken(): string
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl().'/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            Log::error('M-Pesa access token error', [
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Could not generate M-Pesa access token');
        }

        return $response->json()['access_token'];
    }

    protected function password(string $timestamp): string
    {
        return base64_encode($this->shortCode.$this->passKey.$timestamp);
    }

    /**
     * Initiate STK push request
     */
    public function stkPush(string $phoneNumber, int $amount, string $accountReference, string $description)
    {
        $timestamp = now()->format('YmdHis');
        $password  = $this->password($timestamp);

        // Ensure phone in 2547XXXXXXXX format
        $phoneNumber = $this->normalizePhone($phoneNumber);

        $payload = [
            'BusinessShortCode' => (int) $this->shortCode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $phoneNumber,
            'PartyB'            => (int) $this->shortCode,
            'PhoneNumber'       => $phoneNumber,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $accountReference,
            'TransactionDesc'   => $description,
        ];

        $token = $this->accessToken();

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl().'/mpesa/stkpush/v1/processrequest', $payload);

        Log::info('M-Pesa STK push request', [
            'payload'  => $payload,
            'response' => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException('STK push request failed');
        }

        return $response->json();
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // keep digits

        if (str_starts_with($phone, '0')) {
            return '254'.substr($phone, 1);
        }

        if (str_starts_with($phone, '254')) {
            return $phone;
        }

        if (str_starts_with($phone, '7')) {
            return '254'.$phone;
        }

        throw new \InvalidArgumentException('Invalid phone number format');
    }
}
