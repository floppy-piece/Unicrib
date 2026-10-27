<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\WhatsAppAgentController;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppAgentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_webhook_verification()
    {
        $response = $this->get('/whatsapp/webhook?hub_mode=subscribe&hub_verify_token=unicrib_meta_token_2026&hub_challenge=12345');

        $response->assertStatus(200);
        $response->assertSee('12345');
    }

    public function test_whatsapp_agent_handles_search_housing_tool()
    {
        $user = User::create([
            'name' => 'Alice Student',
            'email' => 'alice@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'student',
            'phone' => '0711223344',
            'is_verified' => true,
        ]);

        Property::create([
            'landlord_id' => $user->id,
            'title' => 'Hostel Kutus',
            'description' => 'Hostel near university',
            'price' => 5000,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'size' => 15,
            'address' => 'Kutus Town',
            'area' => 'Kutus',
        ]);

        putenv('WHATSAPP_TOKEN=test_whatsapp_token');
        putenv('WHATSAPP_PHONE_NUMBER_ID=123456789');

        Http::fake([
            '*/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'tool_calls' => [
                                    [
                                        'id' => 'call_123',
                                        'type' => 'function',
                                        'function' => [
                                            'name' => 'search_housing',
                                            'arguments' => json_encode(['location' => 'Kutus', 'max_price' => 6000, 'property_type' => 'hostel'])
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ])
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Found hostel in Kutus for KSh 5000/mo!'
                            ]
                        ]
                    ]
                ]),
            'https://graph.facebook.com/v17.0/123456789/messages' => Http::response(['success' => true], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        'from' => '254711223344',
                                        'text' => [
                                            'body' => 'Find me a hostel in Kutus under 6000'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/whatsapp/webhook', $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        putenv('WHATSAPP_TOKEN');
        putenv('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function test_whatsapp_agent_handles_image_interpretation_unverified_user()
    {
        $user = User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'student',
            'phone' => '0733445566',
            'is_verified' => false,
        ]);

        putenv('WHATSAPP_TOKEN=test_whatsapp_token');
        putenv('WHATSAPP_PHONE_NUMBER_ID=123456789');

        Http::fake([
            'https://graph.facebook.com/v17.0/123456789/messages' => Http::response(['success' => true], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        'from' => '254733445566',
                                        'type' => 'image',
                                        'image' => [
                                            'id' => 'img_123',
                                            'caption' => 'What house is this?'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/whatsapp/webhook', $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        putenv('WHATSAPP_TOKEN');
        putenv('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function test_whatsapp_agent_handles_landlord_add_property_and_booking_tools()
    {
        $landlord = User::create([
            'name' => 'Jane Landlord',
            'email' => 'jane@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'landlord',
            'phone' => '0755667788',
            'is_verified' => true,
        ]);

        $student = User::create([
            'name' => 'Student John',
            'email' => 'john@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'student',
            'phone' => '0712345679',
        ]);

        $property = Property::create([
            'landlord_id' => $landlord->id,
            'title' => 'Landlord Apartment',
            'description' => 'Apartment',
            'price' => 8000,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'size' => 25,
            'address' => 'Kutus',
            'area' => 'Main Area',
        ]);

        $booking = Booking::create([
            'student_id' => $student->id,
            'landlord_id' => $landlord->id,
            'property_id' => $property->id,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        putenv('WHATSAPP_TOKEN=test_whatsapp_token');
        putenv('WHATSAPP_PHONE_NUMBER_ID=123456789');

        Http::fake([
            '*/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'tool_calls' => [
                                    [
                                        'id' => 'call_landlord_1',
                                        'type' => 'function',
                                        'function' => [
                                            'name' => 'update_booking_status',
                                            'arguments' => json_encode(['booking_id' => $booking->id, 'status' => 'approved'])
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ])
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Booking approved successfully!'
                            ]
                        ]
                    ]
                ]),
            'https://graph.facebook.com/v17.0/123456789/messages' => Http::response(['success' => true], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        'from' => '254755667788',
                                        'text' => [
                                            'body' => 'Approve booking #' . $booking->id
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/whatsapp/webhook', $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertEquals('approved', $booking->fresh()->status);

        putenv('WHATSAPP_TOKEN');
        putenv('WHATSAPP_PHONE_NUMBER_ID');
    }
}
