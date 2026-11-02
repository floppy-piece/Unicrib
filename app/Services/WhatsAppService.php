<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('services.telegram.token') ?: env('TELEGRAM_BOT_TOKEN', '');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Send a text message with optional inline buttons.
     */
    public function sendInlineButtons(int|string $chatId, string $text, array $inlineKeyboard = [], string $parseMode = 'HTML'): bool
    {
        if (empty($this->token)) {
            Log::error('Telegram Service Error: TELEGRAM_BOT_TOKEN is missing.');
            return false;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ];

        if (!empty($inlineKeyboard)) {
            $payload['reply_markup'] = json_encode([
                'inline_keyboard' => $inlineKeyboard
            ]);
        }

        $response = Http::post("{$this->apiUrl}/sendMessage", $payload);

        if ($response->failed()) {
            Log::error('Telegram sendMessage failed:', ['response' => $response->body()]);
            return false;
        }

        return true;
    }

    /**
     * Send a photo with a caption and optional inline buttons.
     */
    public function sendPhoto(int|string $chatId, string $photoUrl, string $caption = '', array $inlineKeyboard = [], string $parseMode = 'HTML'): bool
    {
        if (empty($this->token)) {
            Log::error('Telegram Service Error: TELEGRAM_BOT_TOKEN is missing.');
            return false;
        }

        $payload = [
            'chat_id' => $chatId,
            'photo' => $photoUrl,
            'caption' => $caption,
            'parse_mode' => $parseMode,
        ];

        if (!empty($inlineKeyboard)) {
            $payload['reply_markup'] = json_encode([
                'inline_keyboard' => $inlineKeyboard
            ]);
        }

        $response = Http::post("{$this->apiUrl}/sendPhoto", $payload);

        if ($response->failed()) {
            Log::error('Telegram sendPhoto failed:', ['response' => $response->body()]);
            
            // Fallback: if photo URL fails to load, send as formatted text message
            return $this->sendInlineButtons($chatId, $caption . "\n\n<i>(Photo preview could not be loaded)</i>", $inlineKeyboard, $parseMode);
        }

        return true;
    }

    /**
     * Answer a callback query from inline buttons.
     */
    public function answerCallbackQuery(string $callbackQueryId, string $text = '', bool $showAlert = false): bool
    {
        if (empty($this->token)) return false;

        $response = Http::post("{$this->apiUrl}/answerCallbackQuery", [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
        ]);

        return $response->successful();
    }
}