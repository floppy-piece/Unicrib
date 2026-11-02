<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $phoneId;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN', ''));
        $this->phoneId = env('WHATSAPP_PHONE_NUMBER_ID', '');
        $version = env('WHATSAPP_API_VERSION', 'v17.0');
        $this->apiUrl = rtrim(env('WHATSAPP_API_BASE_URL', "https://graph.facebook.com/{$version}"), '/');
    }

    /**
     * Send a text message to a WhatsApp recipient.
     */
    public function sendMessage(string $recipientPhone, string $text): bool
    {
        if (empty($this->token) || empty($this->phoneId)) {
            Log::info("WhatsApp simulated message to {$recipientPhone}: {$text}");
            return false;
        }

        $response = Http::withToken($this->token)->post("{$this->apiUrl}/{$this->phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhone,
            'type' => 'text',
            'text' => ['body' => $text]
        ]);

        if ($response->failed()) {
            Log::error('WhatsApp sendMessage failed:', ['response' => $response->body()]);
            return false;
        }

        return true;
    }

    /**
     * Send an image with a caption to a WhatsApp recipient.
     */
    public function sendImage(string $recipientPhone, string $imageUrl, string $caption = ''): bool
    {
        if (empty($this->token) || empty($this->phoneId)) {
            Log::info("WhatsApp simulated image to {$recipientPhone}: {$imageUrl} - {$caption}");
            return false;
        }

        $response = Http::withToken($this->token)->post("{$this->apiUrl}/{$this->phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhone,
            'type' => 'image',
            'image' => [
                'link' => $imageUrl,
                'caption' => $caption
            ]
        ]);

        if ($response->failed()) {
            Log::error('WhatsApp sendImage failed:', ['response' => $response->body()]);
            // Fallback to text message if image link fails
            return $this->sendMessage($recipientPhone, $caption . "\n\n(Image preview could not be loaded)");
        }

        return true;
    }
}