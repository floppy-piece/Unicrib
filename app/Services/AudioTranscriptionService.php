<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioTranscriptionService
{
    /**
     * Transcribe incoming voice message audio file to text.
     */
    public function transcribeAudioUrl(string $audioFileUrl): string
    {
        try {
            // Download the voice file locally or into temporary storage
            $fileContents = file_get_contents($audioFileUrl);
            $tempPath = sys_get_temp_dir() . '/' . uniqid('voice_', true) . '.ogg';
            file_put_contents($tempPath, $fileContents);

            // Send to Whisper or your configured AI Speech-to-Text endpoint
            $apiKey = env('OPENAI_API_KEY') ?? env('META_API_KEY');
            
            $response = Http::withToken($apiKey)
                ->attach('file', file_get_contents($tempPath), 'voice.ogg')
                ->post('https://floppy-piece--ep-muse-spark-open-816b-a42b-server.us-west.modal.direct/v1/chat/completions', [
                    'model' => 'meta-models/Muse-Spark-Open-816B-A42B',
                    'language' => 'sw', // Supports Swahili, English, and auto-detection
                ]);

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            if ($response->successful()) {
                return $response->json('text') ?? '';
            }

            Log::error('Voice Transcription Error: ' . $response->body());
            return '';
        } catch (\Exception $e) {
            Log::error('Voice Transcription Exception: ' . $e->getMessage());
            return '';
        }
    }
}