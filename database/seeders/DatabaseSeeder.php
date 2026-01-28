<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Outlet::create([
            'nama_outlet' => 'Outlet Jakarta',
            'alamat' => 'Jl. Sudirman No.1',
            'no_hp' => '08123456789'
        ]);

        Outlet::create([
            'nama_outlet' => 'Outlet Bandung',
            'alamat' => 'Jl. Asia Afrika',
            'no_hp' => '08234567890'
        ]);

        Outlet::create([
            'nama_outlet' => 'Outlet Surabaya',
            'alamat' => 'Jl. Pemuda',
            'no_hp' => '08345678901'
        ]);
    }
}
