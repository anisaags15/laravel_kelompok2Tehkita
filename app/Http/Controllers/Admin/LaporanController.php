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
    public function index(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $totalDistribusi = Distribusi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->sum('jumlah') ?? 0;
        $stokMasuk = $totalDistribusi;

        $outletTeraktif = Distribusi::selectRaw('outlet_id, COUNT(*) as total')
            ->with('outlet')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->groupBy('outlet_id')->orderByDesc('total')->limit(5)->get()
            ->map(fn($item) => (object)['nama_outlet' => $item->outlet->nama_outlet ?? 'N/A', 'total' => $item->total]);

        $bahanTerbanyak = Distribusi::selectRaw('bahan_id, SUM(jumlah) as total')
            ->with('bahan')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->groupBy('bahan_id')->orderByDesc('total')->limit(5)->get()
            ->map(fn($item) => (object)['nama_bahan' => $item->bahan->nama_bahan ?? 'N/A', 'total' => $item->total]);

        $stokMenipis = StokOutlet::where('stok', '<=', 5)->count();
        $outletAktif = Distribusi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->distinct('outlet_id')->count('outlet_id');

        return view('admin.laporan.index', compact('totalDistribusi', 'stokMasuk', 'outletTeraktif', 'bahanTerbanyak', 'stokMenipis', 'outletAktif', 'bulan', 'tahun'));
    }

    public function stokKritis(Request $request)
    {
        $query = StokOutlet::with(['outlet', 'bahan'])->where('stok', '<=', 5);

        if ($request->has('outlet_id') && $request->outlet_id != '') {
            $query->where('outlet_id', $request->outlet_id);
        }

        $stokKritis = $query->orderBy('stok', 'asc')->get();
        $outlets = Outlet::all();

        return view('admin.laporan.stok_kritis', compact('stokKritis', 'outlets'));
    }

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

    public function stokOutlet() 
    { 
        $outlets = Outlet::with('stokOutlet')->get(); 
        return view('admin.laporan.stok_outlet', compact('outlets')); 
    }

    // Fungsi baru untuk ekspor semua stok outlet (Rekap Wilayah)
    public function cetakStokSemua()
    {
        $outlets = Outlet::with('stokOutlet.bahan')->get();
        // Memanggil file: resources/views/admin/laporan/pdf/stok_outlet_semua.blade.php
        $pdf = Pdf::loadView('admin.laporan.pdf.stok_outlet_semua', compact('outlets'));
        return $pdf->setPaper('a4', 'landscape')->download('Rekap_Stok_Wilayah_'.date('d-m-Y').'.pdf');
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

    public function distribusi() 
    { 
        $distribusis = Distribusi::with(['bahan', 'outlet'])->orderBy('tanggal', 'desc')->get(); 
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