<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TransportController;
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
        $verifyToken = "unicrib_meta_token_2026";
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
        $input = $request->all();
        Log::info('WhatsApp Webhook Payload:', $input);

        $messageData = $input['entry'][0]['changes'][0]['value']['messages'][0] ?? null;
        if (!$messageData) {
            return response()->json(['status' => 'ignored']);
        }

        $senderPhone = $messageData['from']; // e.g., 254712345678
        $userMessage = $messageData['text']['body'] ?? '';

        // Authenticate user by phone number
        $user = User::where('phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

        if (!$user) {
            $this->sendWhatsAppReply($senderPhone, "Welcome to Unicrib! We couldn't find an account linked to this WhatsApp number. Please register on our web app first.");
            return response()->json(['status' => 'user_not_found']);
        }

        // Process through Meta AI Agent with Tool Gateway & Memory
        $aiResponse = $this->askMetaModelWithToolGateway($userMessage, $user, $senderPhone);

        // Send reply back via WhatsApp Cloud API
        $this->sendWhatsAppReply($senderPhone, $aiResponse);

        return response()->json(['status' => 'success']);
    }

    private function askMetaModelWithToolGateway($prompt, $user, $senderPhone)
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = env('META_API_BASE_URL', 'https://api.meta.ai/v1');
        $model = env('META_MODEL_NAME', 'muse-spark');

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

        // Define Tool Schemas for Meta's Model API
        $tools = $isLandlord ? [
            [
                "type" => "function",
                "function" => [
                    "name" => "get_landlord_properties",
                    "description" => "View all properties owned by this landlord along with verification status.",
                    "parameters" => ["type" => "object", "properties" => []]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "get_landlord_bookings",
                    "description" => "View recent student bookings and payment statuses.",
                    "parameters" => ["type" => "object", "properties" => []]
                ]
            ]
        ] : [
            [
                "type" => "function",
                "function" => [
                    "name" => "search_properties",
                    "description" => "Search available student properties by area and max price.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "area" => ["type" => "string", "description" => "Location name e.g. Kutus"],
                            "max_price" => ["type" => "integer", "description" => "Max budget in KSh"]
                        ],
                        "required" => ["area"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "fetch_transport_status",
                    "description" => "Fetch transport slot and assigned driver details for a booking.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "booking_id" => ["type" => "integer", "description" => "Active booking ID"]
                        ],
                        "required" => ["booking_id"]
                    ]
                ]
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "check_storage_status",
                    "description" => "Check active storage housing bookings, duration, and paid deposit.",
                    "parameters" => ["type" => "object", "properties" => []]
                ]
            ]
        ];

        $response = Http::withToken($apiKey)->post("{$baseUrl}/chat/completions", [
            'model' => $model,
            'messages' => $history,
            'tools' => $tools,
            'tool_choice' => 'auto'
        ]);

        $resData = $response->json();
        $messageChoice = $resData['choices'][0]['message'] ?? null;

        if (!$messageChoice) {
            return "I'm having trouble connecting to Unicrib AI right now. Please try again!";
        }

        // Handle Tool Calling Execution
        if (isset($messageChoice['tool_calls'])) {
            $toolCall = $messageChoice['tool_calls'][0];
            $functionName = $toolCall['function']['name'];
            $arguments = json_decode($toolCall['function']['arguments'], true);

            // Execute via Controller Gateway
            $toolResult = $this->dispatchToControllerGateway($functionName, $arguments, $user);

            $history[] = $messageChoice;
            $history[] = [
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'content' => $toolResult
            ];

            Cache::put($cacheKey, $history, now()->addHours(2));

            // Re-prompt model to formulate a natural response from the tool output
            return $this->askMetaModelWithToolGateway("Summarize this data for the user: " . $toolResult, $user, $senderPhone);
        }

        $assistantReply = $messageChoice['content'] ?? "Done!";
        $history[] = ['role' => 'assistant', 'content' => $assistantReply];
        Cache::put($cacheKey, $history, now()->addHours(2));

        return $assistantReply;
    }

    /**
     * Gateway Dispatcher: Securely routes AI tool calls into Controller logic
     */
    private function dispatchToControllerGateway($name, $args, $user)
    {
        switch ($name) {
            case 'search_properties':
                $properties = Property::where('area', 'LIKE', '%' . ($args['area'] ?? '') . '%')
                    ->when(isset($args['max_price']), fn($q) => $q->where('price', '<=', $args['max_price']))
                    ->take(3)->get();
                if ($properties->isEmpty()) return "No properties found matching your criteria.";
                return $properties->map(fn($p) => "• {$p->title} - KSh {$p->price}/mo at {$p->address}")->implode("\n");

            case 'fetch_transport_status':
                $transport = Transport::with('driver')->where('booking_id', $args['booking_id'])->latest()->first();
                if (!$transport) return "No transport slot found for booking #{$args['booking_id']}.";
                $driverInfo = $transport->driver ? "Driver: {$transport->driver->name} ({$transport->driver->vehicle_model}, Plate: {$transport->driver->vehicle_plate})" : "Driver pending assignment.";
                return "Transport Status: " . ucfirst($transport->status) . "\n{$driverInfo}";

            case 'check_storage_status':
                $storage = StorageBooking::where('user_id', $user->id)->latest()->first();
                if (!$storage) return "You have no active storage bookings.";
                return "Storage #{$storage->id}: Status is " . ucfirst($storage->status) . ", Duration: {$storage->months} months, Paid: KSh {$storage->paid_amount}/{$storage->amount}.";

            case 'get_landlord_properties':
                $properties = Property::where('landlord_id', $user->id)->get();
                if ($properties->isEmpty()) return "You have no registered properties.";
                return "Your Properties:\n" . $properties->map(fn($p) => "• [ID: {$p->id}] {$p->title} - KSh {$p->price} (" . ($p->is_verified ? 'Verified ✅' : 'Pending ⏳') . ")")->implode("\n");

            case 'get_landlord_bookings':
                $bookings = Booking::with(['property', 'student'])->where('landlord_id', $user->id)->latest()->take(5)->get();
                if ($bookings->isEmpty()) return "No student bookings found.";
                return "Recent Bookings:\n" . $bookings->map(fn($b) => "• Booking #{$b->id}: {$b->student->name} for {$b->property->title} | Status: " . ucfirst($b->status))->implode("\n");

            default:
                return "Gateway action completed successfully.";
        }
    }

    private function sendWhatsAppReply($recipientPhone, $text)
    {
        $token = env('WHATSAPP_TOKEN');
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');

        if (!$token || !$phoneId) {
            Log::info("WhatsApp simulated reply to {$recipientPhone}: {$text}");
            return;
        }

        Http::withToken($token)->post("https://graph.facebook.com/v20.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhone,
            'type' => 'text',
            'text' => ['body' => $text]
        ]);
    }
}