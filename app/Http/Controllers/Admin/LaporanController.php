<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\Distribusi;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Dashboard Laporan Utama
     */
    public function index(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $totalDistribusi = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah') ?? 0;
        
        $stokMasuk = $totalDistribusi;

        $outletTeraktif = Distribusi::selectRaw('outlet_id, COUNT(*) as total')
            ->with('outlet')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('outlet_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn($item) => (object)[
                'nama_outlet' => $item->outlet->nama_outlet ?? 'N/A', 
                'total' => $item->total
            ]);

        $bahanTerbanyak = Distribusi::selectRaw('bahan_id, SUM(jumlah) as total')
            ->with('bahan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('bahan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn($item) => (object)[
                'nama_bahan' => $item->bahan->nama_bahan ?? 'N/A', 
                'total' => $item->total
            ]);

        $stokMenipis = StokOutlet::where('stok', '<=', 5)->count();
        $outletAktif = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->distinct('outlet_id')
            ->count('outlet_id');

        return view('admin.laporan.index', compact(
            'totalDistribusi', 'stokMasuk', 'outletTeraktif', 
            'bahanTerbanyak', 'stokMenipis', 'outletAktif', 'bulan', 'tahun'
        ));
    }

    /**
     * Halaman List Stok Kritis (DIPERBARUI UNTUK STANDARDISASI)
     */
    public function stokKritis(Request $request)
    {
        // --- LOGIC ASLI KAMU (JANGAN DIHAPUS) ---
        $query = StokOutlet::with(['outlet', 'bahan'])->where('stok', '<=', 5);
        if ($request->has('outlet_id') && $request->outlet_id != '') {
            $query->where('outlet_id', $request->outlet_id);
        }
        $stokKritis = $query->orderBy('stok', 'asc')->get();
        $outlets = Outlet::all();
        // --- END LOGIC ASLI ---

        // --- TAMBAHAN UNTUK STANDARDISASI TAMPILAN ---
        
        // 1. Total Item Kritis Global (Tetap ada)
        $totalKritisGlobal = $stokKritis->count();

        // 2. Ringkasan per Outlet (Agar tampilan tabel 1 baris = 1 Outlet)
        // Dikelompokkan dari hasil query asli kamu agar filter tetap bekerja
        $laporanOutlet = $stokKritis->groupBy('outlet_id')->map(function ($items) {
            return (object) [
                'outlet_id' => $items->first()->outlet_id,
                'outlet' => $items->first()->outlet,
                'total_item_kritis' => $items->count()
            ];
        });

        return view('admin.laporan.stok_kritis', compact(
            'stokKritis', 
            'outlets', 
            'totalKritisGlobal', 
            'laporanOutlet'
        ));
    }

    /**
     * Cetak PDF Stok Kritis (Rekap Wilayah)
     */
    public function cetakStokKritis(Request $request)
    {
        $query = StokOutlet::with(['outlet', 'bahan'])->where('stok', '<=', 5);
        if ($request->has('outlet_id') && $request->outlet_id != '') {
            $query->where('outlet_id', $request->outlet_id);
        }
        $stokKritis = $query->orderBy('stok', 'asc')->get();
        
        $pdf = Pdf::loadView('admin.laporan.pdf.stok_kritis', compact('stokKritis'));
        return $pdf->setPaper('a4', 'portrait')->download('Laporan_Stok_Kritis_'.date('d-m-Y').'.pdf');
    }

    /**
     * List Stok Per Outlet
     */
    public function stokOutlet() 
    { 
        $outlets = Outlet::with('stokOutlet')->get(); 
        return view('admin.laporan.stok_outlet', compact('outlets')); 
    }

    /**
     * Cetak SEMUA Stok Outlet
     */
    public function cetakStokSemua()
    {
        $outlets = Outlet::with(['stokOutlet.bahan'])->get();
        $pdf = Pdf::loadView('admin.laporan.pdf.stok_outlet_semua', compact('outlets'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Stok_Semua_Outlet_'.date('d-m-Y').'.pdf');
    }

    /**
     * Detail Stok satu Outlet (Digunakan juga oleh Detail Stok Kritis)
     */
    public function detailStokOutlet(Outlet $outlet) 
    { 
        $stok = $outlet->stokOutlet()->with('bahan')->get()->map(function($item) use ($outlet) {
            $lastDistribusi = Distribusi::where('outlet_id', $outlet->id)
                ->where('bahan_id', $item->bahan_id)
                ->latest('tanggal')
                ->first();
            $item->tanggal_terakhir_diterima = $lastDistribusi ? $lastDistribusi->tanggal : null;
            return $item;
        }); 
        return view('admin.laporan.detail_stok_outlet', compact('outlet', 'stok')); 
    }

    /**
     * Cetak PDF Stok satu Outlet
     */
    public function cetakStokOutlet(Outlet $outlet) 
    { 
        $stok = $outlet->stokOutlet()->with('bahan')->get()->map(function($item) use ($outlet) {
            $lastDistribusi = Distribusi::where('outlet_id', $outlet->id)
                ->where('bahan_id', $item->bahan_id)
                ->latest('tanggal')
                ->first();
            $item->tanggal_terakhir_diterima = $lastDistribusi ? $lastDistribusi->tanggal : null;
            return $item;
        });
        
        $pdf = Pdf::loadView('admin.laporan.pdf.stok_outlet', compact('outlet', 'stok')); 
        return $pdf->download("Laporan_Stok_{$outlet->nama_outlet}.pdf"); 
    }

    /**
     * List Rekap Distribusi Bulanan
     */
    public function distribusi() 
    { 
        $distribusis = Distribusi::select(
                'outlet_id',
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('COUNT(*) as total_pengiriman'),
                DB::raw('SUM(jumlah) as total_qty')
            )
            ->with('outlet')
            ->groupBy('outlet_id', 'bulan', 'tahun')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get(); 

        return view('admin.laporan.distribusi', compact('distribusis')); 
    }

    /**
     * Detail Transaksi Distribusi
     */
    public function detailDistribusi($outlet_id, $bulan, $tahun) 
    { 
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;
        $outlet = Outlet::findOrFail($outlet_id);
        
        $items = Distribusi::with(['bahan']) 
            ->where('outlet_id', $outlet_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.laporan.detail_distribusi', compact('outlet', 'items', 'bulan', 'tahun')); 
    }

    /**
     * Cetak PDF Distribusi Bulanan
     */
    public function cetakDistribusi($outlet_id, $bulan, $tahun) 
    { 
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;
        $outlet = Outlet::findOrFail($outlet_id);
        
        $items = Distribusi::with(['bahan'])
            ->where('outlet_id', $outlet_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf.distribusi', compact('outlet', 'items', 'bulan', 'tahun')); 
        return $pdf->setPaper('a4', 'portrait')->download("Laporan_Distribusi_{$outlet->nama_outlet}.pdf"); 
    }
}