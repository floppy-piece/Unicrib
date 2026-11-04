<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioTranscriptionService
{
    /**
     * Transcribe incoming WhatsApp voice note audio file to text using Modal endpoint.
     */
    public function transcribeAudioUrl(string $audioFileUrl): string
    {
        try {
            $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
            
            // Download the voice file binary from WhatsApp Graph API with authorization token
            $fileContents = Http::withToken($token)->get($audioFileUrl)->body();
            
            if (empty($fileContents)) {
                $fileContents = @file_get_contents($audioFileUrl);
            }

            if (empty($fileContents)) {
                Log::error('Voice Transcription Error: Unable to download audio binary.');
                return '';
            }

            $tempPath = sys_get_temp_dir() . '/' . uniqid('voice_', true) . '.ogg';
            file_put_contents($tempPath, $fileContents);

            $apiKey = env('META_API_KEY', env('OPENAI_API_KEY', 'dummy-key'));
            $baseUrl = rtrim(env('AI_AUDIO_BASE_URL', 'https://floppy-piece--ep-muse-spark-open-816b-a42b-server.us-west.modal.direct/v1'), '/');
            
            // Dispatch to Modal audio transcription endpoint
            $response = Http::withToken($apiKey)
                ->attach('file', fopen($tempPath, 'r'), 'voice.ogg')
                ->post("{$baseUrl}/audio/transcriptions", [
                    'model' => 'whisper-1', // Or your modal audio model identifier
                    'language' => 'sw',     // Swahili/English support hint
                ]);

            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }

            if ($response->successful()) {
                return $response->json('text') ?? $response->json('transcript') ?? '';
            }

            Log::error('Voice Transcription API Error Response: ' . $response->body());
            return '';
        } catch (\Exception $e) {
            Log::error('Voice Transcription Exception: ' . $e->getMessage());
            return '';
        }
    }
}