<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 
    public function run(): void
    {
        // avoid duplicate admin with same email
        if (User::where('email', 'admin@example.com')->exists()) {
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'category' => 'admin',     // make sure `category` column allows 'admin'
            'password' => 'adminrules', // change to strong password
        ]);
    }
}
