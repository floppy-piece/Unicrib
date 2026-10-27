<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('email', 'admin@unicrib.com')->exists()) {
            return;
        }

        User::create([
            'name' => 'Unicrib Admin',
            'email' => 'admin@unicrib.com',
            'phone' => '254704969006',
            'category' => 'admin',
            'is_verified' => true,
            'paystack_subaccount_code' => 'ACCT_admin_sub',
            'password' => Hash::make('adminrules.105.ok,'),
            'email_verified_at' => now(),
        ]);
    }
}
