<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramAgentController;
use App\Http\Controllers\Api\WhatsAppAgentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are stateless and automatically excluded from CSRF checks.
| Accessible via /api/...
|--------------------------------------------------------------------------
*/

// Telegram Webhook & Simulation Endpoints
Route::post('/telegram/webhook', [TelegramAgentController::class, 'handleWebhook']);
Route::post('/telegram/simulate', [TelegramAgentController::class, 'simulateMessage']);

// WhatsApp Webhook & Simulation Endpoints
Route::get('/whatsapp/webhook', [WhatsAppAgentController::class, 'verifyWebhook']);
Route::post('/whatsapp/webhook', [WhatsAppAgentController::class, 'handleIncomingMessage']);
Route::post('/whatsapp/simulate', [WhatsAppAgentController::class, 'simulateMessage']);