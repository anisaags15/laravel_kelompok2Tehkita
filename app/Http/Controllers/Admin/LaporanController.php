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

class LaporanController extends Controller
{
    /* ===============================
        HALAMAN UTAMA DASHBOARD LAPORAN
    =============================== */
    public function index(Request $request)
    {
        // Force typecasting ke (int) untuk PHP 8.4 compatibility
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
            ->map(function ($item) {
                return (object)[
                    'nama_outlet' => $item->outlet->nama_outlet ?? 'N/A',
                    'total' => $item->total
                ];
            });

        $bahanTerbanyak = Distribusi::selectRaw('bahan_id, SUM(jumlah) as total')
            ->with('bahan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('bahan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'nama_bahan' => $item->bahan->nama_bahan ?? 'N/A',
                    'total' => $item->total
                ];
            });

        $stokMenipis = StokOutlet::where('stok', '<=', 5)->count();

        $outletAktif = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->distinct('outlet_id')
            ->count('outlet_id');

        return view('admin.laporan.index', compact(
            'totalDistribusi',
            'stokMasuk',
            'outletTeraktif',
            'bahanTerbanyak',
            'stokMenipis',
            'outletAktif',
            'bulan',
            'tahun'
        ));
    }

    /* ===============================
        CETAK PDF RINGKASAN (FIXED)
    ============================== */
    public function cetakIndex(Request $request)
    {
        // Pastikan input adalah integer agar Carbon tidak error di PHP 8.4
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        // Membuat nama bulan bahasa Indonesia
        $namaBulanAktif = Carbon::create()->month($bulan)->translatedFormat('F');
        $tahunAktif = $tahun;

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
            ->get();

        $bahanTerbanyak = Distribusi::selectRaw('bahan_id, SUM(jumlah) as total')
            ->with('bahan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('bahan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stokMenipis = StokOutlet::where('stok','<=', 5)->count();

        $outletAktif = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->distinct('outlet_id')
            ->count('outlet_id');

        // Render view ke PDF dengan semua variabel yang dibutuhkan
        $pdf = Pdf::loadView('admin.laporan.pdf.index', compact(
            'totalDistribusi',
            'stokMasuk',
            'outletTeraktif',
            'bahanTerbanyak',
            'stokMenipis',
            'outletAktif',
            'bulan',
            'tahun',
            'namaBulanAktif',
            'tahunAktif'
        ));

        // Download dengan nama file yang rapi
        return $pdf->download("Ringkasan_Laporan_{$namaBulanAktif}_{$tahun}.pdf");
    }

    /* ===============================
        HALAMAN STOK KRITIS
    =============================== */
    public function stokKritis()
    {
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();

        return view('admin.laporan.stok_kritis', compact('stokKritis'));
    }

    /* ===============================
        LAPORAN STOK PER OUTLET
    =============================== */
    public function stokOutlet()
    {
        $outlets = Outlet::with('stokOutlet')->get();
        return view('admin.laporan.stok_outlet', compact('outlets'));
    }

    public function detailStokOutlet(Outlet $outlet)
    {
        $stok = $outlet->stokOutlet()->with('bahan')->get();
        return view('admin.laporan.detail_stok_outlet', compact('outlet', 'stok'));
    }

    public function cetakStokOutlet(Outlet $outlet)
    {
        $stok = $outlet->stokOutlet()->with('bahan')->get();
        $pdf = Pdf::loadView('admin.laporan.pdf.stok_outlet', compact('outlet', 'stok'));
        return $pdf->download("Laporan_Stok_{$outlet->nama_outlet}.pdf");
    }

    /* ===============================
        LAPORAN RIWAYAT DISTRIBUSI
    =============================== */
    public function distribusi()
    {
        $distribusis = Distribusi::with(['bahan', 'outlet'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.laporan.distribusi', compact('distribusis'));
    }

    public function detailDistribusi($id)
    {
        $distribusi = Distribusi::with(['bahan', 'outlet'])->findOrFail($id);
        return view('admin.laporan.detail_distribusi', compact('distribusi'));
    }

    public function cetakDistribusi($id)
    {
        $distribusi = Distribusi::with(['bahan', 'outlet'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.laporan.pdf.distribusi', compact('distribusi'));
        return $pdf->download("Distribusi_{$distribusi->outlet->nama_outlet}.pdf");
    }
}