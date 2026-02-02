<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_outlet',
        'alamat',
        'no_hp'
    ];

    // admin outlet (user)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // stok masuk ke outlet
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }

    // stok tersedia di outlet
    public function stokOutlet()
    {
        return $this->hasMany(StokOutlet::class);
    }

    // distribusi ke outlet
    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    // pemakaian bahan di outlet
    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class);
    }
}
