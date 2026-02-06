<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuks';

    protected $fillable = [
        'bahan_id',
        'jumlah',
        'tanggal',
    ];

    // relasi: stok masuk milik satu bahan
    public function bahan(): BelongsTo
    {
        return $this->belongsTo(Bahan::class);
    }
}
