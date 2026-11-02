<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UnicribAgentController extends Controller
{
    /**
     * Handle AI prompt processing with tool gateway for any user role (student, landlord, driver).
     */
    public function handleAiPrompt(User $user, string $prompt, string $chatId)
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');
        $endpoint = "{$baseUrl}/chat/completions";

        $cacheKey = "telegram_chat_" . $chatId;
        $category = $user->category; // student, landlord, driver, transport, admin

        $systemPrompt = match ($category) {
            'landlord' => "You are Unicrib Landlord Assistant, a professional AI manager helping property owner {$user->name} manage their listings, monitor student bookings, update property availability, and check verification statuses in Kenya.",
            'driver', 'transport' => "You are Unicrib Driver Assistant, helping transport provider {$user->name} view assigned transport trips, claim available student rides, and update transit statuses.",
            default => "You are Unicrib AI, a helpful multimodal assistant for student housing, storage, and transport dispatch in Kenya. User is {$user->name}.",
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
            Log::error('Unicrib Agent AI Model API HTTP Error:', ['status' => $response->status(), 'body' => $response->body()]);
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

    /**
     * Get tool schemas dynamically based on user category.
     */
    public function getToolSchemas(string $category): array
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
     * Dispatch tool call to appropriate controller method.
     */
    private function dispatchToControllerGateway($name, $args, User $user)
    {
        $request = new Request($args);

        switch ($name) {
            // Inside dispatchToControllerGateway() or property API responses:
            case 'search_housing':
            case 'search_properties':
                $properties = \App\Models\Property::where('area', 'LIKE', '%' . ($args['location'] ?? '') . '%')
                    ->when($args['max_price'] ?? null, fn($q, $price) => $q->where('price', '<=', $price))
                    ->take(2)
                    ->get();

                if ($properties->isEmpty()) {
                    return "No properties found matching your criteria.";
                }

                foreach ($properties as $property) {
                    if (!empty($property->images) && is_array($property->images) && count($property->images) > 0) {
                        // Generate public storage URL for Telegram to stream
                        $imageUrl = asset('storage/' . $property->images[0]);
                        $caption = "🏠 <b>{$property->title}</b>\n💰 KSh " . number_format($property->price) . "/mo\n📍 {$property->address}";
                        
                        // Dispatch photo directly to the user's Telegram chat
                        app(\App\Services\TelegramService::class)->sendPhoto($user->telegram_chat_id, $imageUrl, $caption);
                    }
                }

                return "Here are the matching properties found for you above with their photos!";
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
                return "Transport Trips ({$filter}):\n" . $tripsQuery->map(fn($t) => "• Trip #{$t->id}: Status: {$t->status} | Student: " . ($t->student->name ?? 'N/A') . " | To: " . ($t->property->title ?? 'N/A'))->implode("\n");

            case 'claim_driver_trip':
                $transportId = $args['transport_id'] ?? null;
                $transport = Transport::find($transportId);
                if (!$transport) return "Trip #{$transportId} not found.";
                if ($transport->driver_id !== null && $transport->driver_id !== $user->id) {
                    return "This trip has already been claimed by another driver.";
                }
                $transport->update([
                    'driver_id' => $user->id,
                    'status'    => 'confirmed',
                ]);
                return "Trip #{$transport->id} claimed successfully and confirmed!";

            case 'update_trip_status':
                $transportId = $args['transport_id'] ?? null;
                $status = $args['status'] ?? null;
                $transport = Transport::find($transportId);
                if (!$transport) return "Trip #{$transportId} not found.";
                if ($transport->driver_id !== $user->id && $user->category !== 'admin') {
                    return "Unauthorized: You are not assigned to this transport trip.";
                }
                $transport->update(['status' => $status]);
                return "Status for Trip #{$transport->id} successfully updated to " . ucfirst($status) . ".";

            case 'get_payment_link':
                $bookingId = $args['booking_id'] ?? null;
                $booking = Booking::where('id', $bookingId)->where('student_id', $user->id)->first();
                if (!$booking) return "Booking not found.";
                
                $paymentUrl = route('bookings.show', $booking->id);
                return "Please complete your payment securely in your browser using this link: {$paymentUrl}";

            default:
                return "Gateway action completed successfully.";
        }
    }
}