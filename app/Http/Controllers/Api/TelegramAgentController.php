<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TelegramAgentController extends Controller
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle incoming Telegram webhook updates
     */
    public function handleWebhook(Request $request)
    {
        try {
            $input = $request->all();
            Log::info('Telegram Raw Payload: ' . json_encode($input));

            // 1. Handle Inline Button Taps (Callback Queries)
            if (isset($input['callback_query'])) {
                return $this->handleCallbackQuery($input['callback_query']);
            }

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

            // 2. Handle Telegram /start Command with Interactive Welcome Options
            if ($text === '/start') {
                if ($user) {
                    $this->telegram->sendInlineButtons(
                        $chatId, 
                        "👋 <b>Welcome back to UniCrib, {$user->name}!</b>\n\nYour account is connected. What would you like to manage today?",
                        [
                            [
                                ['text' => '🏠 Search Housing', 'callback_data' => 'menu_housing'],
                                ['text' => '🚚 Transport Status', 'callback_data' => 'menu_transport']
                            ],
                            [
                                ['text' => '📦 Storage Units', 'callback_data' => 'menu_storage']
                            ]
                        ]
                    );
                    return response()->json(['status' => 'welcome_existing_user']);
                } else {
                    $this->telegram->sendInlineButtons(
                        $chatId, 
                        "👋 <b>Welcome to UniCrib AI!</b>\n\nYour Telegram account is not linked yet. To connect your account, please reply with your registered phone number or tap below:",
                        [
                            [['text' => '🔗 How to link account', 'callback_data' => 'help_link']]
                        ]
                    );
                    return response()->json(['status' => 'welcome_new_user']);
                }
            }

            // 3. Handle /link <phone> Command
            if (!$user && str_starts_with($text, '/link')) {
                $parts = explode(' ', $text);
                $phoneInput = trim($parts[1] ?? '');

                if (empty($phoneInput)) {
                    $this->telegram->sendInlineButtons($chatId, "⚠️ Please provide your phone number. Example:\n<code>/link 254712345678</code>", []);
                    return response()->json(['status' => 'missing_phone']);
                }

                $user = User::where('phone', 'LIKE', '%' . substr($phoneInput, -9))->first();
                if ($user) {
                    $user->update(['telegram_chat_id' => $chatId]);
                    $this->telegram->sendInlineButtons(
                        $chatId, 
                        "✅ <b>Success!</b> Your Telegram account is now linked to <b>{$user->name}</b>.\n\nHow can I help you today?",
                        [
                            [['text' => '🚀 Open Dashboard Menu', 'callback_data' => 'menu_housing']]
                        ]
                    );
                    return response()->json(['status' => 'linked']);
                } else {
                    $this->telegram->sendInlineButtons($chatId, "❌ Phone number not found in UniCrib records. Please check your number and try again.", []);
                    return response()->json(['status' => 'link_failed']);
                }
            }

            // 4. Handle Unlinked Users Sending Regular Text
            if (!$user) {
                $this->telegram->sendInlineButtons(
                    $chatId, 
                    "⚠️ <b>Account Not Linked</b>\n\nPlease link your UniCrib account first by replying with:\n<code>/link 254712345678</code>",
                    []
                );
                return response()->json(['status' => 'user_not_found']);
            }

            // 5. Send immediate processing feedback
            $this->telegram->sendInlineButtons($chatId, "⏳ <i>Processing your request...</i>", []);

            // 6. Handle Photo / Image Uploads
            if (!empty($photo)) {
                $largestPhoto = end($photo);
                $fileId = $largestPhoto['file_id'] ?? null;

                $aiResponse = app(TelegramImageInterpretationController::class)
                    ->interpretHouseImage($user, $fileId, $text);

                $this->telegram->sendInlineButtons($chatId, $aiResponse, []);
                return response()->json(['status' => 'success']);
            }

            // 7. Handle Text Commands / AI Prompts
            if (empty($text)) {
                $this->telegram->sendInlineButtons($chatId, "⚠️ Received an empty message.", []);
                return response()->json(['status' => 'empty_text']);
            }

            $aiResponse = $this->askMetaModelWithToolGateway($text, $user, $chatId);
            $this->telegram->sendInlineButtons($chatId, $aiResponse, []);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Telegram Webhook Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            if (isset($chatId)) {
                $this->telegram->sendInlineButtons($chatId, "⚠️ An error occurred while processing your request. Please try again later.", []);
            }

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Inline Keyboard Button Taps
     */
    protected function handleCallbackQuery(array $callbackQuery)
    {
        $callbackId = $callbackQuery['id'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'];

        Log::info("Telegram Button Tapped: {$data} from Chat ID: {$chatId}");

        $user = User::where('telegram_chat_id', $chatId)->first();

        // Menu quick navigation
        if ($data === 'menu_housing') {
            $this->telegram->answerCallbackQuery($callbackId);
            $this->telegram->sendInlineButtons($chatId, "🏠 *Housing Search*\n\nTell me the ward or location you want to live in (e.g., *'Find hostels in Kutus under 10k'*)!", []);
            return response()->json(['status' => 'success']);
        }

        if ($data === 'menu_transport') {
            $this->telegram->answerCallbackQuery($callbackId);
            $this->telegram->sendInlineButtons($chatId, "🚚 *Transport Dispatch*\n\nYour transport requests and active schedules can be checked by typing *'Check my transport status'*.", []);
            return response()->json(['status' => 'success']);
        }

        if ($data === 'menu_storage') {
            $this->telegram->answerCallbackQuery($callbackId);
            $this->telegram->sendInlineButtons($chatId, "📦 *Store Housing*\n\nType *'Check my storage status'* or *'Reserve a medium storage unit'* to get started.", []);
            return response()->json(['status' => 'success']);
        }

        if ($data === 'help_link') {
            $this->telegram->answerCallbackQuery($callbackId);
            $this->telegram->sendInlineButtons($chatId, "To link your account, type:\n<code>/link YOUR_PHONE_NUMBER</code>\n\nExample: <code>/link 254712345678</code>", []);
            return response()->json(['status' => 'success']);
        }

        // Handle Landlord / Student Booking Approvals or Actions
        if (str_starts_with($data, 'approve_cancel_')) {
            $bookingId = str_replace('approve_cancel_', '', $data);
            $booking = Booking::find($bookingId);

            if ($booking) {
                $booking->update(['status' => 'cancelled']);
                $this->telegram->answerCallbackQuery($callbackId, 'Cancellation approved successfully!', showAlert: true);
                $this->telegram->sendInlineButtons($chatId, "✅ Booking #{$bookingId} has been successfully cancelled.", []);
            } else {
                $this->telegram->answerCallbackQuery($callbackId, 'Booking not found.', showAlert: true);
            }
            return response()->json(['status' => 'success']);
        }

        // Default callback acknowledgment
        $this->telegram->answerCallbackQuery($callbackId);
        return response()->json(['status' => 'success']);
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
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "add_landlord_property",
                        "description" => "Add a new student property listing with full details including title, description, price, area, address, property type, bedrooms, and bathrooms.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "title" => ["type" => "string", "description" => "Property title e.g. Greenview Student Hostel"],
                                "description" => ["type" => "string", "description" => "Detailed description of amenities and rules"],
                                "price" => ["type" => "number", "description" => "Monthly rent in Kenyan Shillings (KSh)"],
                                "area" => ["type" => "string", "description" => "Area/ward: mjini, ngomongo, diaspora, or kutus", "enum" => ["mjini", "ngomongo", "diaspora", "kutus"]],
                                "address" => ["type" => "string", "description" => "Physical address or landmark location"],
                                "property_type" => ["type" => "string", "description" => "Type: apartment, house, room, studio, or bedsitter", "enum" => ["apartment", "house", "room", "studio", "bedsitter"]],
                                "bedrooms" => ["type" => "integer", "description" => "Number of bedrooms"],
                                "bathrooms" => ["type" => "integer", "description" => "Number of bathrooms"],
                                "capacity" => ["type" => "integer", "description" => "Total tenant capacity"]
                            ],
                            "required" => ["title", "price", "area", "address", "property_type", "bedrooms", "bathrooms", "capacity"]
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
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "get_payment_link",
                    "description" => "Get a secure browser payment link for a pending student booking or storage reservation.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "booking_id" => ["type" => "integer", "description" => "The active booking ID"]
                        ],
                        "required" => ["booking_id"]
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

            case 'add_landlord_property':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->addLandlordPropertyApi($request, $user);

            case 'get_landlord_bookings':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->landlordBookingsApi($user);

            case 'get_payment_link':
                $bookingId = $args['booking_id'] ?? null;
                $booking = Booking::where('id', $bookingId)->where('student_id', $user->id)->first();
                if (!$booking) return "Booking not found.";
                
                $paymentUrl = route('bookings.show', $booking->id); // Or your dedicated payment route
                return "Please complete your payment securely in your browser using this link: {$paymentUrl}";

            default:
                return "Gateway action completed successfully.";
        }
    }
}