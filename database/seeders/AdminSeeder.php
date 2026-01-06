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
        if (User::where('email', 'admin@unicrib.com')->exists()) {
            return;
        }

        User::create([
            'name' => 'Unicrib Admin',
            'email' => 'admin@unicrib.com',
            'phone'=> '0704969006',
            'category' => 'admin',     // make sure `category` column allows 'admin'
            'password' => 'adminrules.105.ok,', // change to strong password
        ]);
    }
}
