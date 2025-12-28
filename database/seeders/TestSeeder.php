<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->Create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=>'password',
            'category'=>'student',
        ]);
   
         /*
         |----------------------------------------------------------
         | LANDLORDS  (category = landlord)
         |----------------------------------------------------------
        */

        // Main demo landlord
        $landlord = User::firstOrCreate(
            ['email' => 'landlord@example.com'],
            [
                'name'     => 'Demo Landlord',
                'password' => 'password',
                'category' => 'landlord',
            ]
        );

        // Optional: another landlord, to see unverified-only properties
        $landlordTwo = User::firstOrCreate(
            ['email' => 'landlord2@example.com'],
            [
                'name'     => 'Second Demo Landlord',
                'password' => 'password',
                'category' => 'landlord',
            ]
        );

        /*
         |----------------------------------------------------------
         | PROPERTIES (verified + unverified)
         |----------------------------------------------------------
        */

        // VERIFIED properties for first landlord
        Property::factory()->create([
            'landlord_id' => $landlord->id,
            'title'       => 'Verified Bedsitter Near Campus',
            'price'       => 8000,              // adjust if your column is different
            'is_verified' => true,
            
        ]);

        Property::factory()->create([
            'landlord_id' => $landlord->id,
            'title'       => 'Verified 1 Bedroom Apartment',
            'price'       => 12000,
            'is_verified' => true,
            
        ]);

        // UNVERIFIED properties for first landlord
        Property::factory()->create([
            'landlord_id' => $landlord->id,
            'title'       => 'Unverified Bedsitter',
            'price'       => 7000,
            'is_verified' => false,
            
        ]);

        // Properties for second landlord (mix)
        Property::factory()->create([
            'landlord_id' => $landlordTwo->id,
            'title'       => 'Verified Hostel Room',
            'price'       => 6000,
            'is_verified' => true,
            
        ]);

        Property::factory()->create([
            'landlord_id' => $landlordTwo->id,
            'title'       => 'Unverified Single Room',
            'price'       => 5000,
            'is_verified' => false,
            
        ]);

        /*
         |----------------------------------------------------------
         | STUDENTS (category = student)
         |----------------------------------------------------------
        */

        // Explicit demo students you can log in with
        $demoStudents = [
            [
                'name'     => 'Verified Student One',
                'email'    => 'student0v@example.com',
                'is_verified' => true,
            ],
            [
                'name'     => 'Verified Student Two',
                'email'    => 'student1v@example.com',
                'is_verified' => true,
            ],
            [
                'name'     => 'Unverified Student One',
                'email'    => 'student0u@example.com',
                'is_verified' => false,
            ],
            [
                'name'     => 'Unverified Student Two',
                'email'    => 'student1u@example.com',
                'is_verified' => false,
            ],
        ];

        foreach ($demoStudents as $student) {
            User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'name'     => $student['name'],
                    'password' => 'password',
                    'category' => 'student',
                    // if you add student verification flags later, you can plug them here:
                    'is_verified'   => $student['is_verified'],
                    // 'student_verified_at'   => $student['verified'] ? now() : null,
                ]
            );
        }

        // Extra random students (using factory) with category preset
        User::factory()->count(6)->create([
            'category' => 'student',
            'password' => 'password',
        ]);
    }}
