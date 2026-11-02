<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $user = User::where('telegram_chat_id', (string) $chatId)->first();

            // 2. Handle Telegram /start Command with Interactive Welcome Options
            if ($text === '/start') {
                if ($user) {
                    $menuOptions = match ($user->category) {
                        'landlord' => [
                            [['text' => '🏠 My Properties', 'callback_data' => 'menu_landlord_props'], ['text' => '📋 Tenant Bookings', 'callback_data' => 'menu_landlord_bookings']]
                        ],
                        'driver', 'transport' => [
                            [['text' => '🚚 Assigned Trips', 'callback_data' => 'menu_driver_trips'], ['text' => '🔍 Available Trips', 'callback_data' => 'menu_driver_available']]
                        ],
                        default => [
                            [['text' => '🏠 Search Housing', 'callback_data' => 'menu_housing'], ['text' => '🚚 Transport Status', 'callback_data' => 'menu_transport']],
                            [['text' => '📦 Storage Units', 'callback_data' => 'menu_storage']]
                        ]
                    };

                    $this->telegram->sendInlineButtons(
                        $chatId, 
                        "👋 <b>Welcome back to UniCrib, {$user->name}!</b>\n\nYour account is connected. What would you like to manage today?",
                        $menuOptions
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

                if ($user->category === 'landlord') {
                    // Extract numbers from caption safely
                    $propertyId = trim(filter_var($text, FILTER_SANITIZE_NUMBER_INT));

                    // 🔒 Guard clause: If no valid integer ID was sent in the caption, inform the user cleanly
                    if (empty($propertyId) || !is_numeric($propertyId)) {
                        $this->telegram->sendInlineButtons(
                            $chatId, 
                            "⚠️ <b>Missing Property ID!</b>\n\nPlease specify the Property ID in your photo caption when uploading (e.g., <i>'Property 2'</i> or just the number <i>'2'</i>).", 
                            []
                        );
                        return response()->json(['status' => 'missing_property_id']);
                    }

                    $property = Property::where('id', (int) $propertyId)->where('landlord_id', $user->id)->first();

                    if ($property && $fileId) {
                        $token = env('TELEGRAM_BOT_TOKEN');
                        $fileRes = Http::get("https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}");
                        
                        if ($fileRes->successful() && $fileRes->json('ok')) {
                            $filePath = $fileRes->json('result.file_path');
                            $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

                            // Download and store securely
                            $contents = file_get_contents($fileUrl);
                            $storagePath = 'properties/' . $user->id . '/' . uniqid() . '.jpg';
                            \Illuminate\Support\Facades\Storage::disk('public')->put($storagePath, $contents);

                            // Append to existing images array
                            $images = $property->images ?? [];
                            $images[] = $storagePath;
                            $property->update(['images' => $images]);

                            $this->telegram->sendInlineButtons($chatId, "✅ New photo successfully added to Property #[ID: {$property->id}] {$property->title}!", []);
                            return response()->json(['status' => 'success']);
                        }
                    }

                    $this->telegram->sendInlineButtons($chatId, "❌ Property #{$propertyId} was not found or does not belong to you.", []);
                    return response()->json(['status' => 'property_not_found']);
                }

                // Default student image interpretation flow
                $aiResponse = app(TelegramImageInterpretationController::class)->interpretHouseImage($user, $fileId, $text);
                $this->telegram->sendInlineButtons($chatId, $aiResponse, []);
                return response()->json(['status' => 'success']);
            }

            // 7. Handle Text Commands / AI Prompts via UnicribAgentController
            if (empty($text)) {
                $this->telegram->sendInlineButtons($chatId, "⚠️ Received an empty message.", []);
                return response()->json(['status' => 'empty_text']);
            }

            $aiResponse = app(UnicribAgentController::class)->handleAiPrompt($user, $text, $chatId);
            $this->telegram->sendInlineButtons($chatId, $aiResponse, []);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Telegram Webhook Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            if (isset($chatId)) {
                $this->telegram->sendInlineButtons($chatId, "⚠️ An error occurred while processing your request. Please try again later. ". $e->getMessage(), []);
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

        $user = User::where('telegram_chat_id', (string) $chatId)->first();

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

        $this->telegram->answerCallbackQuery($callbackId);
        return response()->json(['status' => 'success']);
    }
}