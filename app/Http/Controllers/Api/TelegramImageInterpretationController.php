<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramImageInterpretationController extends Controller
{
    /**
     * Interpret house image sent via Telegram, ensuring user verification.
     */
    public function interpretHouseImage(User $user, $fileId = null, $userPrompt = '')
    {
        if (!$user->is_verified) {
            return "🔒 Payment Verification Required!\nTo use the Unicrib Telegram AI House Image Finder and get extra property details, please complete your account payment verification on the Unicrib web app first.";
        }

        $resolvedImageUrl = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267';

        // Resolve image URL from Telegram Bot API if file_id is provided
        if ($fileId) {
            $token = env('TELEGRAM_BOT_TOKEN');
            if ($token) {
                try {
                    $fileRes = Http::get("https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}");
                    if ($fileRes->successful() && $fileRes->json('ok')) {
                        $filePath = $fileRes->json('result.file_path');
                        $resolvedImageUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch Telegram file path: ' . $e->getMessage());
                }
            }
        }

        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');

        try {
            $response = Http::withToken($apiKey)->post("{$baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are Unicrib AI Vision Assistant in Kenya. Analyze the provided house/property image and extract detailed property insights: estimated monthly rent in KSh, estimated number of bedrooms, architectural type (hostel/apartment/bedsitter/single room), condition, and potential student housing suitability.'
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $userPrompt ?: 'Please analyze this house photo and provide extra details.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $resolvedImageUrl
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 500
            ]);

            if ($response->failed()) {
                Log::error('Telegram Image Interpretation API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return "I received your house image, but encountered an error analyzing it right now. Please try again!";
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? "Analysis complete! This looks like a suitable student property. Check Unicrib web app for more listings.";
        } catch (\Exception $e) {
            Log::error('Telegram Image Interpretation Exception: ' . $e->getMessage());
            return "Sorry, I couldn't process the image at the moment. Please try again!";
        }
    }
}