<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TelegramAgentController extends Controller
{
    /**
     * Handle incoming Telegram webhook updates
     */
    public function handleWebhook(Request $request)
    {
        try {
            $input = $request->all();
            Log::info('Telegram Raw Payload: ' . json_encode($input));

            $message = $input['message'] ?? $input['edited_message'] ?? null;
            if (!$message) {
                return response()->json(['status' => 'ignored']);
            }

            $chatId = $message['chat']['id'] ?? null;
            $text = trim($message['text'] ?? $message['caption'] ?? '');
            $photo = $message['photo'] ?? null;

            if (!$chatId) {
                return response()->json(['status' => 'no_chat_id']);
            }

            // Fetch user by Telegram Chat ID
            $user = User::where('telegram_chat_id', $chatId)->first();

            // 1. Handle Telegram /start Command
            if ($text === '/start') {
                if ($user) {
                    $this->sendTelegramReply(
                        $chatId, 
                        "👋 *Welcome back to UniCrib, {$user->name}!*\n\nYour account is connected. Ask me anything about student housing, property listings, storage, or transport!"
                    );
                    return response()->json(['status' => 'welcome_existing_user']);
                } else {
                    $this->sendTelegramReply(
                        $chatId, 
                        "👋 *Welcome to UniCrib AI!*\n\nYour Telegram account is not linked yet. To connect your account, please reply with your registered phone number in this format:\n\n`/link 254712345678`"
                    );
                    return response()->json(['status' => 'welcome_new_user']);
                }
            }

            // 2. Handle /link <phone> Command
            if (!$user && str_starts_with($text, '/link')) {
                $parts = explode(' ', $text);
                $phoneInput = trim($parts[1] ?? '');

                if (empty($phoneInput)) {
                    $this->sendTelegramReply($chatId, "⚠️ Please provide your phone number. Example:\n`/link 254712345678`");
                    return response()->json(['status' => 'missing_phone']);
                }

                $user = User::where('phone', 'LIKE', '%' . substr($phoneInput, -9))->first();
                if ($user) {
                    $user->update(['telegram_chat_id' => $chatId]);
                    $this->sendTelegramReply($chatId, "✅ *Success!* Your Telegram account is now linked to *{$user->name}*.\n\nHow can I help you today?");
                    return response()->json(['status' => 'linked']);
                } else {
                    $this->sendTelegramReply($chatId, "❌ Phone number not found in UniCrib records. Please check your number and try again.");
                    return response()->json(['status' => 'link_failed']);
                }
            }

            // 3. Handle Unlinked Users Sending Regular Text
            if (!$user) {
                $this->sendTelegramReply(
                    $chatId, 
                    "⚠️ *Account Not Linked*\n\nPlease link your UniCrib account first by replying with:\n`/link 254712345678`"
                );
                return response()->json(['status' => 'user_not_found']);
            }

            // 4. Send immediate processing feedback
            $this->sendTelegramReply($chatId, "⏳ *Processing your request...*");

            // 5. Handle Photo / Image Uploads
            if (!empty($photo)) {
                $largestPhoto = end($photo);
                $fileId = $largestPhoto['file_id'] ?? null;

                $aiResponse = app(TelegramImageInterpretationController::class)
                    ->interpretHouseImage($user, $fileId, $text);

                $this->sendTelegramReply($chatId, $aiResponse);
                return response()->json(['status' => 'success']);
            }

            // 6. Handle Text Commands / AI Prompts
            if (empty($text)) {
                $this->sendTelegramReply($chatId, "⚠️ Received an empty message.");
                return response()->json(['status' => 'empty_text']);
            }

            $aiResponse = $this->askMetaModelWithToolGateway($text, $user, $chatId);
            $this->sendTelegramReply($chatId, $aiResponse);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Telegram Webhook Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            if (isset($chatId)) {
                $this->sendTelegramReply($chatId, "⚠️ An error occurred while processing your request. Please try again later.");
            }

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function askMetaModelWithToolGateway($prompt, $user, $chatId)
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');
        $endpoint = "{$baseUrl}/chat/completions";

        $cacheKey = "telegram_chat_" . $chatId;
        $isLandlord = $user->category === 'landlord';

        $systemPrompt = $isLandlord
            ? "You are Unicrib Landlord Assistant, a professional AI manager helping property owner {$user->name} manage their listings, monitor student bookings, and check verification statuses in Kenya."
            : "You are Unicrib AI, a helpful multimodal assistant for student housing, storage, and transport dispatch in Kenya. User is {$user->name}.";

        $history = Cache::get($cacheKey, [
            ['role' => 'system', 'content' => $systemPrompt]
        ]);

        $history[] = ['role' => 'user', 'content' => $prompt];
        $tools = $this->getToolSchemas($isLandlord);

        $response = Http::withToken($apiKey)->post($endpoint, [
            'model' => $model,
            'messages' => $history,
            'tools' => $tools,
            'tool_choice' => 'auto'
        ]);

        if ($response->failed()) {
            Log::error('Telegram AI Model API HTTP Error:', ['status' => $response->status(), 'body' => $response->body()]);
            return "I'm having trouble connecting to UniCrib AI right now. Please try again!";
        }

        $resData = $response->json();
        $messageChoice = $resData['choices'][0]['message'] ?? null;

        if (!$messageChoice) {
            return "I'm having trouble understanding the AI response right now.";
        }

        // Handle Tool Calls
        if (isset($messageChoice['tool_calls'])) {
            $toolCall = $messageChoice['tool_calls'][0];
            $functionName = $toolCall['function']['name'];
            $arguments = json_decode($toolCall['function']['arguments'], true) ?? [];

            $toolResult = $this->dispatchToControllerGateway($functionName, $arguments, $user);

            $history[] = $messageChoice;
            $history[] = [
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'content' => (string) $toolResult
            ];

            $followUpResponse = Http::withToken($apiKey)->post($endpoint, [
                'model' => $model,
                'messages' => $history,
                'tools' => $tools,
                'tool_choice' => 'auto'
            ]);

            $followUpData = $followUpResponse->json();
            $finalMessageChoice = $followUpData['choices'][0]['message'] ?? null;
            $assistantReply = $finalMessageChoice['content'] ?? (string) $toolResult;

            $history[] = ['role' => 'assistant', 'content' => $assistantReply];
            Cache::put($cacheKey, $history, now()->addHours(2));

            return $assistantReply;
        }

        $assistantReply = $messageChoice['content'] ?? "Done!";
        $history[] = ['role' => 'assistant', 'content' => $assistantReply];
        Cache::put($cacheKey, $history, now()->addHours(2));

        return $assistantReply;
    }

    private function getToolSchemas(bool $isLandlord): array
    {
        if ($isLandlord) {
            return [
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_landlord_properties",
                        "description" => "View all properties owned by this landlord along with verification and availability status.",
                        "parameters" => ["type" => "object", "properties" => []]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_landlord_bookings",
                        "description" => "View recent student bookings, payment statuses, and tenant details.",
                        "parameters" => ["type" => "object", "properties" => []]
                    ]
                ]
            ];
        }

        return [
            [
                "type" => "function",
                "function" => [
                    "name" => "search_housing",
                    "description" => "Search available student housing, hostels, and apartments by location/ward name, maximum price budget, and property type.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "location" => ["type" => "string", "description" => "Location name, area, or university ward e.g. Kutus, Main Campus"],
                            "max_price" => ["type" => "integer", "description" => "Maximum budget in Kenyan Shillings (KSh)"],
                            "property_type" => ["type" => "string", "description" => "Type of property e.g. hostel, apartment, single room, bedsitter"]
                        ],
                        "required" => ["location"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "check_transport_status",
                    "description" => "Check transport slot assignment, ride status, and assigned driver details using a route name or booking identifier.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "identifier" => ["type" => "string", "description" => "Booking ID, route name, or tracking identifier"]
                        ],
                        "required" => ["identifier"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "manage_storage",
                    "description" => "Check active storage housing bookings status or reserve a new storage unit.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "action" => ["type" => "string", "description" => "Action: 'check' or 'reserve'", "enum" => ["check", "reserve"]],
                            "unit_size" => ["type" => "string", "description" => "Unit size: small, medium, large, standard"]
                        ],
                        "required" => ["action"]
                    ]
                ]
            ]
        ];
    }

    private function dispatchToControllerGateway($name, $args, $user)
    {
        $request = new Request($args);

        switch ($name) {
            case 'search_housing':
            case 'search_properties':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->searchPropertiesApi($request, $user);

            case 'check_transport_status':
            case 'fetch_transport_status':
                return app(\App\Http\Controllers\TransportController::class)->apiTransportStatus($request);

            case 'manage_storage':
            case 'check_storage_status':
                return app(\App\Http\Controllers\StorageHousingController::class)->apiManageStorage($request, $user);

            case 'get_landlord_properties':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->landlordPropertiesApi($user);

            case 'get_landlord_bookings':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->landlordBookingsApi($user);

            default:
                return "Gateway action completed successfully.";
        }
    }

    private function sendTelegramReply($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            Log::info("Telegram simulated reply to chat {$chatId}: {$text}");
            return;
        }

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown'
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram API Exception: ' . $e->getMessage());
        }
    }
}