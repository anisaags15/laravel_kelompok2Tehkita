<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminPusatSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Pusat',
            'username' => 'admin',
            'email' => 'adminteh@gmail.com',
            'no_hp' => '08123456789',
            'role' => 'admin',
            'outlet_id' => null, 
            'password' => bcrypt('admin123'), 
            'email_verified_at' => now(),
        ]);
    }
}
