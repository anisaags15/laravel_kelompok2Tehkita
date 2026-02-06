<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    // nama tabel
    protected $table = 'distribusis';

    // kolom yang boleh diisi
    protected $fillable = [
        'bahan_id',
        'outlet_id',
        'jumlah',
        'status',
        'tanggal',
    ];

    /**
     * RELASI
     */

    // distribusi milik satu bahan
    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    // distribusi milik satu outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
