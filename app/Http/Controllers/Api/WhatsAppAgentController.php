<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\Api\WhatsAppImageInterpretationController;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\StorageBooking;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
     * Handle incoming WhatsApp text and route through Meta Model API Agent
     */
    public function handleIncomingMessage(Request $request)
    {
        // Read directly from the raw content buffer to bypass array depth limits
        $rawContent = $request->getContent();
        $input = json_decode($rawContent, true) ?? [];

        // Selective logging (log only the message value rather than the 10-level wrapper)
        $value = $input['entry'][0]['changes'][0]['value'] ?? null;
        //Log::info('WhatsApp Incoming Payload Value:', $value ?? []);
        // Instead of passing array $input to Log::info:
        Log::info('WhatsApp Raw Payload: ' . $request->getContent());

        $messageData = $value['messages'][0] ?? null;
        if (!$messageData) {
            return response()->json(['status' => 'ignored']);
        }

        $senderPhone = $messageData['from'] ?? null;
        $messageType = $messageData['type'] ?? 'text';

        // Authenticate user by phone number
        $user = User::where('phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

        if (!$user) {
            $this->sendWhatsAppReply($senderPhone, "Welcome to Unicrib! We couldn't find an account linked to this WhatsApp number. Please register on our web app first.");
            return response()->json(['status' => 'user_not_found']);
        }

        if ($messageType === 'image') {
            $imageMediaId = $messageData['image']['id'] ?? null;
            $caption = $messageData['image']['caption'] ?? '';

            $aiResponse = app(WhatsAppImageInterpretationController::class)
                ->interpretHouseImage($request, $user, $imageMediaId, null, $caption);

            $this->sendInteractiveButtons($senderPhone, $aiResponse, [
                'book_house' => 'Book Now',
                'view_more' => 'View Details'
            ]);
            return response()->json(['status' => 'success']);
        }

        if ($messageType === 'interactive') {
            $buttonReplyId = $messageData['interactive']['button_reply']['id'] ?? '';
            $aiResponse = $this->handleInteractiveButtonReply($buttonReplyId, $user, $senderPhone);
            $this->sendWhatsAppReply($senderPhone, $aiResponse);
            return response()->json(['status' => 'success']);
        }

        $userMessage = $messageData['text']['body'] ?? '';

        // Process through Meta AI Agent with Tool Gateway & Memory
        $aiResponse = $this->askMetaModelWithToolGateway($userMessage, $user, $senderPhone);

        // Send reply back via WhatsApp Cloud API
        $this->sendWhatsAppReply($senderPhone, $aiResponse);

        return response()->json(['status' => 'success']);
    }

    private function askMetaModelWithToolGateway($prompt, $user, $senderPhone)
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');
        $endpoint = "{$baseUrl}/chat/completions";

        $cacheKey = "whatsapp_chat_" . $senderPhone;

        // Set dynamic system prompt & tools based on user category (Student vs Landlord)
        $isLandlord = $user->category === 'landlord';

        $systemPrompt = $isLandlord
            ? "You are Unicrib Landlord Assistant, a professional AI manager helping property owner {$user->name} manage their listings, monitor student bookings, and check verification statuses in Kenya."
            : "You are Unicrib AI, a helpful multimodal assistant for student housing, storage, and transport dispatch in Kenya. User is {$user->name}.";

        $history = Cache::get($cacheKey, [
            ['role' => 'system', 'content' => $systemPrompt]
        ]);

        $history[] = ['role' => 'user', 'content' => $prompt];

        // Define Tool Schemas for OpenAI-compatible Model API
        $tools = $this->getToolSchemas($isLandlord);

        $response = Http::withToken($apiKey)->post($endpoint, [
            'model' => $model,
            'messages' => $history,
            'tools' => $tools,
            'tool_choice' => 'auto'
        ]);

        if ($response->failed()) {
            Log::error('AI Model API HTTP Error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return "I'm having trouble connecting to Unicrib AI right now. Please try again!";
        }

        $resData = $response->json();
        $messageChoice = $resData['choices'][0]['message'] ?? null;

        if (!$messageChoice) {
            Log::error('AI Model API Malformed Response:', ['response' => $resData]);
            return "I'm having trouble understanding the AI response right now.";
        }

        // Handle Tool Calling Execution
        if (isset($messageChoice['tool_calls'])) {
            $toolCall = $messageChoice['tool_calls'][0];
            $functionName = $toolCall['function']['name'];
            $arguments = json_decode($toolCall['function']['arguments'], true) ?? [];

            // Execute via Controller Gateway
            $toolResult = $this->dispatchToControllerGateway($functionName, $arguments, $user);

            $history[] = $messageChoice;
            $history[] = [
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'content' => (string) $toolResult
            ];

            // Send the tool execution result back to the model to generate a final natural language response
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

    /**
     * Return OpenAI-compatible tool schemas for housing, transport, and storage management.
     */
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
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "add_landlord_property",
                        "description" => "Add a new student property listing on behalf of the landlord.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "title" => ["type" => "string", "description" => "Property title e.g. Executive Hostel Room"],
                                "description" => ["type" => "string", "description" => "Detailed property description"],
                                "price" => ["type" => "integer", "description" => "Monthly rent price in KSh"],
                                "area" => ["type" => "string", "description" => "Area or ward name e.g. Kutus"],
                                "address" => ["type" => "string", "description" => "Full physical address"],
                                "bedrooms" => ["type" => "integer", "description" => "Number of bedrooms"],
                                "bathrooms" => ["type" => "integer", "description" => "Number of bathrooms"]
                            ],
                            "required" => ["title", "price", "area", "address"]
                        ]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "update_property_availability",
                        "description" => "Update a property's availability status (available or unavailable).",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "property_id" => ["type" => "integer", "description" => "Property ID"],
                                "is_available" => ["type" => "boolean", "description" => "True for available, false for unavailable"]
                            ],
                            "required" => ["property_id", "is_available"]
                        ]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "update_booking_status",
                        "description" => "Approve or reject a student's booking request for a property.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "booking_id" => ["type" => "integer", "description" => "Booking ID"],
                                "status" => ["type" => "string", "description" => "Action status: 'approved' or 'rejected'", "enum" => ["approved", "rejected"]]
                            ],
                            "required" => ["booking_id", "status"]
                        ]
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
                            "location" => [
                                "type" => "string",
                                "description" => "Location name, area, or university ward e.g. Kutus, Main Campus, Kirinyaga"
                            ],
                            "max_price" => [
                                "type" => "integer",
                                "description" => "Maximum budget in Kenyan Shillings (KSh)"
                            ],
                            "property_type" => [
                                "type" => "string",
                                "description" => "Type of property e.g. hostel, apartment, single room, bedsitter"
                            ]
                        ],
                        "required" => ["location"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "check_transport_status",
                    "description" => "Check transport slot assignment, ride status, and assigned driver details using a route name or booking/tracking identifier.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "identifier" => [
                                "type" => "string",
                                "description" => "Booking ID, route name, or tracking identifier for the transport request"
                            ]
                        ],
                        "required" => ["identifier"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "manage_storage",
                    "description" => "Check active storage housing bookings status or reserve a new storage unit specifying action ('check' or 'reserve') and unit size.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "action" => [
                                "type" => "string",
                                "description" => "Action to perform: either 'check' status or 'reserve' storage unit",
                                "enum" => ["check", "reserve"]
                            ],
                            "unit_size" => [
                                "type" => "string",
                                "description" => "Size of the storage unit e.g. small, medium, large, standard"
                            ]
                        ],
                        "required" => ["action"]
                    ]
                ]
            ]
        ];
    }

    /**
     * Gateway Dispatcher: Dynamically dispatch and execute corresponding local Laravel controller methods.
     */
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

            case 'add_landlord_property':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->addLandlordPropertyApi($request, $user);

            case 'update_property_availability':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->updatePropertyAvailabilityApi($request, $user);

            case 'update_booking_status':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->updateBookingStatusApi($request, $user);

            default:
                return "Gateway action completed successfully.";
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
            $response = Http::withToken($token)->post("{$baseUrl}/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $recipientPhone,
                'type' => 'text',
                'text' => ['body' => $text]
            ]);

            if ($response->failed()) {
                Log::error('WhatsApp Cloud API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'recipient' => $recipientPhone
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Cloud API Exception: ' . $e->getMessage(), [
                'recipient' => $recipientPhone,
                'exception' => $e
            ]);
        }
    }

    private function sendInteractiveButtons($recipientPhone, $bodyText, array $buttons)
    {
        $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        $baseUrl = rtrim(env('WHATSAPP_API_BASE_URL', 'https://graph.facebook.com/v17.0'), '/');

        $formattedButtons = [];
        foreach ($buttons as $id => $title) {
            $formattedButtons[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => (string) $id,
                    'title' => substr((string) $title, 0, 20)
                ]
            ];
        }

        if (!$token || !$phoneId) {
            Log::info("WhatsApp simulated interactive reply to {$recipientPhone}: {$bodyText} [Buttons: " . json_encode($buttons) . "]");
            return;
        }

        try {
            $response = Http::withToken($token)->post("{$baseUrl}/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $recipientPhone,
                'type' => 'interactive',
                'interactive' => [
                    'type' => 'button',
                    'body' => [
                        'text' => $bodyText
                    ],
                    'action' => [
                        'buttons' => $formattedButtons
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('WhatsApp Interactive API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'recipient' => $recipientPhone
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Interactive API Exception: ' . $e->getMessage());
        }
    }

    private function handleInteractiveButtonReply($buttonId, $user, $senderPhone)
    {
        if (str_starts_with($buttonId, 'book_house') || $buttonId === 'book_prop') {
            return "🏡 Great choice! To complete your booking and secure this house, please visit the Unicrib web app under Student Dashboard or make your payment verification.";
        }
        if (str_starts_with($buttonId, 'approve_book')) {
            $bookingId = str_replace('approve_book_', '', $buttonId);
            if (is_numeric($bookingId)) {
                $booking = Booking::where('id', $bookingId)->where('landlord_id', $user->id)->first();
                if ($booking) {
                    $booking->update(['status' => 'approved']);
                    return "✅ Booking #{$booking->id} has been successfully approved!";
                }
            }
            return "Booking approved successfully via WhatsApp action.";
        }
        if (str_starts_with($buttonId, 'reject_book')) {
            $bookingId = str_replace('reject_book_', '', $buttonId);
            if (is_numeric($bookingId)) {
                $booking = Booking::where('id', $bookingId)->where('landlord_id', $user->id)->first();
                if ($booking) {
                    $booking->update(['status' => 'rejected']);
                    return "❌ Booking #{$booking->id} has been rejected.";
                }
            }
            return "Booking rejected.";
        }

        return "Action '{$buttonId}' processed successfully by Unicrib AI.";
    }

    /**
     * Simulate incoming WhatsApp message for local testing without Meta Cloud API setup
     */
    public function simulateMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $senderPhone = $request->input('phone');
        $userMessage = $request->input('message');

        $user = User::where('phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

        if (!$user) {
            return response()->json([
                'status' => 'user_not_found',
                'reply' => "Welcome to Unicrib! We couldn't find an account linked to this WhatsApp number. Please register on our web app first."
            ], 404);
        }

        $aiResponse = $this->askMetaModelWithToolGateway($userMessage, $user, $senderPhone);
        $this->sendWhatsAppReply($senderPhone, $aiResponse);

        return response()->json([
            'status' => 'success',
            'sender' => $senderPhone,
            'user' => $user->name,
            'reply' => $aiResponse
        ]);
    }
}