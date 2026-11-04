<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transport;
use App\Services\AudioTranscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhatsAppAgentController extends Controller
{
    /**
     * Verify WhatsApp Webhook token from Meta
     */
    public function verifyWebhook(Request $request)
    {
        $verifyToken = env('WHATSAPP_WEBHOOK_VERIFY_TOKEN');
        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === $verifyToken) {
            return response($request->get('hub_challenge'), 200);
        }
        return response('Unauthorized', 403);
    }

    /**
     * Handle incoming WhatsApp messages (Text, Photos, Voice Notes, Interactive Buttons)
     */
    public function handleIncomingMessage(Request $request)
    {
        try {
            $rawContent = $request->getContent();
            $input = json_decode($rawContent, true) ?? [];

            $value = $input['entry'][0]['changes'][0]['value'] ?? null;
            Log::info('WhatsApp Raw Payload: ' . $rawContent);

            $messageData = $value['messages'][0] ?? null;
            if (!$messageData) {
                return response()->json(['status' => 'ignored']);
            }

            $senderPhone = $messageData['from'] ?? null;
            $messageType = $messageData['type'] ?? 'text';

            if (!$senderPhone) {
                return response()->json(['status' => 'no_sender_phone']);
            }

            // Authenticate user by phone number
            $user = User::where('phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

            if (!$user) {
                $this->sendWhatsAppReply($senderPhone, "Welcome to Unicrib! We couldn't find an account linked to this WhatsApp number. Please register on our web app first.");
                return response()->json(['status' => 'user_not_found']);
            }

            // 1. Handle Voice / Audio Notes (Transcription + Multilingual AI Pipeline)
            if ($messageType === 'audio' || $messageType === 'voice') {
                $audioMediaId = $messageData['audio']['id'] ?? $messageData['voice']['id'] ?? null;

                if ($audioMediaId) {
                    $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
                    $graphVersion = env('WHATSAPP_API_VERSION', 'v17.0');

                    $mediaRes = Http::withToken($token)->get("https://graph.facebook.com/{$graphVersion}/{$audioMediaId}");
                    if ($mediaRes->successful()) {
                        $downloadUrl = $mediaRes->json('url');
                        
                        // Transcribe using AudioTranscriptionService
                        $transcribedText = app(AudioTranscriptionService::class)->transcribeAudioUrl($downloadUrl);

                        if (!empty($transcribedText)) {
                            $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $transcribedText, $senderPhone, 'whatsapp');
                            $this->sendWhatsAppReply($senderPhone, "🗣️ *Voice Transcribed:* \"_{$transcribedText}_\"\n\n" . $aiResponse);
                            return response()->json(['status' => 'voice_success']);
                        }
                    }
                }

                $this->sendWhatsAppReply($senderPhone, "⚠️ Could not transcribe your voice note. Please try typing your request.");
                return response()->json(['status' => 'voice_failed']);
            }

            // 2. Handle Photo / Image Uploads (Integrated with Llama Vision)
            if ($messageType === 'image') {
                $imageMediaId = $messageData['image']['id'] ?? null;
                $caption = trim($messageData['image']['caption'] ?? '');

                if ($user->category === 'landlord') {
                    $propertyId = trim(filter_var($caption, FILTER_SANITIZE_NUMBER_INT));

                    if (empty($propertyId) || !is_numeric($propertyId)) {
                        $this->sendWhatsAppReply($senderPhone, "⚠️ *Missing Property ID!*\n\nPlease specify the Property ID in your photo caption when uploading (e.g., *'Property 2'* or just the number *'2'*).");
                        return response()->json(['status' => 'missing_property_id']);
                    }

                    $property = Property::where('id', (int) $propertyId)->where('landlord_id', $user->id)->first();

                    if ($property && $imageMediaId) {
                        $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
                        $graphVersion = env('WHATSAPP_API_VERSION', 'v17.0');

                        $mediaRes = Http::withToken($token)->get("https://graph.facebook.com/{$graphVersion}/{$imageMediaId}");
                        if ($mediaRes->successful()) {
                            $downloadUrl = $mediaRes->json('url');
                            $fileStream = Http::withToken($token)->get($downloadUrl);

                            if ($fileStream->successful()) {
                                $storagePath = 'properties/' . $user->id . '/' . uniqid() . '.jpg';
                                Storage::disk('public')->put($storagePath, $fileStream->body());

                                $images = $property->images ?? [];
                                $images[] = $storagePath;
                                $property->update(['images' => $images]);

                                $this->sendWhatsAppReply($senderPhone, "✅ New photo successfully added to Property #[ID: {$property->id}] {$property->title}!");
                                return response()->json(['status' => 'success']);
                            }
                        }
                    }

                    $this->sendWhatsAppReply($senderPhone, "❌ Property #{$propertyId} was not found or does not belong to you.");
                    return response()->json(['status' => 'property_not_found']);
                }

                // Student Vision Interpretation using Llama-3.2-11B-Vision-Instruct
                if ($imageMediaId) {
                    $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
                    $graphVersion = env('WHATSAPP_API_VERSION', 'v17.0');

                    $mediaRes = Http::withToken($token)->get("https://graph.facebook.com/{$graphVersion}/{$imageMediaId}");
                    if ($mediaRes->successful()) {
                        $downloadUrl = $mediaRes->json('url');
                        $fileStream = Http::withToken($token)->get($downloadUrl);

                        if ($fileStream->successful()) {
                            $imageBase64 = base64_encode($fileStream->body());
                            $imageUrlData = 'data:image/jpeg;base64,' . $imageBase64;

                            $apiKey = env('META_API_KEY');
                            $baseUrl = rtrim('https://floppy-piece--ep-muse-spark-open-816b-a42b-server.us-west.modal.direct/v1', '/');
                            
                            // Send to Llama-3.2-11B-Vision-Instruct on Modal
                            $visionResponse = Http::withToken($apiKey)->post("{$baseUrl}/chat/completions", [
                                'model' => 'meta-llama/Llama-3.2-11B-Vision-Instruct',
                                'messages' => [
                                    [
                                        'role' => 'user',
                                        'content' => [
                                            [
                                                'type' => 'text',
                                                'text' => "Analyze this image for a student housing search in Kenya. Description or context given by user: '{$caption}'."
                                            ],
                                            [
                                                'type' => 'image_url',
                                                'image_url' => ['url' => $imageUrlData]
                                            ]
                                        ]
                                    ]
                                ],
                                'max_tokens' => 300
                            ]);

                            if ($visionResponse->successful()) {
                                $visualDescription = $visionResponse->json('choices.0.message.content') ?? 'Student property photo';
                                
                                // Pass the extracted visual description into your Unicrib Agent search pipeline
                                $aiResponse = app(UnicribAgentController::class)->handleAiPrompt(
                                    $user, 
                                    "Find a property matching these visual features: {$visualDescription}. {$caption}", 
                                    $senderPhone, 
                                    'whatsapp'
                                );

                                $this->sendWhatsAppReply($senderPhone, $aiResponse);
                                return response()->json(['status' => 'vision_success']);
                            }
                        }
                    }
                }

                $this->sendWhatsAppReply($senderPhone, "⚠️ Could not process your image. Please try adding a text description.");
                return response()->json(['status' => 'vision_failed']);
            }

            // 3. Handle Interactive Button Replies
            if ($messageType === 'interactive') {
                $buttonReplyId = $messageData['interactive']['button_reply']['id'] ?? '';
                $aiResponse = $this->handleInteractiveButtonReply($buttonReplyId, $user, $senderPhone);
                $this->sendWhatsAppReply($senderPhone, $aiResponse);
                return response()->json(['status' => 'success']);
            }

            // 4. Process Text Commands and Multilingual AI Prompts via Unified UnicribAgentController
            $userMessage = $messageData['text']['body'] ?? '';
            if (empty($userMessage)) {
                return response()->json(['status' => 'empty_text']);
            }

            // Delegate fully to UnicribAgentController for tool and multi-language processing
            $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $userMessage, $senderPhone, 'whatsapp');

            // Send the response back to WhatsApp
            $this->sendWhatsAppReply($senderPhone, $aiResponse);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($senderPhone)) {
                $this->sendWhatsAppReply($senderPhone, "⚠️ An error occurred while processing your request. Please try again in a moment!");
            }

            // Return a 200 status so Meta doesn't flood the webhook with retries
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    private function sendWhatsAppReply($recipientPhone, $text)
    {
        $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        $baseUrl = rtrim(env('WHATSAPP_API_BASE_URL', 'https://graph.facebook.com/v17.0'), '/');

        if (!$token || !$phoneId) {
            Log::info("WhatsApp simulated reply to {$recipientPhone}: {$text}");
            return;
        }

        try {
            Http::withToken($token)->post("{$baseUrl}/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $recipientPhone,
                'type' => 'text',
                'text' => ['body' => $text]
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp Reply Exception: ' . $e->getMessage());
        }
    }

    private function handleInteractiveButtonReply($buttonId, $user, $senderPhone)
    {
        if (str_starts_with($buttonId, 'book_house') || $buttonId === 'book_prop') {
            return "🏡 Great choice! To complete your booking and secure this house, please visit the Unicrib web app under Student Dashboard or make your payment verification.";
        }

        return "Action '{$buttonId}' processed successfully by Unicrib AI.";
    }
}