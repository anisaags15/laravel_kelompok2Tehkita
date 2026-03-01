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

    public function detailDistribusi($outlet_id, $bulan, $tahun) 
    { 
        // Force conversion ke integer
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $outlet = Outlet::findOrFail($outlet_id);
        
        // REVISI: Dihapus '.kategori' agar tidak error RelationNotFound
        $items = Distribusi::with(['bahan']) 
            ->where('outlet_id', $outlet_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.laporan.detail_distribusi', compact('outlet', 'items', 'bulan', 'tahun')); 
    }

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