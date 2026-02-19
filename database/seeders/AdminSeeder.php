<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ini data adminteh yang tadi hilang di laptop temenmu
User::create([
    'name' => 'Admin Pusat',
    'username' => 'admin',
    'email' => 'adminteh@gmail.com',
    'no_hp' => '08123456789', 
    'password' => Hash::make('admin123'), // SUDAH DIGANTI JADI admin123
    'role' => 'admin',
    'email_verified_at' => now(),
]);
    }
}