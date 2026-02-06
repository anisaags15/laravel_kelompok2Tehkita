<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemakaian extends Model
{
    protected $fillable = ['bahan_id', 'outlet_id', 'jumlah', 'tanggal'];

    public function bahan(): BelongsTo { return $this->belongsTo(Bahan::class); }
    public function outlet(): BelongsTo { return $this->belongsTo(Outlet::class); }
}