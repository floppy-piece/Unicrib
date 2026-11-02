<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected string $version;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $this->version = 'v1';
    }

    /**
     * Send an interactive text message with optional inline buttons.
     */
    public function sendInlineButtons(string $chatId, string $text, array $inlineKeyboard = []): bool
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if (!empty($inlineKeyboard)) {
            $payload['reply_markup'] = [
                'inline_keyboard' => $inlineKeyboard
            ];
        }

        return $this->postToApi('sendMessage', $payload);
    }

    /**
     * Send a photo with an optional caption to the user.
     */
    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): bool
    {
        $payload = [
            'chat_id' => $chatId,
            'photo' => $photoUrl, // Can be a public URL (e.g. asset('storage/...'))
            'caption' => $caption,
            'parse_mode' => 'HTML',
        ];

        return $this->postToApi('sendPhoto', $payload);
    }

    /**
     * Answer callback queries from inline buttons.
     */
    public function answerCallbackQuery(string $callbackQueryId, string $text = '', bool $showAlert = false): bool
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
        $url = "https://api.telegram.org/bot{$this->token}/{$method}";

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            Log::error("Telegram API Error ({$method})", [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        }

        return true;
    }
}