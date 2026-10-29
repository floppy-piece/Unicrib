<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send a message with Inline Callback Buttons
     *
     * @param string|int $chatId Telegram user chat ID
     * @param string $text Message body text (supports HTML formatting)
     * @param array $buttons Matrix of buttons: [[['text' => 'Btn 1', 'callback_data' => 'action_1']]]
     */
    public function sendInlineButtons(string|int $chatId, string $text, array $buttons): bool
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons,
            ]),
        ];

        return $this->postToApi('sendMessage', $payload);
    }

    /**
     * Answer Callback Query (Acknowledge button tap to remove loading spinner)
     */
    public function answerCallbackQuery(string $callbackQueryId, ?string $text = null, bool $showAlert = false): bool
    {
        $payload = [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
        ];

        return $this->postToApi('answerCallbackQuery', $payload);
    }

    protected function postToApi(string $method, array $payload): bool
    {
        $response = Http::post("{$this->apiUrl}/{$method}", $payload);

        if ($response->failed()) {
            Log::error('Telegram API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        }

        return true;
    }
}