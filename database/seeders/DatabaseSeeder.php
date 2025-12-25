<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->Create([
            'name' => 'Test student',
            'email' => 'student@example.com',
            'password'=>'password',
            'category'=>'student',
        ]);
        User::factory()->Create([
            'name' => 'Test landlord',
            'email' => 'landlord@example.com',
            'password'=>'password',
            'category'=>'landlord',
        ]);
    }
}
