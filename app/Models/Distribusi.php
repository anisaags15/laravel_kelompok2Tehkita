<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = 'distribusis';

    protected $fillable = [
        'bahan_id',
        'outlet_id',
        'jumlah',
        'status',
        'tanggal',
        'tanggal_diterima', // penting supaya bisa diupdate saat diterima
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_diterima' => 'datetime',
    ];

    // RELASI
    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    // ACCESSOR supaya langsung format di view/PDF
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal
            ? $this->tanggal->format('d M Y H:i') . ' WIB'
            : '-';
    }

    public function getTanggalDiterimaFormatAttribute()
    {
        return $this->tanggal_diterima
            ? $this->tanggal_diterima->format('d M Y H:i') . ' WIB'
            : '-';
    }

 public function getBahanNamaAttribute()
{
    return $this->bahan->nama_bahan ?? 'Tidak ada bahan';
}


    public function getOutletNamaAttribute()
    {
        return $this->outlet->nama ?? 'Tidak ada outlet';
    }
}