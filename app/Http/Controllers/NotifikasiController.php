<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Message;

class NotifikasiController extends Controller
{
    /**
     * NOTIFIKASI UNTUK ADMIN PUSAT
     * Melihat semua stok kritis dari seluruh outlet
     */
    public function indexAdmin()
    {
        // 1. Ambil stok yang kritis (<= 5) dari SEMUA outlet
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();

        // 2. Ambil pesan masuk yang belum dibaca
        $unreadMessages = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->with('sender')
            ->latest()
            ->get();

        return view('admin.notifikasi.index', compact('stokKritis', 'unreadMessages'));
    }

    /**
     * NOTIFIKASI UNTUK ADMIN OUTLET (USER)
     * Hanya melihat peringatan untuk outletnya sendiri
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Stok kurang di outlet ini saja
        $stokAlert = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '<=', 5)
            ->get();

        // 2. Ringkasan pemakaian hari ini
        $pemakaianHariIni = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->whereDate('tanggal', now())
            ->get();

        // 3. Pesan belum dibaca dari admin
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->with('sender')
            ->latest()
            ->get();

        return view('user.notifikasi.index', compact(
            'stokAlert',
            'pemakaianHariIni',
            'unreadMessages'
        ));
    }
}