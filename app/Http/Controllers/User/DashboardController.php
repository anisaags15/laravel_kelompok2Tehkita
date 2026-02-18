<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Distribusi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->outlet_id) {
            abort(403, 'Outlet tidak ditemukan untuk user ini.');
        }

        $outletId = $user->outlet_id;
        $hariIni = Carbon::today();
        $awalBulan = Carbon::now()->startOfMonth();

        // 1. TOTAL JENIS BAHAN (Menghitung berapa macam barang yang ada stoknya)
        $totalStok = StokOutlet::where('outlet_id', $outletId)
            ->where('stok', '>', 0)
            ->count(); 

        // 2. PEMAKAIAN HARI INI (Total unit yang dipakai hari ini)
        $pemakaianHariIni = Pemakaian::where('outlet_id', $outletId)
            ->whereDate('tanggal', $hariIni)
            ->sum('jumlah');

        // 3. DISTRIBUSI BULAN INI (Hanya hitung barang masuk dari tanggal 1 sampai sekarang)
        // Ini solusi supaya angka '100' tadi tidak muncul terus kalau datanya data lama
        $distribusi = Distribusi::where('outlet_id', $outletId)
            ->whereBetween('created_at', [$awalBulan, Carbon::now()])
            ->sum('jumlah');

        // 4. DATA TABEL STOK (Kiri)
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $outletId)
            ->get();

        // 5. DATA TABEL PEMAKAIAN (Kanan)
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $outletId)
            ->latest('tanggal')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalStok',
            'pemakaianHariIni',
            'distribusi',
            'stokOutlets',
            'pemakaians'
        ));
    }
}