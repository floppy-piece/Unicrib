<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioTranscriptionService
{
    public function transcribeAudioUrl(string $audioFileUrl): array
    {
        try {
            $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
            
            // Download audio binary from WhatsApp
            $fileResponse = Http::withToken($token)->get($audioFileUrl);
            $fileContents = $fileResponse->body();
            
            if (empty($fileContents)) {
                $fileContents = @file_get_contents($audioFileUrl);
            }

            if (empty($fileContents)) {
                return ['success' => false, 'error' => 'Failed to download audio binary from WhatsApp.'];
            }

            $audioBase64 = base64_encode($fileContents);

            // Use Google Generative Language API with Gemini 2.5 Flash
            $apiKey = env('GEMINI_API_KEY');
            $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/openai/';

            $response = Http::withToken($apiKey)->post("{$baseUrl}chat/completions", [
                'model' => 'gemini-2.5-flash',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Listen to this voice note from a student in Kenya searching for housing or storage. Transcribe what they said accurately into text (in Swahili or English as spoken):'
                            ],
                            [
                                'type' => 'image_url', // Using standard OpenAI-compatible multimodal content block for audio/media bytes if supported, or inline data
                                'image_url' => [
                                    'url' => 'data:audio/ogg;base64,' . $audioBase64
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 300
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content') ?? '';
                if (!empty($text)) {
                    return ['success' => true, 'text' => trim($text)];
                }
            }

            // Fallback: If direct audio block isn't parsed, return a clean default text so user isn't blocked
            Log::error('Gemini Audio Transcription Error: ' . $response->body());
            return ['success' => false, 'error' => 'Gemini API Error: ' . $response->body()];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => "Transcription Exception: " . $e->getMessage()];
        }
    }
}