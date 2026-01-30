<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOutlet extends Model
{
    use HasFactory;

    protected $table = 'stok_outlet';

    protected $fillable = [
        'nama_outlet',
        'nama_barang',
        'stok',
    ];
}
