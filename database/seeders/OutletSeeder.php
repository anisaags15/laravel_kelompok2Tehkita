<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        Outlet::create([
            'nama_outlet' => 'Teh Kita Perumnas',
            'alamat' => 'Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat',
            'no_hp' => '081234567890'
        ]);

        Outlet::create([
            'nama_outlet' => 'Teh Kita Arumsari',
            'alamat' => 'Jl. Jembatan Merah No.18, Kecomberan, Kec. Talun, Kabupaten Cirebon, Jawa Barat',
            'no_hp' => '081234567891'
        ]);
        
        Outlet::create([
            'nama_outlet' => 'Teh Kita Perjuangan',
            'alamat' => '7G7M+VW6, Jl. Yudhasari II, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45132',
            'no_hp' => '081234567894'
        ]);
        
        Outlet::create([
            'nama_outlet' => 'Teh Kita Plumbon',
            'alamat' => 'Jl. Mertabasah, Pamijahan, Kec. Plumbon, Kabupaten Cirebon, Jawa Barat 45155',
            'no_hp' => '081234567892'
        ]);

        Outlet::create([
            'nama_outlet' => 'Teh Kita Klangenan',
            'alamat' => '8C8X+WRG, Jl. Nyimas Endang Geulis, Jemaras Kidul, Kec. Klangenan, Kabupaten Cirebon, Jawa Barat 45156',
            'no_hp' => '081234567893'
        ]);

    }
}
