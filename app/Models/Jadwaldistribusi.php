<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDistribusi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_distribusi';

    protected $fillable = [
        'keterangan',
        'tanggal_rencana',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: hanya yang upcoming
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                     ->where('tanggal_rencana', '>=', now()->toDateString())
                     ->orderBy('tanggal_rencana', 'asc');
    }
}