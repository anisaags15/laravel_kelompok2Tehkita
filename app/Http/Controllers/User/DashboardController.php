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

        if (!$user || !$user->outlet_id) {
            abort(403, 'Outlet tidak ditemukan untuk user ini.');
        }

        $outletId = $user->outlet_id;
        $hariIni = Carbon::today();
        
        // --- 1. STATISTIK UTAMA ---
        $totalStok = StokOutlet::where('outlet_id', $outletId)->sum('stok');
        $pemakaianHariIni = Pemakaian::where('outlet_id', $outletId)->whereDate('tanggal', $hariIni)->sum('jumlah');
        $distribusiTotal = Distribusi::where('outlet_id', $outletId)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->sum('jumlah');

        // --- 2. LOGIKA STATUS STOK & INFO MASUK ---
        $statusStok = ($totalStok < 50) ? 'Perlu Order' : 'Stok Aman';
        $warnaStatusStok = ($totalStok < 50) ? 'text-danger' : 'text-success';

        $distribusiTerakhir = Distribusi::where('outlet_id', $outletId)->latest()->first();
        $infoMasuk = $distribusiTerakhir ? $distribusiTerakhir->created_at->diffForHumans() : 'Belum ada data';

        // --- 3. LOGIKA TARGET PEMAKAIAN ---
        $target = $user->outlet->target_pemakaian_harian ?? 100;
        $persentaseTarget = ($target > 0) ? ($pemakaianHariIni / $target) * 100 : 0;
        
        $warnaProgress = 'bg-success'; 
        if ($persentaseTarget >= 90) {
            $warnaProgress = 'bg-danger';
        } elseif ($persentaseTarget >= 70) {
            $warnaProgress = 'bg-warning';
        }

        // --- 4. LOGIKA CHART.JS (7 HARI TERAKHIR) ---
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D'); 
            $chartData[] = (int) Pemakaian::where('outlet_id', $outletId)
                                ->whereDate('tanggal', $date)
                                ->sum('jumlah');
        }

        // --- 5. DATA TABEL & ACTIVITY FEED ---
        $stokOutlets = StokOutlet::with('bahan')->where('outlet_id', $outletId)->get();
        
        // Hanya ambil 5 data terbaru untuk ringkasan di dashboard
        $pemakaians = Pemakaian::with('bahan')->where('outlet_id', $outletId)->latest()->take(5)->get();
        
        $feedPemakaian = Pemakaian::with('bahan', 'outlet')->where('outlet_id', $outletId)
            ->select('*', DB::raw("'pemakaian' as tipe_aktivitas"))->latest()->take(5)->get();
        
        $feedDistribusi = Distribusi::with('bahan', 'outlet')->where('outlet_id', $outletId)
            ->select('*', DB::raw("'distribusi' as tipe_aktivitas"))->latest()->take(5)->get();
            
        $activityFeeds = $feedPemakaian->concat($feedDistribusi)->sortByDesc('created_at')->take(6);

        return view('user.dashboard', compact(
            'totalStok', 'statusStok', 'warnaStatusStok', 'pemakaianHariIni', 'infoMasuk',
            'distribusiTotal', 'stokOutlets', 'pemakaians', 'chartLabels', 'chartData', 
            'activityFeeds', 'persentaseTarget', 'target', 'warnaProgress'
        ));
    }

    /**
     * Halaman Khusus Riwayat Pemakaian (Full Data)
     */
    public function riwayatPemakaian(Request $request)
    {
        $outletId = Auth::user()->outlet_id;
        $query = Pemakaian::with('bahan')->where('outlet_id', $outletId);

        // Fitur Pencarian berdasarkan nama bahan
        if ($request->has('search')) {
            $query->whereHas('bahan', function($q) use ($request) {
                $q->where('nama_bahan', 'like', '%' . $request->search . '%');
            });
        }

        $riwayat = $query->latest()->paginate(15);
        return view('user.riwayat_pemakaian', compact('riwayat'));
    }

    /**
     * Halaman Khusus Riwayat Distribusi/Stok Masuk (Full Data)
     */
    public function riwayatDistribusi(Request $request)
    {
        $outletId = Auth::user()->outlet_id;
        $query = Distribusi::with('bahan')->where('outlet_id', $outletId);

        if ($request->has('search')) {
            $query->whereHas('bahan', function($q) use ($request) {
                $q->where('nama_bahan', 'like', '%' . $request->search . '%');
            });
        }

        $riwayat = $query->latest()->paginate(15);
        return view('user.riwayat.distribusi', compact('riwayat'));
    }
}