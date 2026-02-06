<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOutlet extends Model
{
    use HasFactory;

    protected $table = 'stok_outlets';

    protected $fillable = [
        'outlet_id',
        'bahan_id',
        'stok',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function bahan(): BelongsTo
    {
        return $this->belongsTo(Bahan::class);
    }
}
