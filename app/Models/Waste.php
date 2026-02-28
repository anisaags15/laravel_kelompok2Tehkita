<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    // Ini penting karena nama tabelmu 'waste' (bukan 'wastes')
    protected $table = 'waste'; 

    protected $fillable = ['outlet_id', 'stok_outlet_id', 'jumlah', 'keterangan'];

    // Relasi ke StokOutlet (Biar laporannya bisa panggil nama bahan)
    public function stokOutlet()
    {
        return $this->belongsTo(StokOutlet::class, 'stok_outlet_id');
    }
}