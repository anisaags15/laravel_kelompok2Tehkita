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

        // 1. STATISTIK UTAMA
        $totalStok = StokOutlet::where('outlet_id', $outletId)
            ->where('stok', '>', 0)
            ->count(); 

        $pemakaianHariIni = Pemakaian::where('outlet_id', $outletId)
            ->whereDate('tanggal', $hariIni)
            ->sum('jumlah');

        $distribusi = Distribusi::where('outlet_id', $outletId)
            ->whereBetween('created_at', [$awalBulan, Carbon::now()])
            ->sum('jumlah');

        // 2. DATA TABEL STOK (Hanya stok yang tersedia)
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $outletId)
            ->get();

        // 3. DATA TABEL PEMAKAIAN (Tampilkan 5 transaksi terakhir saja untuk ringkasan)
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $outletId)
            ->latest('tanggal')
            ->latest('created_at')
            ->take(5)
            ->get();

        // 4. LOGIKA CHART.JS (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Nama hari dalam Bahasa Indonesia (Sen, Sel, Rab, dst)
            $chartLabels[] = $date->translatedFormat('D'); 
            
            // Hitung total pemakaian gabungan semua bahan per tanggal tersebut
            $total = Pemakaian::where('outlet_id', $outletId)
                ->whereDate('tanggal', $date)
                ->sum('jumlah');
            
            $chartData[] = (int) $total;
        }

        return view('user.dashboard', compact(
            'totalStok',
            'pemakaianHariIni',
            'distribusi',
            'stokOutlets',
            'pemakaians',
            'chartLabels',
            'chartData'
        ));
    }
}