<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Message;

class NotifikasiController extends Controller
{
    // Halaman notifikasi untuk user
    public function index()
    {
        $user = Auth::user();

        // 1️⃣ Stok kurang
        $stokAlert = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '<=', 5)
            ->get();

        // 2️⃣ Pemakaian hari ini
        $pemakaianHariIni = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->whereDate('tanggal', now())
            ->get();

        // 3️⃣ Pesan belum dibaca
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->get();

        return view('user.notifikasi.index', compact(
            'stokAlert',
            'pemakaianHariIni',
            'unreadMessages'
        ));
    }
}
