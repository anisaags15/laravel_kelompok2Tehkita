<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\Distribusi;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
// Gunakan Facade PDF yang benar untuk Laravel
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /* ===============================
        HALAMAN UTAMA LAPORAN
    =============================== */
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $totalDistribusi = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

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

        // Samakan standar kritis dengan dashboard (<= 5)
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
        HALAMAN STOK KRITIS (REDIRECT DARI DASHBOARD)
    =============================== */
    public function stokKritis()
    {
        // Ambil data stok di bawah atau sama dengan 5
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();

        return view('admin.laporan.stok_kritis', compact('stokKritis'));
    }

    /* ===============================
        CETAK PDF RINGKASAN LAPORAN
    ============================== */
    public function cetakIndex(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $totalDistribusi = Distribusi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

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

        $pdf = Pdf::loadView('admin.laporan.pdf.index', compact(
            'totalDistribusi',
            'stokMasuk',
            'outletTeraktif',
            'bahanTerbanyak',
            'stokMenipis',
            'outletAktif',
            'bulan',
            'tahun'
        ));

        return $pdf->download("Ringkasan_Laporan_{$bulan}_{$tahun}.pdf");
    }

    /* ===============================
        LAPORAN STOK OUTLET
    =============================== */
    public function stokOutlet()
    {
        $outlets = Outlet::with('stokOutlet')->get();
        return view('admin.laporan.stok_outlet', compact('outlets'));
    }

    public function detailStokOutlet(Outlet $outlet)
    {
        $stok = $outlet->stokOutlet()
            ->with('bahan')
            ->get();

        return view('admin.laporan.detail_stok_outlet', compact('outlet', 'stok'));
    }

    public function cetakStokOutlet(Outlet $outlet)
    {
        $stok = $outlet->stokOutlet()
            ->with('bahan')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf.stok_outlet', compact('outlet', 'stok'));

        return $pdf->download("Laporan_Stok_{$outlet->nama_outlet}.pdf");
    }

    /* ===============================
        LAPORAN DISTRIBUSI
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
        $distribusi = Distribusi::with(['bahan', 'outlet'])
            ->findOrFail($id);

        return view('admin.laporan.detail_distribusi', compact('distribusi'));
    }

    public function cetakDistribusi($id)
    {
        $distribusi = Distribusi::with(['bahan', 'outlet'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('admin.laporan.pdf.distribusi', compact('distribusi'));

        return $pdf->download("Distribusi_{$distribusi->outlet->nama_outlet}.pdf");
    }
}