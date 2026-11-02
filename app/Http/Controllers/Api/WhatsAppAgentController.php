<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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
     * Handle incoming WhatsApp messages
     */
    public function handleIncomingMessage(Request $request)
    {
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

        // Authenticate user by phone number
        $user = User::where('phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

        if (!$user) {
            $this->sendWhatsAppReply($senderPhone, "Welcome to Unicrib! We couldn't find an account linked to this WhatsApp number. Please register on our web app first.");
            return response()->json(['status' => 'user_not_found']);
        }

        // 1. Handle Photo / Image Uploads
        if ($messageType === 'image') {
            $imageMediaId = $messageData['image']['id'] ?? null;
            $caption = trim($messageData['image']['caption'] ?? '');

            if ($user->category === 'landlord') {
                $propertyId = trim(filter_var($caption, FILTER_SANITIZE_NUMBER_INT));

                if (empty($propertyId) || !is_numeric($propertyId)) {
                    $this->sendWhatsAppReply($senderPhone, "⚠️ *Missing Property ID!*\n\nPlease specify the Property ID in your photo caption when uploading (e.g., *'Property 2'* or just *'2'*).");
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

            // Student Image Interpretation
            $aiResponse = app(WhatsAppImageInterpretationController::class)
                ->interpretHouseImage($request, $user, $imageMediaId, null, $caption);

            $this->sendWhatsAppReply($senderPhone, $aiResponse);
            return response()->json(['status' => 'success']);
        }

        // 2. Handle Interactive Button Replies
        if ($messageType === 'interactive') {
            $buttonReplyId = $messageData['interactive']['button_reply']['id'] ?? '';
            $aiResponse = $this->handleInteractiveButtonReply($buttonReplyId, $user, $senderPhone);
            $this->sendWhatsAppReply($senderPhone, $aiResponse);
            return response()->json(['status' => 'success']);
        }

        // 3. Process Text Commands and AI Prompts
        $userMessage = $messageData['text']['body'] ?? '';
        if (empty($userMessage)) {
            return response()->json(['status' => 'empty_text']);
        }

        // Inside WhatsAppAgentController.php (where text messages are processed)

        $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $userMessage, $senderPhone, 'whatsapp');

        // Send the response back to WhatsApp using your sendWhatsAppReply method
        $this->sendWhatsAppReply($senderPhone, $aiResponse);

        return response()->json(['status' => 'success']);
    }

    private function askMetaModelWithToolGateway($prompt, User $user, $senderPhone)
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');
        $endpoint = "{$baseUrl}/chat/completions";

        $cacheKey = "whatsapp_chat_" . $senderPhone;
        $category = $user->category;

        $systemPrompt = match ($category) {
            'landlord' => "You are Unicrib Landlord Assistant, a professional assistant helping property owner {$user->name} manage listings and monitor bookings concisely in Kenya.",
            'driver', 'transport' => "You are Unicrib Driver Assistant, helping transport provider {$user->name} manage assigned trips concisely.",
            default => "You are Unicrib AI, a helpful and concise assistant for student housing, storage, and transport dispatch in Kenya. User is {$user->name}. Keep answers brief, clean, and conversational without robotic filler.",
        };

        $history = Cache::get($cacheKey, [
            ['role' => 'system', 'content' => $systemPrompt]
        ]);

        $history[] = ['role' => 'user', 'content' => $prompt];
        $tools = $this->getToolSchemas($category);

        $response = Http::withToken($apiKey)->post($endpoint, [
            'model' => $model,
            'messages' => $history,
            'tools' => $tools,
            'tool_choice' => 'auto'
        ]);

        if ($response->failed()) {
            Log::error('AI Model API HTTP Error:', ['status' => $response->status(), 'body' => $response->body()]);
            return "I'm having trouble connecting to Unicrib AI right now. Please try again!";
        }

        $resData = $response->json();
        $messageChoice = $resData['choices'][0]['message'] ?? null;

        if (!$messageChoice) {
            return "I couldn't process that response. Please try again.";
        }

        if (isset($messageChoice['tool_calls'])) {
            $toolCall = $messageChoice['tool_calls'][0];
            $functionName = $toolCall['function']['name'];
            $arguments = json_decode($toolCall['function']['arguments'], true) ?? [];

            $toolResult = $this->dispatchToControllerGateway($functionName, $arguments, $user, $senderPhone);

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

    /**
     * Role-Based Tool Schemas
     */
    private function getToolSchemas(string $category): array
    {
        if ($category === 'landlord') {
            return [
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_landlord_properties",
                        "description" => "View all properties owned by this landlord along with verification and availability status.",
                        "parameters" => ["type" => "object", "properties" => (object) []]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_landlord_bookings",
                        "description" => "View recent student bookings, payment statuses, and tenant details.",
                        "parameters" => ["type" => "object", "properties" => (object) []]
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
                                "title" => ["type" => "string", "description" => "Property title e.g. Executive Hostel Room"],
                                "description" => ["type" => "string", "description" => "Detailed property description"],
                                "price" => ["type" => "number", "description" => "Monthly rent price in KSh"],
                                "area" => ["type" => "string", "description" => "Area/ward: mjini, ngomongo, diaspora, or kutus", "enum" => ["mjini", "ngomongo", "diaspora", "kutus"]],
                                "address" => ["type" => "string", "description" => "Full physical address"],
                                "property_type" => ["type" => "string", "description" => "Type: apartment, house, room, studio, or bedsitter", "enum" => ["apartment", "house", "room", "studio", "bedsitter"]],
                                "bedrooms" => ["type" => "integer", "description" => "Number of bedrooms"],
                                "bathrooms" => ["type" => "integer", "description" => "Number of bathrooms"],
                                "capacity" => ["type" => "integer", "description" => "Total tenant capacity"]
                            ],
                            "required" => ["title", "price", "area", "address", "property_type", "bedrooms", "bathrooms", "capacity"]
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
                                "property_id" => ["type" => "integer", "description" => "The ID of the property"],
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
                        "description" => "Approve or reject a student booking.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "booking_id" => ["type" => "integer", "description" => "The ID of the booking"],
                                "status" => ["type" => "string", "description" => "Status: 'approved' or 'rejected'", "enum" => ["approved", "rejected"]]
                            ],
                            "required" => ["booking_id", "status"]
                        ]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "update_landlord_property",
                        "description" => "Update details of an existing property owned by the landlord (e.g. title, price, description, bedrooms, bathrooms, capacity). Note: verification and availability status cannot be changed via chat.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "property_id" => ["type" => "integer", "description" => "The ID of the property to update"],
                                "title" => ["type" => "string", "description" => "New property title"],
                                "description" => ["type" => "string", "description" => "New description"],
                                "price" => ["type" => "number", "description" => "New monthly rent in KSh"],
                                "bedrooms" => ["type" => "integer", "description" => "Number of bedrooms"],
                                "bathrooms" => ["type" => "integer", "description" => "Number of bathrooms"],
                                "capacity" => ["type" => "integer", "description" => "Total tenant capacity"]
                            ],
                            "required" => ["property_id"]
                        ]
                    ]
                ]
            ];
        }

        if (in_array($category, ['driver', 'transport', 'admin'])) {
            return [
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_assigned_trips",
                        "description" => "View assigned transport trips or available unassigned trips.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "status_filter" => ["type" => "string", "description" => "Filter: 'assigned', 'available', or 'completed'", "enum" => ["assigned", "available", "completed"]]
                            ],
                            "required" => []
                        ]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "claim_driver_trip",
                        "description" => "Claim an available unassigned transport trip.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "transport_id" => ["type" => "integer", "description" => "The transport trip ID to claim"]
                            ],
                            "required" => ["transport_id"]
                        ]
                    ]
                ],
                [
                    "type" => "function",
                    "function" => [
                        "name" => "update_trip_status",
                        "description" => "Update the progress status of an assigned transport trip.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "transport_id" => ["type" => "integer", "description" => "The transport trip ID"],
                                "status" => ["type" => "string", "description" => "New status: confirmed, in_transit, completed, or cancelled", "enum" => ["confirmed", "in_transit", "completed", "cancelled"]]
                            ],
                            "required" => ["transport_id", "status"]
                        ]
                    ]
                ]
            ];
        }

        // Student Tools
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

    /**
     * Dispatcher Gateway
     */
    private function dispatchToControllerGateway($name, $args, User $user, $senderPhone)
    {
        $request = new Request($args);

        switch ($name) {
            case 'search_housing':
            case 'search_properties':
                $properties = Property::where('area', 'LIKE', '%' . ($args['location'] ?? '') . '%')
                    ->when($args['max_price'] ?? null, fn($q, $price) => $q->where('price', '<=', $price))
                    ->take(3)
                    ->get();

                if ($properties->isEmpty()) {
                    return "No matching student properties found right now. Try checking another location.";
                }

                foreach ($properties as $property) {
                    if (!empty($property->images) && is_array($property->images) && count($property->images) > 0) {
                        $imageUrl = asset('storage/' . $property->images[0]);
                        $caption = "🏠 *{$property->title}*\n" .
                                   "💰 *KSh " . number_format($property->price) . "* / month\n" .
                                   "📍 *Location:* {$property->area} ({$property->address})\n" .
                                   "🛏 *Type:* " . ucfirst($property->property_type) . " ({$property->bedrooms} Bed)";
                        
                        $this->sendWhatsAppImageMedia($senderPhone, $imageUrl, $caption);
                    }
                }

                return "I found " . count($properties) . " property options for you above!";

            case 'get_landlord_properties':
                $properties = Property::where('landlord_id', $user->id)->get();
                if ($properties->isEmpty()) {
                    return "You currently have no registered properties.";
                }

                foreach ($properties as $property) {
                    if (!empty($property->images) && is_array($property->images) && count($property->images) > 0) {
                        $imageUrl = asset('storage/' . $property->images[0]);
                        $statusText = $property->is_verified ? 'Verified ✅' : 'Pending ⏳';
                        $caption = "🏡 *[ID: {$property->id}] {$property->title}*\n" .
                                   "💰 KSh " . number_format($property->price) . "/mo | Status: {$statusText}\n" .
                                   "📍 {$property->address}";
                        
                        $this->sendWhatsAppImageMedia($senderPhone, $imageUrl, $caption);
                    }
                }

                return "Here is the visual overview of your registered properties above.";

            case 'update_landlord_property':
                $propertyId = $args['property_id'] ?? null;
                $property = Property::where('id', $propertyId)->where('landlord_id', $user->id)->first();

                if (!$property) {
                    return "Property #{$propertyId} not found or you do not own it.";
                }

                $updateData = [];
                if (!empty($args['title'])) $updateData['title'] = $args['title'];
                if (!empty($args['description'])) $updateData['description'] = $args['description'];
                if (isset($args['price'])) $updateData['price'] = $args['price'];
                if (isset($args['bedrooms'])) $updateData['bedrooms'] = $args['bedrooms'];
                if (isset($args['bathrooms'])) $updateData['bathrooms'] = $args['bathrooms'];
                if (isset($args['capacity'])) $updateData['capacity'] = $args['capacity'];

                if (empty($updateData)) {
                    return "No valid fields provided to update for Property #{$propertyId}.";
                }

                $property->update($updateData);

                return "✅ Property #[ID: {$property->id}] '{$property->title}' has been successfully updated!";

            case 'check_transport_status':
            case 'fetch_transport_status':
                return app(\App\Http\Controllers\TransportController::class)->apiTransportStatus($request);

            case 'manage_storage':
            case 'check_storage_status':
                return app(\App\Http\Controllers\StorageHousingController::class)->apiManageStorage($request, $user);

            case 'get_landlord_bookings':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->landlordBookingsApi($user);

            case 'update_property_availability':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->updatePropertyAvailabilityApi($request, $user);

            case 'update_booking_status':
                return app(\App\Http\Controllers\Landlord\PropertyController::class)->updateBookingStatusApi($request, $user);

            case 'get_assigned_trips':
                $filter = $args['status_filter'] ?? 'assigned';
                $tripsQuery = Transport::with(['property', 'student'])
                    ->when($filter === 'assigned', fn($q) => $q->where('driver_id', $user->id)->whereIn('status', ['confirmed', 'assigned', 'in_transit']))
                    ->when($filter === 'available', fn($q) => $q->whereNull('driver_id')->where('status', 'pending'))
                    ->when($filter === 'completed', fn($q) => $q->where('driver_id', $user->id)->where('status', 'completed'))
                    ->latest('assigned_at')
                    ->take(5)
                    ->get();

                if ($tripsQuery->isEmpty()) {
                    return "No transport trips found for filter: {$filter}.";
                }

                return "🚚 *Transport Trips ({$filter}):*\n" . $tripsQuery->map(fn($t) => "• Trip #{$t->id}: Status: *{$t->status}* | Student: " . ($t->student->name ?? 'N/A') . " | To: " . ($t->property->title ?? 'N/A'))->implode("\n");

            case 'claim_driver_trip':
                $transportId = $args['transport_id'] ?? null;
                $transport = Transport::find($transportId);
                if (!$transport) return "Trip #{$transportId} not found.";
                if ($transport->driver_id !== null && $transport->driver_id !== $user->id) {
                    return "⚠️ This trip has already been claimed by another driver.";
                }

                $transport->update([
                    'driver_id' => $user->id,
                    'status'    => 'confirmed',
                ]);
                return "✅ Trip #{$transport->id} claimed successfully and confirmed!";

            case 'update_trip_status':
                $transportId = $args['transport_id'] ?? null;
                $status = $args['status'] ?? null;
                $transport = Transport::find($transportId);
                if (!$transport) return "Trip #{$transportId} not found.";
                if ($transport->driver_id !== $user->id && $user->category !== 'admin') {
                    return "⚠️ Unauthorized: You are not assigned to this transport trip.";
                }

                $transport->update(['status' => $status]);
                return "✅ Status for Trip #{$transport->id} successfully updated to " . ucfirst($status) . ".";

            case 'get_payment_link':
                $bookingId = $args['booking_id'] ?? null;
                $booking = Booking::where('id', $bookingId)->where('student_id', $user->id)->first();
                if (!$booking) return "Booking not found.";
                
                $paymentUrl = route('bookings.show', $booking->id);
                return "Secure Payment Link:\n👉 {$paymentUrl}\n\nTap above to complete your deposit securely in your browser.";

            default:
                return "Action completed successfully.";
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

    private function sendWhatsAppImageMedia($recipientPhone, $imageUrl, $caption)
    {
        $token = env('WHATSAPP_TOKEN', env('WHATSAPP_ACCESS_TOKEN'));
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        $baseUrl = rtrim(env('WHATSAPP_API_BASE_URL', 'https://graph.facebook.com/v17.0'), '/');

        if (!$token || !$phoneId) {
            Log::info("WhatsApp simulated image media to {$recipientPhone}: {$imageUrl} - {$caption}");
            return;
        }

        try {
            Http::withToken($token)->post("{$baseUrl}/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $recipientPhone,
                'type' => 'image',
                'image' => [
                    'link' => $imageUrl,
                    'caption' => $caption
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp Media Exception: ' . $e->getMessage());
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