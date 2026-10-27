<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\StorageBooking;
use App\Models\Driver;
use App\Models\Transport;
use App\Models\ContactMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Test Student
        $testUser = User::firstOrCreate(
            ['email' => 'test23@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'category' => 'student',
                'phone' => '254704969006',
                'is_verified' => true,
                'email_verified_at' => now(),
                'paystack_subaccount_code' => 'ACCT_test23',
                'access_expires_at' => now()->addYear(),
            ]
        );

        // 2. Landlords
        $landlord1 = User::firstOrCreate(
            ['email' => 'landlord@example.com'],
            [
                'name' => 'Demo Landlord',
                'password' => Hash::make('password'),
                'category' => 'landlord',
                'phone' => '254722334455',
                'is_verified' => true,
                'email_verified_at' => now(),
                'paystack_subaccount_code' => 'ACCT_landlord1',
                'access_expires_at' => now()->addYear(),
            ]
        );

        $landlord2 = User::firstOrCreate(
            ['email' => 'landlord2@example.com'],
            [
                'name' => 'Second Demo Landlord',
                'password' => Hash::make('password'),
                'category' => 'landlord',
                'phone' => '254733445566',
                'is_verified' => true,
                'email_verified_at' => now(),
                'paystack_subaccount_code' => 'ACCT_landlord2',
                'access_expires_at' => now()->addYear(),
            ]
        );

        // 3. Demo Students
        $demoStudents = [
            [
                'name' => 'Verified Student One',
                'email' => 'student0v@example.com',
                'phone' => '254744556677',
                'is_verified' => true,
            ],
            [
                'name' => 'Verified Student Two',
                'email' => 'student1v@example.com',
                'phone' => '254755667788',
                'is_verified' => true,
            ],
            [
                'name' => 'Unverified Student One',
                'email' => 'student0u@example.com',
                'phone' => '254766778899',
                'is_verified' => false,
            ],
            [
                'name' => 'Unverified Student Two',
                'email' => 'student1u@example.com',
                'phone' => '254777889900',
                'is_verified' => false,
            ],
        ];

        $studentModels = [$testUser];
        foreach ($demoStudents as $studentData) {
            $studentModels[] = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                    'category' => 'student',
                    'phone' => $studentData['phone'],
                    'is_verified' => $studentData['is_verified'],
                    'email_verified_at' => now(),
                    'paystack_subaccount_code' => 'ACCT_' . Str::random(8),
                    'access_expires_at' => $studentData['is_verified'] ? now()->addYear() : null,
                ]
            );
        }

        // 4. Properties with fully populated non-null fields
        $properties = [
            [
                'landlord_id' => $landlord1->id,
                'title' => 'Verified Bedsitter Near Campus',
                'description' => 'A spacious, well-lit bedsitter with reliable water, high-speed Wi-Fi, and 24/7 security guard at the gate.',
                'price' => 8000.00,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'size' => 25.00,
                'address' => 'Kutus Town, Near Kirinyaga University Main Gate',
                'city' => 'Kutus',
                'area' => 'Kutus',
                'street' => 'University Way',
                'building_name' => 'Sunrise Heights Hostel',
                'landmark' => 'Opposite Total Petrol Station',
                'latitude' => -0.56789012,
                'longitude' => 37.34567890,
                'map_lat' => -0.56789012,
                'map_lng' => 37.34567890,
                'location_confirmed' => true,
                'formatted_address' => 'Sunrise Heights Hostel, University Way, Kutus, Kenya',
                'geocoding_data' => ['provider' => 'openstreetmap', 'accuracy' => 'exact'],
                'amenities' => ['Wi-Fi', 'Security', 'Water 24/7', 'CCTV', 'Balcony'],
                'property_type' => 'bedsitter',
                'is_available' => true,
                'is_verified' => true,
                'verification_expires_at' => now()->addMonths(6),
                'capacity' => 10,
                'booked_count' => 2,
                'images' => ['/images/property.jpeg', '/images/home.jpeg'],
            ],
            [
                'landlord_id' => $landlord1->id,
                'title' => 'Verified 1 Bedroom Apartment',
                'description' => 'Modern 1 bedroom apartment with tiled floors, open kitchen plan, balcony, and ample parking space.',
                'price' => 12000.00,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'size' => 45.00,
                'address' => 'Kangaru Road, Kutus',
                'city' => 'Kutus',
                'area' => 'Kangaru',
                'street' => 'Kangaru Road',
                'building_name' => 'Greenview Court',
                'landmark' => 'Near ACK Church',
                'latitude' => -0.56123456,
                'longitude' => 37.34123456,
                'map_lat' => -0.56123456,
                'map_lng' => 37.34123456,
                'location_confirmed' => true,
                'formatted_address' => 'Greenview Court, Kangaru Road, Kutus, Kenya',
                'geocoding_data' => ['provider' => 'openstreetmap', 'accuracy' => 'exact'],
                'amenities' => ['Wi-Fi', 'Parking', 'Water 24/7', 'Security', 'Backup Generator'],
                'property_type' => 'apartment',
                'is_available' => true,
                'is_verified' => true,
                'verification_expires_at' => now()->addMonths(6),
                'capacity' => 5,
                'booked_count' => 1,
                'images' => ['/images/property.jpeg', '/images/unnamed.jpeg'],
            ],
            [
                'landlord_id' => $landlord1->id,
                'title' => 'Unverified Bedsitter',
                'description' => 'Affordable student bedsitter close to campus shortcuts.',
                'price' => 7000.00,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'size' => 20.00,
                'address' => 'Junction Area, Kutus',
                'city' => 'Kutus',
                'area' => 'Junction',
                'street' => 'Shortcut Lane',
                'building_name' => 'Mapinduzi House',
                'landmark' => 'Behind Stage',
                'latitude' => -0.56999999,
                'longitude' => 37.34999999,
                'map_lat' => -0.56999999,
                'map_lng' => 37.34999999,
                'location_confirmed' => false,
                'formatted_address' => 'Mapinduzi House, Shortcut Lane, Kutus, Kenya',
                'geocoding_data' => ['provider' => 'openstreetmap', 'accuracy' => 'approximate'],
                'amenities' => ['Water', 'Security'],
                'property_type' => 'bedsitter',
                'is_available' => true,
                'is_verified' => false,
                'verification_expires_at' => null,
                'capacity' => 8,
                'booked_count' => 0,
                'images' => ['/images/property.jpeg'],
            ],
            [
                'landlord_id' => $landlord2->id,
                'title' => 'Verified Hostel Room',
                'description' => 'Clean hostel room specifically tailored for university students with study desk and Wi-Fi.',
                'price' => 6000.00,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'size' => 18.00,
                'address' => 'Hostel Zone, Kutus',
                'city' => 'Kutus',
                'area' => 'Hostel Zone',
                'street' => 'Campus Road',
                'building_name' => 'Scholars Haven',
                'landmark' => 'Next to Student Mess',
                'latitude' => -0.56555555,
                'longitude' => 37.34555555,
                'map_lat' => -0.56555555,
                'map_lng' => 37.34555555,
                'location_confirmed' => true,
                'formatted_address' => 'Scholars Haven, Campus Road, Kutus, Kenya',
                'geocoding_data' => ['provider' => 'openstreetmap', 'accuracy' => 'exact'],
                'amenities' => ['Wi-Fi', 'Study Table', 'Water 24/7', 'Security'],
                'property_type' => 'hostel',
                'is_available' => true,
                'is_verified' => true,
                'verification_expires_at' => now()->addMonths(6),
                'capacity' => 15,
                'booked_count' => 3,
                'images' => ['/images/property.jpeg', '/images/home.jpeg'],
            ],
            [
                'landlord_id' => $landlord2->id,
                'title' => 'Unverified Single Room',
                'description' => 'Budget-friendly single room with shared facilities.',
                'price' => 5000.00,
                'bedrooms' => 1,
                'bathrooms' => 0,
                'size' => 15.00,
                'address' => 'Market Area, Kutus',
                'city' => 'Kutus',
                'area' => 'Market',
                'street' => 'Market Street',
                'building_name' => 'Uasin Gishu Annex',
                'landmark' => 'Near Open Air Market',
                'latitude' => -0.56888888,
                'longitude' => 37.34888888,
                'map_lat' => -0.56888888,
                'map_lng' => 37.34888888,
                'location_confirmed' => false,
                'formatted_address' => 'Uasin Gishu Annex, Market Street, Kutus, Kenya',
                'geocoding_data' => ['provider' => 'openstreetmap', 'accuracy' => 'approximate'],
                'amenities' => ['Water'],
                'property_type' => 'single-room',
                'is_available' => true,
                'is_verified' => false,
                'verification_expires_at' => null,
                'capacity' => 6,
                'booked_count' => 0,
                'images' => ['/images/property.jpeg'],
            ],
        ];

        $propertyModels = [];
        foreach ($properties as $propData) {
            $propertyModels[] = Property::firstOrCreate(
                ['title' => $propData['title']],
                $propData
            );
        }

        // 5. Bookings
        foreach ($studentModels as $index => $student) {
            $prop = $propertyModels[$index % count($propertyModels)];
            Booking::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'property_id' => $prop->id,
                ],
                [
                    'landlord_id' => $prop->landlord_id,
                    'status' => $index % 2 === 0 ? Booking::STATUS_APPROVED : Booking::STATUS_PENDING,
                    'message' => 'Hello, I am interested in renting this property for the upcoming semester.',
                    'amount' => (int) $prop->price,
                    'payment_status' => $index % 2 === 0 ? Booking::PAYMENT_PAID : Booking::PAYMENT_UNPAID,
                    'payment_reference' => 'PAY-' . strtoupper(Str::random(10)),
                    'paystack_subaccount_code' => $prop->landlord->paystack_subaccount_code,
                    'check_in_date' => now()->addDays(5)->toDateString(),
                    'check_out_date' => now()->addMonths(6)->toDateString(),
                ]
            );
        }

        // 6. Payments
        foreach ($studentModels as $index => $student) {
            Payment::firstOrCreate(
                ['reference' => 'REF-' . strtoupper(Str::random(12))],
                [
                    'user_id' => $student->id,
                    'amount' => 150000,
                    'currency' => 'KES',
                    'status' => 'success',
                    'provider_payload' => ['gateway' => 'paystack', 'channel' => 'm-pesa', 'status' => 'success'],
                ]
            );
        }

        // 7. Storage Bookings
        foreach ($studentModels as $index => $student) {
            StorageBooking::firstOrCreate(
                ['user_id' => $student->id, 'reference' => 'STR-' . strtoupper(Str::random(10))],
                [
                    'start_date' => now()->addDays(1)->toDateString(),
                    'end_date' => now()->addMonths(3)->toDateString(),
                    'months' => 3,
                    'amount' => 4500,
                    'paid_amount' => 1500,
                    'status' => 'active',
                ]
            );
        }

        // 8. Drivers & Transports
        $driversData = [
            [
                'name' => 'John Kiprop',
                'phone' => '254711122233',
                'vehicle_model' => 'Toyota Probox',
                'vehicle_plate' => 'KBC 123A',
                'status' => 'available',
            ],
            [
                'name' => 'David Omondi',
                'phone' => '254722233344',
                'vehicle_model' => 'Honda Bodaboda',
                'vehicle_plate' => 'KMD 456B',
                'status' => 'available',
            ],
        ];

        $driverModels = [];
        foreach ($driversData as $dData) {
            $driverModels[] = Driver::firstOrCreate(
                ['phone' => $dData['phone']],
                $dData
            );
        }

        $bookings = Booking::all();
        foreach ($bookings as $i => $booking) {
            $driver = $driverModels[$i % count($driverModels)];
            Transport::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'property_id' => $booking->property_id,
                    'student_id' => $booking->student_id,
                    'driver_id' => $driver->id,
                    'student_name' => $booking->student->name,
                    'student_phone' => $booking->student->phone ?? '254700000000',
                    'requested_date' => now()->addDays(2)->toDateString(),
                    'requested_time' => '10:00:00',
                    'assigned_at' => now(),
                    'status' => 'driver_assigned',
                ]
            );
        }

        // 9. Contact Messages
        ContactMessage::firstOrCreate(
            ['email' => 'visitor@example.com'],
            [
                'name' => 'Jane Visitor',
                'role' => 'Parent',
                'subject' => 'Inquiry about hostel security',
                'message' => 'Hello, I would like to know if security guards patrol the premises 24/7.',
                'ip' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64)',
            ]
        );
    }
}
