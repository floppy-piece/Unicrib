<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transport;
use App\Models\Property;
use App\Services\TelegramService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UnicribAgentController extends Controller
{
    /**
     * Handle AI prompt processing with tool gateway for any user role across Telegram or WhatsApp.
     */
    public function handleAiPrompt(User $user, string $prompt, string $identifier, string $channel = 'whatsapp')
    {
        $apiKey = env('META_API_KEY');
        $baseUrl = rtrim(env('META_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'), '/');
        $model = env('META_MODEL_NAME', 'gemini-2.5-flash');
        $endpoint = "{$baseUrl}/chat/completions";

        $cacheKey = "unicrib_agent_chat_" . $channel . "_" . $identifier;
        $category = $user->category; // student, landlord, driver, transport, admin

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
            Log::error('Unicrib Agent AI Model API HTTP Error:', ['status' => $response->status(), 'body' => $response->body()]);
            return "I'm having trouble connecting right now. Please try again in a moment!";
        }

        $resData = $response->json();
        $messageChoice = $resData['choices'][0]['message'] ?? null;

        if (!$messageChoice) {
            return "I couldn't process that response. Please try again.";
        }

        // Handle Tool Calls
        if (isset($messageChoice['tool_calls'])) {
            $toolCall = $messageChoice['tool_calls'][0];
            $functionName = $toolCall['function']['name'];
            $arguments = json_decode($toolCall['function']['arguments'], true) ?? [];

            $toolResult = $this->dispatchToControllerGateway($functionName, $arguments, $user, $identifier, $channel);

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

        // Student Tools - Relaxed requirements for seamless searching
        return [
            [
                "type" => "function",
                "function" => [
                    "name" => "search_housing",
                    "description" => "Search available student housing, hostels, single rooms, bedsitters, and apartments by location, ward name, property ID, max budget, or general description.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "property_id" => ["type" => "integer", "description" => "Optional direct Property ID filter"],
                            "location" => ["type" => "string", "description" => "Location name, area, ward, or search terms (e.g. Kutus, Diaspora, bedsitter)"],
                            "max_price" => ["type" => "integer", "description" => "Maximum budget in Kenyan Shillings (KSh)"],
                            "property_type" => ["type" => "string", "description" => "Type of property e.g. hostel, apartment, single room, bedsitter"]
                        ],
                        "required" => []
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
                    "name" => "reserve_storage_unit_via_chat",
                    "description" => "Reserve a storage housing unit by specifying the unit size (small, medium, large, standard) and generate a secure payment link.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "unit_size" => [
                                "type" => "string",
                                "description" => "Size of the storage unit",
                                "enum" => ["small", "medium", "large", "standard"]
                            ]
                        ],
                        "required" => ["unit_size"]
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
            ],
            [
                "type" => "function",
                "function" => [
                    "name" => "book_property_via_chat",
                    "description" => "Get full details link for a specific property ID to inspect amenities and pay the deposit on the web dashboard.",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "property_id" => ["type" => "integer", "description" => "The ID of the property to book"]
                        ],
                        "required" => ["property_id"]
                    ]
                ]
            ]
        ];
    }

    /**
     * Dispatch tool call and route media/cards to either Telegram or WhatsApp based on channel.
     */
    private function dispatchToControllerGateway($name, $args, User $user, string $identifier, string $channel)
    {
        $request = new Request($args);
        $telegramService = $channel === 'telegram' ? app(TelegramService::class) : null;
        $whatsappService = $channel === 'whatsapp' ? app(WhatsAppService::class) : null;

        switch ($name) {
            case 'search_housing':
            case 'search_properties':
                $query = Property::whereRaw('is_available = true')
                    ->whereRaw('is_verified = true');

                // Explicit Property ID Filter
                if (!empty($args['property_id'])) {
                    $query->where('id', (int) $args['property_id']);
                }

                // Broad text search across area, address, title, description, or property_type
                if (!empty($args['location'])) {
                    $searchTerm = trim($args['location']);
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('area', 'ILIKE', '%' . $searchTerm . '%')
                          ->orWhere('address', 'ILIKE', '%' . $searchTerm . '%')
                          ->orWhere('title', 'ILIKE', '%' . $searchTerm . '%')
                          ->orWhere('description', 'ILIKE', '%' . $searchTerm . '%')
                          ->orWhere('property_type', 'ILIKE', '%' . $searchTerm . '%');
                    });
                }

                if (!empty($args['property_type'])) {
                    $query->where('property_type', 'ILIKE', '%' . $args['property_type'] . '%');
                }

                if (!empty($args['max_price'])) {
                    $query->where('price', '<=', (float) $args['max_price']);
                }

                $properties = $query->take(5)->get();

                // Fallback: If broad search returned 0 results, fetch top recommended available listings
                if ($properties->isEmpty()) {
                    $properties = Property::whereRaw('is_available = true')
                        ->whereRaw('is_verified = true')
                        ->latest()
                        ->take(3)
                        ->get();

                    if ($properties->isEmpty()) {
                        return "No student properties are currently available in the database.";
                    }
                }

                foreach ($properties as $property) {
                    // Public GET route for viewing full details & booking deposit form
                    $propertyUrl = route('properties.show', $property->id);

                    $caption = "🏠 <b>{$property->title}</b> [ID: {$property->id}]\n" .
                               "💰 <b>KSh " . number_format($property->price) . "</b> / month\n" .
                               "📍 <b>Location:</b> {$property->area} ({$property->address})\n" .
                               "🛏 <b>Type:</b> " . ucfirst($property->property_type) . " ({$property->bedrooms} Bed)\n\n" .
                               "👉 <b>View Details & Book:</b> {$propertyUrl}";

                    // Check for attached property images
                    if (!empty($property->images) && is_array($property->images) && count($property->images) > 0) {
                        $rawPath = preg_replace('/^public\//', '', $property->images[0]);
                        $imageUrl = url('storage/' . $rawPath);

                        if ($channel === 'telegram' && $telegramService) {
                            $telegramService->sendPhoto($identifier, $imageUrl, $caption);
                        } elseif ($channel === 'whatsapp' && $whatsappService) {
                            if (method_exists($whatsappService, 'sendCtaUrlButton')) {
                                $whatsappService->sendCtaUrlButton(
                                    $identifier,
                                    strip_tags($caption),
                                    'View & Book',
                                    $propertyUrl,
                                    'UniCrib Housing'
                                );
                            } else {
                                $whatsappService->sendImage($identifier, $imageUrl, strip_tags($caption));
                            }
                        }
                    } else {
                        // Send text card if property has no uploaded images
                        if ($channel === 'telegram' && $telegramService) {
                            $telegramService->sendInlineButtons($identifier, $caption, []);
                        } elseif ($channel === 'whatsapp' && $whatsappService) {
                            $whatsappService->sendMessage($identifier, strip_tags($caption));
                        }
                    }
                }

                return "I found " . count($properties) . " matching property option(s) for you above! Tap 'View Details & Book' on any property to inspect its full details and pay your deposit securely.";

            case 'book_property_via_chat':
                $propertyId = $args['property_id'] ?? null;
                $property = Property::where('id', $propertyId)->whereRaw('is_available = true')->first();

                if (!$property) {
                    return "Sorry, Property #{$propertyId} was not found or is currently unavailable.";
                }

                // Check active student booking status
                $existing = Booking::where('student_id', $user->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->first();

                if ($existing) {
                    return "You already have an active booking (Booking #{$existing->id}). Please request cancellation from your landlord before booking a new property.";
                }

                if ($property->isFull()) {
                    return "Sorry, Property #{$property->id} ({$property->title}) is already full.";
                }

                // Generate public GET details & booking link
                $propertyUrl = route('properties.show', $property->id);

                return "🏠 <b>{$property->title}</b> [ID: {$property->id}]\n" .
                       "💰 Monthly Rent: KSh " . number_format($property->price) . "\n" .
                       "📍 Location: {$property->address}\n\n" .
                       "👉 <b>Complete Booking & Deposit:</b>\n{$propertyUrl}\n\n" .
                       "Tap the link above to view full property details, terms, and complete your secure deposit payment.";            

            case 'reserve_storage_unit_via_chat':
                $unitSize = $args['unit_size'] ?? 'standard';
                $monthlyPrice = 800; // Standard monthly price

                $storageBooking = \App\Models\StorageBooking::create([
                    'user_id'       => $user->id,
                    'unit_size'     => $unitSize,
                    'monthly_price' => $monthlyPrice,
                    'paid_amount'   => 0,
                    'status'        => 'pending',
                    'start_date'    => now()->toDateString(),
                ]);

                $paymentUrl = route('payments.storage.form', $storageBooking->id);

                return "📦 Storage Unit ({$unitSize}) reserved successfully! [Booking ID: {$storageBooking->id}]\n\nSecure Payment Link:\n👉 {$paymentUrl}\n\nTap above to complete your initial storage deposit securely in your browser.";

            case 'get_landlord_properties':
                $properties = Property::where('landlord_id', $user->id)->get();
                if ($properties->isEmpty()) {
                    return "You currently have no registered properties.";
                }

                foreach ($properties as $property) {
                    if (!empty($property->images) && is_array($property->images) && count($property->images) > 0) {
                        $imageUrl = asset('storage/' . $property->images[0]);
                        $statusText = $property->is_verified ? 'Verified ✅' : 'Pending ⏳';
                        $caption = "🏡 <b>[ID: {$property->id}] {$property->title}</b>\n" .
                                   "💰 KSh " . number_format($property->price) . "/mo | Status: {$statusText}\n" .
                                   "📍 {$property->address}";
                        
                        if ($channel === 'telegram' && $telegramService) {
                            $telegramService->sendPhoto($identifier, $imageUrl, $caption);
                        } elseif ($channel === 'whatsapp' && $whatsappService) {
                            $whatsappService->sendImage($identifier, $imageUrl, strip_tags($caption));
                        }
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
                return "🚚 <b>Transport Trips ({$filter}):</b>\n" . $tripsQuery->map(fn($t) => "• Trip #{$t->id}: Status: <b>{$t->status}</b> | Student: " . ($t->student->name ?? 'N/A') . " | To: " . ($t->property->title ?? 'N/A'))->implode("\n");

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
}