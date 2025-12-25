<?php

namespace Database\Seeders;

use App\Models\User;
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
    }
}

