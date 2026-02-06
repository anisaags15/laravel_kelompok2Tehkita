<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOutlet extends Model
{
    use HasFactory;

    protected $table = 'stok_outlets';

    // kolom yang boleh diisi
    protected $fillable = [
        'outlet_id',
        'bahan_id',
        'stok',
    ];

    /**
     * RELASI
     */

    // stok ini milik satu outlet
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    // stok ini milik satu bahan
    public function bahan(): BelongsTo
    {
        return $this->belongsTo(Bahan::class);
    }
}
