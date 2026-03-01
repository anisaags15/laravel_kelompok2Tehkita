<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    protected $table = 'waste'; 

    // Tambahkan field yang tadi ketinggalan agar bisa disimpan
    protected $fillable = [
        'outlet_id', 
        'stok_outlet_id', 
        'jumlah', 
        'keterangan', 
        'tanggal', 
        'foto', 
        'status'
    ];

    // Relasi ke Outlet (Biar Admin tahu ini dari cabang mana)
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    // Relasi ke StokOutlet
    public function stokOutlet()
    {
        return $this->belongsTo(StokOutlet::class, 'stok_outlet_id');
    }

    // Shortcut Relasi langsung ke Bahan (Biar manggilnya $waste->bahan->nama_bahan)
    public function bahan()
    {
        return $this->hasOneThrough(
            Bahan::class,
            StokOutlet::class,
            'id', // Foreign key di stok_outlets
            'id', // Foreign key di bahan
            'stok_outlet_id', // Local key di waste
            'bahan_id' // Local key di stok_outlets
        );
    }
}