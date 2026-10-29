<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $version;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->version = config('services.whatsapp.version', 'v18.0');
    }

    /**
     * Send an Interactive Quick Reply Button Message (Max 3 buttons)
     *
     * @param string $recipientPhone Phone number in international format (e.g., 254712345678)
     * @param string $bodyText Main message text
     * @param array $buttons Array of buttons: [['id' => 'btn_1', 'title' => 'Book Now'], ...]
     * @param string|null $headerText Optional header text
     * @param string|null $footerText Optional footer text
     */
    public function sendButtonMessage(
        string $recipientPhone,
        string $bodyText,
        array $buttons,
        ?string $headerText = null,
        ?string $footerText = null
    ): bool {
        $formattedButtons = [];
        foreach ($buttons as $button) {
            $formattedButtons[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => $button['id'],
                    'title' => substr($button['title'], 0, 20), // Max 20 chars per button title
                ]
            ];
        }

        $interactive = [
            'type' => 'button',
            'body' => ['text' => $bodyText],
            'action' => ['buttons' => $formattedButtons],
        ];

        if ($headerText) {
            $interactive['header'] = ['type' => 'text', 'text' => $headerText];
        }

        if ($footerText) {
            $interactive['footer'] = ['text' => $footerText];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhone,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        return $this->postToApi($payload);
    }

    /**
     * Send an Interactive List Message (Max 10 items)
     */
    public function sendListMessage(
        string $recipientPhone,
        string $bodyText,
        string $buttonLabel,
        array $sections,
        ?string $headerText = null,
        ?string $footerText = null
    ): bool {
        $interactive = [
            'type' => 'list',
            'body' => ['text' => $bodyText],
            'action' => [
                'button' => substr($buttonLabel, 0, 20),
                'sections' => $sections,
            ],
        ];

        if ($headerText) {
            $interactive['header'] = ['type' => 'text', 'text' => $headerText];
        }

        if ($footerText) {
            $interactive['footer'] = ['text' => $footerText];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhone,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        return $this->postToApi($payload);
    }

    protected function postToApi(array $payload): bool
    {
        $url = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->token)->post($url, $payload);

        if ($response->failed()) {
            Log::error('WhatsApp API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        }

        return true;
    }
}