<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioTranscriptionService
{
    /**
     * Transcribe incoming WhatsApp voice note using the deployed Modal endpoint model.
     */
    public function transcribeAudioUrl(string $audioFileUrl): array
    {
        try {
            $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
            
            // Download the voice file binary from WhatsApp Graph API
            $fileResponse = Http::withToken($token)->get($audioFileUrl);
            $fileContents = $fileResponse->body();
            
            if (empty($fileContents) || !$fileResponse->successful()) {
                $fileContents = @file_get_contents($audioFileUrl);
            }

            if (empty($fileContents)) {
                return [
                    'success' => false, 
                    'error' => 'Failed to download audio binary from WhatsApp URL.'
                ];
            }

            $tempPath = sys_get_temp_dir() . '/' . uniqid('voice_', true) . '.ogg';
            file_put_contents($tempPath, $fileContents);

            $apiKey = env('META_API_KEY');
            $baseUrl = rtrim(env('AI_AUDIO_BASE_URL', 'https://floppy-piece--ep-muse-spark-open-816b-a42b-server.us-west.modal.direct/v1'), '/');
            
            // Send to Modal transcription endpoint with your active model identifier
            $response = Http::withToken($apiKey)
                ->attach('file', fopen($tempPath, 'r'), 'voice.ogg')
                ->post("{$baseUrl}/audio/transcriptions", [
                    'model' => 'meta-models/Muse-Spark-Open-816B-A42B', // Matches your active Modal server model
                    'language' => 'sw', // Swahili/English support hint
                ]);

            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }

            if ($response->successful()) {
                $text = $response->json('text') ?? $response->json('transcript') ?? $response->json('choices.0.message.content') ?? '';
                if (!empty($text)) {
                    return ['success' => true, 'text' => $text];
                }
                return [
                    'success' => false, 
                    'error' => 'Modal returned successful status but empty transcription text. Body: ' . $response->body()
                ];
            }

            $errorMsg = "Modal API Failed [HTTP Status " . $response->status() . "]: " . $response->body();
            Log::error('Voice Transcription API Error: ' . $errorMsg);
            
            return ['success' => false, 'error' => $errorMsg];

        } catch (\Exception $e) {
            $errorMsg = "Transcription Exception: " . $e->getMessage();
            Log::error($errorMsg);
            
            return ['success' => false, 'error' => $errorMsg];
        }
    }
}