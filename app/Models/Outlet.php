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
        'no_hp',
        'target_pemakaian_harian' // Sudah aman di sini
    ];

    public function users() { return $this->hasMany(User::class); }
    public function stokMasuk() { return $this->hasMany(StokMasuk::class); }
    public function stokOutlet() { return $this->hasMany(StokOutlet::class); }
    public function distribusi() { return $this->hasMany(Distribusi::class); }
    public function pemakaian() { return $this->hasMany(Pemakaian::class); }
}