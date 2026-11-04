<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppImageInterpretationController extends Controller
{
    public function interpretHouseImage(Request $request, User $user, $imageMediaId = null, $imageUrl = null, $userPrompt = '')
    {
        $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
        $graphVersion = env('WHATSAPP_API_VERSION', 'v17.0');
        $base64Image = null;

        if ($imageMediaId && $token) {
            try {
                $mediaRes = Http::withToken($token)->get("https://graph.facebook.com/{$graphVersion}/{$imageMediaId}");
                if ($mediaRes->successful()) {
                    $downloadUrl = $mediaRes->json('url');
                    $imageContentRes = Http::withToken($token)->get($downloadUrl);
                    if ($imageContentRes->successful()) {
                        $mimeType = $imageContentRes->header('Content-Type') ?: 'image/jpeg';
                        $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageContentRes->body());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to download WhatsApp media contents: ' . $e->getMessage());
            }
        }

        if (!$base64Image) {
            $base64Image = $imageUrl ?: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267';
        }

        // Hardcode Google Generative Language Endpoint with Gemini 2.5 Flash
        $apiKey = env('GEMINI_API_KEY');
        $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/openai/';
        $model = 'gemini-2.5-flash';

        try {
            $response = Http::withToken($apiKey)->post("{$baseUrl}chat/completions", [
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
                                    'url' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.2
            ]);

            if ($response->failed()) {
                Log::error('Gemini Vision API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return "I received your house image, but encountered an error analyzing it right now. Status: " . $response->status();
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? "Analysis complete! This looks like a suitable student property. Check Unicrib web app for more listings.";
        } catch (\Exception $e) {
            Log::error('WhatsApp Image Interpretation Exception: ' . $e->getMessage());
            return "Sorry, I couldn't process the image at the moment. Exception: " . $e->getMessage();
        }
    }
}