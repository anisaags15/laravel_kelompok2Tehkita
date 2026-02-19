<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Distribusi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Safety check
        if (!$user || !$user->outlet_id) {
            abort(403, 'Outlet tidak ditemukan untuk user ini.');
        }

        $outletId = $user->outlet_id;
        $hariIni = Carbon::today();
        $awalBulan = Carbon::now()->startOfMonth();

        // 1. STATISTIK UTAMA
        $totalStok = StokOutlet::where('outlet_id', $outletId)->where('stok', '>', 0)->count();
        $pemakaianHariIni = Pemakaian::where('outlet_id', $outletId)->whereDate('tanggal', $hariIni)->sum('jumlah');
        $distribusiTotal = Distribusi::where('outlet_id', $outletId)->whereBetween('created_at', [$awalBulan, Carbon::now()])->sum('jumlah');

        // --- LOGIKA TARGET VS REALISASI ---
        $target = $user->outlet->target_pemakaian_harian ?? 100;
        $persentaseTarget = ($target > 0) ? ($pemakaianHariIni / $target) * 100 : 0;
        
        $warnaProgress = 'bg-success';
        if ($persentaseTarget >= 90) {
            $warnaProgress = 'bg-danger';
        } elseif ($persentaseTarget >= 70) {
            $warnaProgress = 'bg-warning';
        }

        // 2. DATA TABEL & FEED
        $stokOutlets = StokOutlet::with('bahan')->where('outlet_id', $outletId)->get();
        $pemakaians = Pemakaian::with('bahan')->where('outlet_id', $outletId)->latest()->take(5)->get();
        
        $feedPemakaian = Pemakaian::with('bahan', 'outlet')->where('outlet_id', $outletId)
            ->select('*', DB::raw("'pemakaian' as tipe_aktivitas"))->latest()->take(5)->get();
        
        $feedDistribusi = Distribusi::with('bahan', 'outlet')->where('outlet_id', $outletId)
            ->select('*', DB::raw("'distribusi' as tipe_aktivitas"))->latest()->take(5)->get();
            
        $activityFeeds = $feedPemakaian->concat($feedDistribusi)->sortByDesc('created_at')->take(6);

        // 3. LOGIKA CHART.JS (7 HARI TERAKHIR) - INI YANG TADI HILANG!
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Nama hari (Sen, Sel, Rab, dst)
            $chartLabels[] = $date->translatedFormat('D'); 
            
            // Total pemakaian per tanggal
            $total = Pemakaian::where('outlet_id', $outletId)
                ->whereDate('tanggal', $date)
                ->sum('jumlah');
            
            $chartData[] = (int) $total;
        }

        // Sekarang semua variabel sudah siap dikirim ke view
        return view('user.dashboard', compact(
            'totalStok', 
            'pemakaianHariIni', 
            'distribusiTotal', 
            'stokOutlets', 
            'pemakaians', 
            'chartLabels', 
            'chartData', 
            'activityFeeds', 
            'persentaseTarget', 
            'target', 
            'warnaProgress'
        ));
    }
}