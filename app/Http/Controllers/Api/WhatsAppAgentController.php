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
                        $transcriptionResult = app(AudioTranscriptionService::class)->transcribeAudioUrl($downloadUrl);

                        if ($transcriptionResult['success']) {
                            $transcribedText = $transcriptionResult['text'];
                            $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $transcribedText, $senderPhone, 'whatsapp');
                            $this->sendWhatsAppReply($senderPhone, "🗣️ *Voice Transcribed:* \"_{$transcribedText}_\"\n\n" . $aiResponse);
                            return response()->json(['status' => 'voice_success']);
                        } else {
                            $debugError = $transcriptionResult['error'];
                            $this->sendWhatsAppReply($senderPhone, "⚠️ *Transcription Debug Error:* \n```{$debugError}```");
                            return response()->json(['status' => 'voice_failed', 'error' => $debugError]);
                        }
                    } else {
                        $mediaError = "Failed to fetch media URL from WhatsApp Graph API. Status: " . $mediaRes->status();
                        $this->sendWhatsAppReply($senderPhone, "⚠️ *Media Fetch Error:* \n{$mediaError}");
                        return response()->json(['status' => 'media_fetch_failed', 'error' => $mediaError]);
                    }
                }

                $this->sendWhatsAppReply($senderPhone, "⚠️ Could not retrieve audio media ID from message payload.");
                return response()->json(['status' => 'no_media_id']);
            }

            // 2. Handle Photo / Image Uploads
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

                // Student Vision Interpretation using Llama-3.2-11B-Vision-Instruct Controller
                $aiResponse = app(WhatsAppImageInterpretationController::class)
                    ->interpretHouseImage($request, $user, $imageMediaId, null, $caption);

                $this->sendWhatsAppReply($senderPhone, $aiResponse);
                return response()->json(['status' => 'success']);
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

            $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $userMessage, $senderPhone, 'whatsapp');
            $this->sendWhatsAppReply($senderPhone, $aiResponse);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($senderPhone)) {
                $this->sendWhatsAppReply($senderPhone, "⚠️ An error occurred while processing your request. Please try again in a moment!");
            }

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